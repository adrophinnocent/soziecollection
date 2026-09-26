<?php

namespace App\Support;

/**
 * Builds the campaign artwork for the homepage hero slides.
 *
 * The hero renders every slide's words as HTML on top of this artwork, so the
 * designs are deliberately text free: pure obsidian and champagne gold geometry
 * in the brand's clipped-corner language. Keeping them vector also means no
 * binary assets to ship and no external image host anywhere in the storefront.
 */
final class SlideArtwork
{
    private const OBSIDIAN = '#0C0A09';

    private const OBSIDIAN_SOFT = '#17130F';

    private const GOLD = '#A8895F';

    private const GOLD_BRIGHT = '#D4AF37';

    private const GOLD_LIGHT = '#C5A059';

    private const GOLD_CREAM = '#F3E5AB';

    private const WIDTH = 960;

    private const HEIGHT = 1200;

    /**
     * @return array<string, string> Map of variant key to raw SVG markup.
     */
    public static function variants(): array
    {
        return [
            'golden-aura' => self::aura(),
            'noir-imperial' => self::imperial(),
            'scent-finder' => self::constellation(),
            'vip-circle' => self::vip(),
        ];
    }

    private static function aura(): string
    {
        $rings = '';

        foreach ([210, 160, 112, 68] as $index => $radius) {
            $opacity = 0.5 - ($index * 0.1);
            $rings .= sprintf(
                '<circle cx="480" cy="640" r="%d" fill="none" stroke="%s" stroke-width="1.2" opacity="%.2f"/>',
                $radius,
                self::GOLD,
                $opacity
            );
        }

        return self::wrap(
            self::radialGlow(480, 620, 430, self::GOLD_BRIGHT, 0.30),
            '<circle cx="480" cy="640" r="300" fill="url(#fade)"/>'
            .'<circle cx="480" cy="640" r="300" fill="none" stroke="'.self::GOLD_LIGHT.'" stroke-width="0.8" opacity="0.35"/>'
            .$rings
            .self::bottle(480, 880, 1.45, 0.88)
            .self::polygon(1180, 90, 250, 1, self::GOLD, 0.22)
            .self::polygon(-60, 1010, 200, 0, self::GOLD_LIGHT, 0.16)
        );
    }

    private static function imperial(): string
    {
        $blade = '';

        foreach ([0, 1, 2] as $index) {
            $offset = 190 + ($index * 96);
            $blade .= sprintf(
                '<path d="M%d 120 L%d 120 L%d 1180 L%d 1180 Z" fill="url(#blade)" opacity="%.2f"/>',
                $offset,
                $offset + 26,
                $offset - 210,
                $offset - 236,
                0.5 - ($index * 0.13)
            );
        }

        return self::wrap(
            self::radialGlow(300, 340, 470, self::GOLD_LIGHT, 0.22),
            '<defs><linearGradient id="blade" x1="0" y1="0" x2="1" y2="1">'
            .'<stop offset="0%" stop-color="'.self::GOLD_CREAM.'" stop-opacity="0.55"/>'
            .'<stop offset="55%" stop-color="'.self::GOLD.'" stop-opacity="0.18"/>'
            .'<stop offset="100%" stop-color="'.self::OBSIDIAN.'" stop-opacity="0"/>'
            .'</linearGradient></defs>'
            .$blade
            .self::polygon(560, 300, 300, 0, self::GOLD_BRIGHT, 0.20)
            .self::bottle(470, 900, 1.55, 0.95)
            .self::frame(56)
        );
    }

    private static function constellation(): string
    {
        $dots = '';

        $points = [
            [190, 300], [330, 205], [455, 330], [600, 190], [720, 315],
            [255, 520], [520, 470], [700, 520], [175, 700], [385, 640],
            [620, 700], [790, 640], [300, 880], [500, 830], [690, 900],
        ];

        foreach ($points as $index => [$x, $y]) {
            $radius = $index % 3 === 0 ? 5.5 : 3.2;
            $dots .= sprintf(
                '<circle cx="%d" cy="%d" r="%.1f" fill="%s" opacity="%.2f"/>',
                $x,
                $y,
                $radius,
                $index % 4 === 0 ? self::GOLD_CREAM : self::GOLD_BRIGHT,
                $index % 3 === 0 ? 0.75 : 0.42
            );
        }

        $lines = '';

        foreach ([[0, 1], [1, 2], [2, 3], [5, 6], [6, 7], [8, 9], [9, 10], [10, 11], [12, 13], [13, 14]] as [$a, $b]) {
            $lines .= sprintf(
                '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="%s" stroke-width="0.7" opacity="0.28"/>',
                $points[$a][0],
                $points[$a][1],
                $points[$b][0],
                $points[$b][1],
                self::GOLD
            );
        }

        return self::wrap(
            self::radialGlow(480, 560, 460, self::GOLD, 0.24),
            $lines.$dots
            .self::bottle(480, 900, 1.35, 0.85)
            .self::polygon(60, 60, 200, 0, self::GOLD, 0.18)
            .self::polygon(700, 940, 200, 1, self::GOLD_LIGHT, 0.18)
        );
    }

