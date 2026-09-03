<?php

namespace App\Services;

use App\Models\Sku;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SkuShelfAnalyzer
{
    public function analyze(UploadedFile $photo, Collection $skus, ?string $expectedCategory = null): array
    {
        if ($skus->isEmpty()) {
            return $this->manualFallback('no_skus', 'No SKUs are configured for detection yet.', [], $expectedCategory);
        }

        $skuCatalog = $this->skuCatalog($skus);
        $expectedCategory = $expectedCategory ? $this->normalizeCategoryLabel($expectedCategory) : null;
        $prompt = $this->buildPrompt($skuCatalog, $expectedCategory);
        $imageBytes = file_get_contents($photo->getRealPath());
        $mime = $photo->getMimeType() ?: 'image/jpeg';
        $attempts = [];

        foreach ($this->providerOrder() as $provider) {
            $result = match ($provider) {
                'openai' => $this->analyzeWithOpenAi($prompt, $imageBytes, $mime, $skus, $expectedCategory),
                'gemini' => $this->analyzeWithGemini($prompt, $imageBytes, $mime, $skus, $expectedCategory),
                default => null,
            };

            if (! $result) {
                continue;
            }

            $attempts[] = [
                'provider' => $provider,
                'status' => $result['status'] ?? 'unknown',
                'message' => $result['message'] ?? null,
            ];

            if (($result['status'] ?? null) === 'completed') {
                $result['attempts'] = $attempts;
                return $result;
            }

            if (($result['status'] ?? null) === 'wrong_category') {
                $result['attempts'] = $attempts;
                return $result;
            }
        }

        return $this->manualFallback(
            'manual_fallback',
            'Both AI providers could not produce a confident result. Continue with manual SKU entry.',
            $attempts,
            $expectedCategory
        );
    }

    private function providerOrder(): array
    {
        $provider = strtolower((string) config('services.ai.provider', 'auto'));

        return match ($provider) {
            'openai' => ['openai'],
            'gemini' => ['gemini'],
            default => ['openai', 'gemini'],
        };
    }

    private function analyzeWithOpenAi(string $prompt, string $imageBytes, string $mime, Collection $skus, ?string $expectedCategory): array
    {
        if (! config('services.openai.api_key')) {
            return [
                'status' => 'not_configured',
                'message' => 'OpenAI API key is not configured.',
                'category' => $expectedCategory,
                'provider' => 'openai',
                'model' => config('services.openai.vision_model'),
                'detections' => [],
                'review_required' => true,
            ];
        }

        $payload = [
            'model' => config('services.openai.vision_model'),
            'input' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'input_text', 'text' => $prompt],
                        ['type' => 'input_image', 'image_url' => $this->dataUrl($imageBytes, $mime)],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->aiHttp()
                ->withToken(config('services.openai.api_key'))
                ->post('https://api.openai.com/v1/responses', $payload);

            if ($response->failed()) {
                return $this->providerError('openai', config('services.openai.vision_model'), $response->status(), $response->body());
            }

            return $this->providerResult('openai', config('services.openai.vision_model'), $this->parseTextResponse($response->json()), $skus, $expectedCategory);
        } catch (\Throwable $exception) {
            report($exception);

            return $this->providerException('openai', config('services.openai.vision_model'), $exception->getMessage());
        }
    }

    private function analyzeWithGemini(string $prompt, string $imageBytes, string $mime, Collection $skus, ?string $expectedCategory): array
    {
        if (! config('services.gemini.api_key')) {
            return [
                'status' => 'not_configured',
                'message' => 'Gemini API key is not configured.',
                'category' => $expectedCategory,
                'provider' => 'gemini',
                'model' => config('services.gemini.model'),
                'detections' => [],
                'review_required' => true,
            ];
        }

        $baseUrl = rtrim((string) config('services.gemini.base_url'), '/');
        $model = config('services.gemini.model');
        $url = "{$baseUrl}/v1beta/models/{$model}:generateContent?key=" . urlencode((string) config('services.gemini.api_key'));
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mime,
                                'data' => base64_encode($imageBytes),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->aiHttp()->post($url, $payload);

            if ($response->failed()) {
                return $this->providerError('gemini', $model, $response->status(), $response->body());
            }

            return $this->providerResult('gemini', $model, $this->parseGeminiResponse($response->json()), $skus, $expectedCategory);
        } catch (\Throwable $exception) {
            report($exception);

            return $this->providerException('gemini', $model, $exception->getMessage());
        }
    }

    private function providerResult(string $provider, string $model, array $parsed, Collection $skus, ?string $expectedCategory): array
    {
        $categoryValidation = $this->normalizeCategoryValidation($parsed['category_validation'] ?? [], $expectedCategory);
        $isWrongCategory = $expectedCategory !== null
            && ($categoryValidation['matches_expected_category'] ?? null) === false;
        $detections = $isWrongCategory
            ? []
            : $this->normalizeDetections($parsed['detections'] ?? [], $skus, $expectedCategory);
        $averageConfidence = $this->averageConfidence($detections);
        $reviewRequired = empty($detections)
            || $averageConfidence < (float) config('services.ai.review_threshold', 0.75)
            || $isWrongCategory
            || collect($detections)->contains(fn ($d) => ($d['review_required'] ?? false) === true);

        return [
            'status' => $isWrongCategory ? 'wrong_category' : (empty($detections) ? 'no_detection' : 'completed'),
            'message' => $isWrongCategory
                ? 'Wrong Category Image. Please capture the '.$expectedCategory.' section.'
                : (empty($detections)
                ? $this->providerLabel($provider) . ' could not confidently identify configured SKUs.'
                : $this->providerLabel($provider) . ' detection completed. Please review and correct the results before submitting.'),
            'category' => $expectedCategory,
            'category_key' => $expectedCategory ? Str::slug($expectedCategory, '_') : null,
            'category_validation' => $categoryValidation,
            'provider' => $provider,
            'model' => $model,
            'detections' => $detections,
            'average_confidence' => $averageConfidence,
            'review_required' => $reviewRequired,
            'raw_text' => $parsed['raw_text'] ?? null,
        ];
    }

    private function providerError(string $provider, string $model, int $status, string $body): array
    {
        return [
            'status' => 'provider_error',
            'message' => $this->providerLabel($provider) . ' failed. Trying fallback if available.',
            'provider' => $provider,
            'model' => $model,
            'detections' => [],
            'review_required' => true,
            'error' => [
                'status' => $status,
                'body' => Str::limit($body, 1000),
            ],
        ];
    }

    private function providerException(string $provider, string $model, string $message): array
    {
        return [
            'status' => 'provider_exception',
            'message' => $this->providerLabel($provider) . ' is temporarily unavailable. Trying fallback if available.',
            'provider' => $provider,
            'model' => $model,
            'detections' => [],
            'review_required' => true,
            'error' => [
                'message' => $message,
            ],
        ];
    }

    private function manualFallback(string $status, string $message, array $attempts, ?string $expectedCategory = null): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'category' => $expectedCategory,
            'category_key' => $expectedCategory ? Str::slug($expectedCategory, '_') : null,
            'category_validation' => [
                'expected_category' => $expectedCategory,
                'detected_category' => null,
                'matches_expected_category' => $expectedCategory === null ? null : false,
                'confidence' => 0.0,
                'notes' => 'AI category validation did not complete.',
            ],
            'provider' => 'manual',
            'model' => null,
            'detections' => [],
            'review_required' => true,
            'attempts' => $attempts,
        ];
    }

    private function skuCatalog(Collection $skus): array
    {
        return $skus
            ->map(fn (Sku $sku) => [
                'id' => $sku->id,
                'name' => $sku->name,
                'brand' => $sku->brand?->name,
                'category' => $sku->category,
                'aliases' => $sku->aliases ?? [],
                'reference_notes' => $sku->ai_reference_notes,
                'has_reference_image' => filled($sku->reference_image_path),
            ])
            ->values()
            ->all();
    }

    private function buildPrompt(array $skuCatalog, ?string $expectedCategory): string
    {
        $catalogJson = json_encode($skuCatalog, JSON_PRETTY_PRINT);
        $categoryInstruction = $expectedCategory
            ? "Expected category: {$expectedCategory}. First decide whether the image is mainly the {$expectedCategory} section. If it is not, set category_validation.matches_expected_category to false and return detections as an empty array."
            : 'No single expected category was provided.';

        return <<<PROMPT
You are a professional retail shelf audit vision system for CMIH merchandisers.

Analyze this shop shelf photo and detect only products that match the configured SKU catalog below.
Do not invent products. If a product is uncertain, mark review_required true and lower confidence.
{$categoryInstruction}

Return ONLY valid JSON with this shape:
{
  "category_validation": {
    "expected_category": "{$expectedCategory}",
    "detected_category": "short category name visible in the image",
    "matches_expected_category": true,
    "confidence": 0.0,
    "notes": "short evidence note"
  },
  "detections": [
    {
      "sku_id": 123,
      "sku_name": "Exact configured SKU name",
      "quantity": 0,
      "facing": 0,
      "share_of_shelf": 0.0,
      "planogram_compliant": true,
      "confidence": 0.0,
      "review_required": true,
      "boxes": [
        {"x": 0.0, "y": 0.0, "width": 0.0, "height": 0.0, "label": "front row"}
      ],
      "notes": "short evidence note"
    }
  ],
  "scene_notes": "short note about angle, blur, glare, occlusion, or reasons for uncertainty"
}

Counting rules:
- quantity means visible units of each SKU in the image.
- facing means visible front-facing units.
- share_of_shelf is the approximate percentage of visible shelf area occupied by that SKU using a 0 to 100 scale. Return 65 for 65%, not 0.65. If one configured SKU fills the visible shelf, return close to 100.
- boxes are normalized coordinates from 0 to 1 relative to the image.
- confidence must be between 0 and 1.
- If the shelf is blurry, angled, occluded, too far, or the label is not readable, set review_required true.
- If an expected category was provided, only return detections for SKUs in that category.
- Wrong-category examples: expected Orals but image is mostly laundry powder; expected Homecare - Dishwash but image is mostly skin cleansing.
- If no configured SKU is clearly visible, return an empty detections array.

Configured SKU catalog:
{$catalogJson}
PROMPT;
    }

    private function parseTextResponse(array $raw): array
    {
        $text = $raw['output_text'] ?? null;

        if (! $text && isset($raw['output']) && is_array($raw['output'])) {
            $parts = [];
            foreach ($raw['output'] as $outputItem) {
                foreach (($outputItem['content'] ?? []) as $content) {
                    if (isset($content['text'])) {
                        $parts[] = $content['text'];
                    }
                }
            }
            $text = trim(implode("\n", $parts));
        }

        return $this->decodeJsonText((string) $text);
    }

    private function parseGeminiResponse(array $raw): array
    {
        $parts = $raw['candidates'][0]['content']['parts'] ?? [];
        $text = trim(collect($parts)->pluck('text')->filter()->implode("\n"));

        return $this->decodeJsonText($text);
    }

    private function decodeJsonText(string $text): array
    {
        $jsonText = $this->extractJson($text);
        $decoded = json_decode($jsonText, true);

        if (! is_array($decoded)) {
            return [
                'detections' => [],
                'raw_text' => $text,
            ];
        }

        $decoded['raw_text'] = $text;

        return $decoded;
    }

    private function extractJson(string $text): string
    {
        $trimmed = trim($text);
        if (str_starts_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```(?:json)?\s*/i', '', $trimmed) ?? $trimmed;
            $trimmed = preg_replace('/\s*```$/', '', $trimmed) ?? $trimmed;
        }

        $start = strpos($trimmed, '{');
        $end = strrpos($trimmed, '}');

        if ($start !== false && $end !== false && $end > $start) {
            return substr($trimmed, $start, $end - $start + 1);
        }

        return $trimmed;
    }

    private function normalizeDetections(array $detections, Collection $skus, ?string $expectedCategory = null): array
    {
        $skuById = $skus->keyBy('id');
        $expectedCategoryKey = $expectedCategory ? $this->normalizeCategoryKey($expectedCategory) : null;
        $normalized = [];

        foreach ($detections as $detection) {
            $skuId = (int) ($detection['sku_id'] ?? 0);
            if (! $skuById->has($skuId)) {
                continue;
            }

            if ($expectedCategoryKey
                && $this->normalizeCategoryKey((string) ($skuById[$skuId]->category ?? '')) !== $expectedCategoryKey) {
                continue;
            }

            $confidence = max(0, min(1, (float) ($detection['confidence'] ?? 0)));

            $normalized[] = [
                'sku_id' => $skuId,
                'sku_name' => $skuById[$skuId]->name,
                'quantity' => max(0, (int) round((float) ($detection['quantity'] ?? 0))),
                'facing' => max(0, (int) round((float) ($detection['facing'] ?? 0))),
                'share_of_shelf' => max(0, min(100, (float) ($detection['share_of_shelf'] ?? 0))),
                'planogram_compliant' => (bool) ($detection['planogram_compliant'] ?? false),
                'confidence' => round($confidence, 2),
                'review_required' => (bool) ($detection['review_required'] ?? $confidence < (float) config('services.ai.review_threshold', 0.75)),
                'boxes' => is_array($detection['boxes'] ?? null) ? $detection['boxes'] : [],
                'notes' => (string) ($detection['notes'] ?? ''),
            ];
        }

        return $normalized;
    }

    private function normalizeCategoryValidation(array $validation, ?string $expectedCategory): array
    {
        $expected = $expectedCategory ? $this->normalizeCategoryLabel($expectedCategory) : null;
        $detected = isset($validation['detected_category'])
            ? $this->normalizeCategoryLabel((string) $validation['detected_category'])
            : null;
        $matches = $validation['matches_expected_category'] ?? null;

        if ($expected !== null) {
            if ($matches === null && $detected !== null) {
                $matches = $this->normalizeCategoryKey($detected) === $this->normalizeCategoryKey($expected);
            }

            $matches = (bool) $matches;
        }

        return [
            'expected_category' => $expected,
            'detected_category' => $detected,
            'matches_expected_category' => $expected === null ? null : $matches,
            'confidence' => round(max(0, min(1, (float) ($validation['confidence'] ?? 0))), 2),
            'notes' => trim((string) ($validation['notes'] ?? '')),
        ];
    }

    private function normalizeCategoryLabel(string $category): string
    {
        return Str::of($category)
            ->replace('–', '-')
            ->replace('—', '-')
            ->squish()
            ->toString();
    }

    private function normalizeCategoryKey(string $category): string
    {
        return Str::slug($this->normalizeCategoryLabel($category), '_');
    }

    private function averageConfidence(array $detections): float
    {
        if (empty($detections)) {
            return 0.0;
        }

        return round(collect($detections)->avg('confidence'), 2);
    }

    private function dataUrl(string $imageBytes, string $mime): string
    {
        return 'data:' . $mime . ';base64,' . base64_encode($imageBytes);
    }

    private function providerLabel(string $provider): string
    {
        return match (strtolower($provider)) {
            'openai' => 'OpenAI',
            'gemini' => 'Gemini',
            default => Str::headline($provider),
        };
    }

    private function aiHttp(): PendingRequest
    {
        $request = Http::timeout(90)
            ->acceptJson()
            ->retry(2, 900, function ($exception) {
                if ($exception instanceof ConnectionException) {
                    return true;
                }

                if ($exception instanceof RequestException && $exception->response) {
                    return in_array($exception->response->status(), [429, 500, 502, 503, 504], true);
                }

                return false;
            }, throw: false);
        $caBundle = (string) config('services.ai.ca_bundle');

        if ($caBundle !== '' && is_file($caBundle)) {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        return $request;
    }
}
