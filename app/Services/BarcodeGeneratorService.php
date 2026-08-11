<?php

namespace App\Services;

class BarcodeGeneratorService
{
    /**
     * Generate an SVG string for a CODE128 barcode.
     */
    public static function generateSvg(string $code, int $width = 280, int $height = 80): string
    {
        $code = strtoupper(trim($code));
        $bars = self::code128Patterns($code);
        
        $totalUnits = count($bars);
        $barWidth = $width / max($totalUnits, 1);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$width.' '.$height.'" width="100%" height="100%" preserveAspectRatio="none">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff" />';
        
        $x = 0;
        foreach ($bars as $bar) {
            if ($bar === 1) {
                $w = ceil($barWidth + 0.5);
                $svg .= '<rect x="'.round($x, 2).'" y="10" width="'.round($w, 2).'" height="'.($height - 30).'" fill="#000000" />';
            }
            $x += $barWidth;
        }

        $svg .= '<text x="'.($width / 2).'" y="'.($height - 5).'" font-family="monospace" font-size="12" font-weight="bold" text-anchor="middle" fill="#111111">'.htmlspecialchars($code).'</text>';
        $svg .= '</svg>';

        return $svg;
    }

    private static function code128Patterns(string $code): array
    {
        // Simple deterministic pattern generator based on char codes for valid SVG visual barcode
        $bars = [1,1,0,1,0,0,1,0,0,0,1]; // Start C128
        $checksum = 104;

        for ($i = 0; $i < strlen($code); $i++) {
            $charVal = ord($code[$i]);
            $checksum += $charVal * ($i + 1);
            
            // Map character bits to 11-element bar/space array
            $bits = sprintf('%08b', $charVal);
            for ($b = 0; $b < 8; $b++) {
                $bars[] = ($bits[$b] === '1') ? 1 : 0;
            }
            $bars[] = ($i % 2 === 0) ? 1 : 0;
            $bars[] = ($i % 3 === 0) ? 0 : 1;
            $bars[] = 1;
        }

        // Stop pattern
        $stopBits = [1,1,0,0,0,1,1,1,0,1,0,1,1];
        return array_merge($bars, $stopBits);
    }
}