    private static function vip(): string
    {
        $arch = '';

        foreach ([250, 190, 130] as $index => $radius) {
            $arch .= sprintf(
                '<path d="M%d 900 A%d %d 0 0 1 %d 900" fill="none" stroke="%s" stroke-width="1.4" opacity="%.2f"/>',
                480 - $radius,
                $radius,
                $radius,
                480 + $radius,
                $index === 0 ? self::GOLD_LIGHT : self::GOLD,
                0.55 - ($index * 0.15)
            );
        }

        return self::wrap(
            self::radialGlow(480, 880, 420, self::GOLD_BRIGHT, 0.28),
            $arch
            .'<circle cx="480" cy="900" r="34" fill="none" stroke="'.self::GOLD_CREAM.'" stroke-width="1" opacity="0.55"/>'
            .'<circle cx="480" cy="900" r="6" fill="'.self::GOLD_CREAM.'" opacity="0.8"/>'
            .self::polygon(120, 120, 210, 0, self::GOLD, 0.20)
            .self::polygon(640, 1040, 190, 1, self::GOLD, 0.16)
            .self::bottle(480, 880, 1.30, 0.80)
        );
    }

    /**
     * The perfume bottle the brand is built around: a faceted flacon with a liquid
     * fill, a label band and a cap that sits squarely on the shoulders.
     *
     * Geometry: the cap rests on the body top, the shoulders are clipped corners,
     * and the label bands are inset. Sizes are derived from one scale so the
     * proportions hold at any size.
     */
    private static function bottle(int $cx, int $baseY, float $scale, float $opacity): string
    {
        $w = 170 * $scale;
        $h = 250 * $scale;
        $x = $cx - ($w / 2);
        $y = $baseY - $h;

        $capHeight = $h * 0.15;
        $neckWidth = $w * 0.32;
        $cut = $w * 0.14;
        $shoulderY = $y + $capHeight;
        $capY = $shoulderY - $capHeight;
        $stroke = self::GOLD;

        $body = sprintf(
            'M%.2f %.2f L%.2f %.2f L%.2f %.2f L%.2f %.2f Z',
            $x + $cut, $shoulderY,
            $x + $w - $cut, $shoulderY,
            $x + $w, $baseY,
            $x, $baseY
        );

        $cap = sprintf(
            'M%.2f %.2f L%.2f %.2f L%.2f %.2f L%.2f %.2f Z',
            $cx - ($neckWidth / 2) + ($neckWidth * 0.10), $capY,
            $cx + ($neckWidth / 2) - ($neckWidth * 0.10), $capY,
            $cx + ($neckWidth / 2), $shoulderY,
            $cx - ($neckWidth / 2), $shoulderY
        );

        $labelTop = $shoulderY + ($h * 0.30);
        $labelBottom = $shoulderY + ($h * 0.52);

        return sprintf(
            '<g opacity="%.2f">'
            .'<path d="%s" fill="url(#liquid)"/>'
            .'<path d="%s" fill="none" stroke="%s" stroke-width="2"/>'
            .'<path d="%s" fill="none" stroke="%s" stroke-width="2"/>'
            .'<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f" stroke="%s" stroke-width="1" opacity="0.55"/>'
            .'<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f" stroke="%s" stroke-width="1" opacity="0.35"/>'
            .'<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f" stroke="%s" stroke-width="1.6" opacity="0.8"/>'
            .'</g>',
            $opacity,
            $body,
            $body,
            $stroke,
            $cap,
            self::GOLD_LIGHT,
            $x + ($w * 0.18), $labelTop, $x + ($w * 0.82), $labelTop, self::GOLD,
            $x + ($w * 0.18), $labelBottom, $x + ($w * 0.82), $labelBottom, self::GOLD,
            $cx - ($neckWidth / 2), $shoulderY, $cx + ($neckWidth / 2), $shoulderY, self::GOLD_LIGHT
        );
    }

