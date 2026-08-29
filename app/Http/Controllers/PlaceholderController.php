<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Note;
use App\Models\Perfume;
use Illuminate\Http\Response;

/**
 * Generates elegant, brand-neutral SVG placeholders on the fly for brands,
 * perfumes and notes that don't have real product photography — avoids
 * hosting third-party copyrighted product/brand imagery while still giving
 * each item a distinct look (monogram + deterministic colour) instead of
 * one identical generic placeholder everywhere.
 */
class PlaceholderController extends Controller
{
    private const PALETTE = [
        '#8B5E3C', '#6B4C6B', '#4A7B8C', '#A45C40', '#5B6B4D',
        '#7D4F50', '#3F5765', '#9C7A3C', '#5C4A72', '#4F6D5A',
    ];

    private const FAMILY_COLORS = [
        'Citrus' => '#C99A3B',
        'Floral' => '#B98CA0',
        'Woody' => '#6B4423',
        'Amber' => '#B8752B',
        'Vanilla' => '#C4A67A',
        'Fresh' => '#7A9471',
        'Aquatic' => '#4A7B8C',
        'Musky' => '#6B4C6B',
        'Gourmand' => '#8B5E3C',
    ];

    public function brand(string $slug): Response
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        return $this->svgResponse(view('placeholders.brand', [
            'initials' => $this->initials($brand->name),
            'name' => $brand->name,
            'color' => $this->colorFor($brand->name),
        ])->render());
    }

    public function perfume(string $slug): Response
    {
        $perfume = Perfume::with(['brand', 'fragranceFamily'])->where('slug', $slug)->firstOrFail();

        return $this->svgResponse(view('placeholders.bottle', [
            'name' => $perfume->name,
            'brand' => $perfume->brand?->name,
            'color' => self::FAMILY_COLORS[$perfume->fragranceFamily?->name] ?? '#B08D57',
            'fontSize' => strlen($perfume->name) > 22 ? 16 : 22,
        ])->render());
    }

    public function note(string $slug): Response
    {
        $note = Note::where('slug', $slug)->firstOrFail();

        return $this->svgResponse(view('placeholders.bottle', [
            'name' => $note->name,
            'brand' => null,
            'color' => $this->colorFor($note->name),
            'fontSize' => strlen($note->name) > 22 ? 16 : 22,
        ])->render());
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/[\s&\-]+/', $name, -1, PREG_SPLIT_NO_EMPTY);

        $initials = collect($parts)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(3)
            ->implode('');

        return $initials ?: '?';
    }

    private function colorFor(string $name): string
    {
        return self::PALETTE[crc32($name) % count(self::PALETTE)];
    }

    private function svgResponse(string $svg): Response
    {
        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
