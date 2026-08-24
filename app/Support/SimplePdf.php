<?php

namespace App\Support;

class SimplePdf
{
    /**
     * Build a compact text-only PDF. This avoids adding a heavy dependency while
     * still giving users a downloadable PDF for operational reports.
     *
     * @param  array<int, string>  $lines
     */
    public static function make(string $title, array $lines): string
    {
        $safeLines = collect([$title, str_repeat('=', min(72, max(12, strlen($title))))])
            ->merge($lines)
            ->map(fn ($line) => self::pdfText((string) $line))
            ->values();

        $content = "BT\n/F1 10 Tf\n50 790 Td\n14 TL\n";
        foreach ($safeLines as $line) {
            $content .= "({$line}) Tj\nT*\n";
        }
        $content .= "ET";

        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n",
            "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
            "5 0 obj\n<< /Length ".strlen($content)." >>\nstream\n{$content}\nendstream\nendobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        return $pdf."trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
    }

    private static function pdfText(string $text): string
    {
        $text = preg_replace('/[^\x20-\x7E]/', ' ', $text) ?? '';
        $text = preg_replace('/\s+/', ' ', $text) ?? '';

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], substr(trim($text), 0, 110));
    }
}