    private static function polygon(int $cx, int $cy, int $size, int $variant, string $stroke, float $opacity): string
    {
        $half = $size / 2;
        $cut = $size * 0.22;
        $points = $variant === 1
            ? sprintf('%d,%d %d,%d %d,%d %d,%d %d,%d %d,%d', $cx + $cut, $cy - $half, $cx + $half, $cy - $half, $cx + $half, $cy + $half - $cut, $cx + $half - $cut, $cy + $half, $cx - $half, $cy + $half, $cx - $half, $cy - $half + $cut)
            : sprintf('%d,%d %d,%d %d,%d %d,%d %d,%d %d,%d', $cx - $half, $cy - $half, $cx + $half - $cut, $cy - $half, $cx + $half, $cy + $half, $cx - $half + $cut, $cy + $half, $cx - $half, $cy + $half - $cut, $cx - $half, $cy - $half);

        return sprintf(
            '<polygon points="%s" fill="none" stroke="%s" stroke-width="1.1" opacity="%.2f"/>',
            $points,
            $stroke,
            $opacity
        );
    }

    private static function frame(int $inset): string
    {
        $w = self::WIDTH - ($inset * 2);
        $h = self::HEIGHT - ($inset * 2);
        $cut = 34;

        return sprintf(
            '<polygon points="%d,%d %d,%d %d,%d %d,%d %d,%d %d,%d" fill="none" stroke="%s" stroke-width="0.7" opacity="0.22"/>',
            $inset, $inset + $cut,
            $inset + $w - $cut, $inset,
            $inset + $w, $inset + $h - $cut,
            $inset + $w, $inset + $h,
            $inset + $cut, $inset + $h,
            $inset, $inset + $h - $cut,
            self::GOLD
        );
    }

    private static function radialGlow(int $cx, int $cy, int $radius, string $colour, float $opacity): string
    {
        return sprintf(
            '<defs><radialGradient id="glow" cx="50%%" cy="50%%" r="50%%">'
            .'<stop offset="0%%" stop-color="%s" stop-opacity="%.2f"/>'
            .'<stop offset="60%%" stop-color="%s" stop-opacity="%.2f"/>'
            .'<stop offset="100%%" stop-color="%s" stop-opacity="0"/>'
            .'</radialGradient></defs>'
            .'<circle cx="%d" cy="%d" r="%d" fill="url(#glow)"/>',
            $colour,
            $opacity,
            $colour,
            $opacity * 0.35,
            $colour,
            $cx,
            $cy,
            $radius
        );
    }

    private static function wrap(string $defs, string $body): string
    {
        return implode("\n", [
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.self::WIDTH.' '.self::HEIGHT.'" width="'.self::WIDTH.'" height="'.self::HEIGHT.'" role="img">',
            '<defs>',
            '<linearGradient id="bg" x1="0" y1="0" x2="0.4" y2="1">',
            '<stop offset="0%" stop-color="#1A1512"/>',
            '<stop offset="55%" stop-color="'.self::OBSIDIAN_SOFT.'"/>',
            '<stop offset="100%" stop-color="'.self::OBSIDIAN.'"/>',
            '</linearGradient>',
            '<radialGradient id="fade" cx="50%" cy="50%" r="50%">',
            '<stop offset="0%" stop-color="'.self::GOLD.'" stop-opacity="0.16"/>',
            '<stop offset="100%" stop-color="'.self::GOLD.'" stop-opacity="0"/>',
            '</radialGradient>',
            '<linearGradient id="liquid" x1="0" y1="0" x2="0" y2="1">',
            '<stop offset="0%" stop-color="'.self::GOLD_CREAM.'" stop-opacity="0.34"/>',
            '<stop offset="100%" stop-color="'.self::GOLD.'" stop-opacity="0.05"/>',
            '</linearGradient>',
            '<linearGradient id="sweep" x1="0" y1="0" x2="1" y2="0.6">',
            '<stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.07"/>',
            '<stop offset="45%" stop-color="#FFFFFF" stop-opacity="0"/>',
            '</linearGradient>',
            '</defs>',
            '<rect width="'.self::WIDTH.'" height="'.self::HEIGHT.'" fill="url(#bg)"/>',
            $defs,
            $body,
            '<rect width="'.self::WIDTH.'" height="'.self::HEIGHT.'" fill="url(#sweep)"/>',
            '</svg>',
        ])."\n";
    }
}
