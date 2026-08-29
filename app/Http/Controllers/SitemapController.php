<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Note;
use App\Models\Perfume;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Static routes worth indexing, with a rough crawl priority.
     * /articles is deliberately excluded while that section is unfinished.
     */
    private const STATIC_ROUTES = [
        ['home', 1.0],
        ['explore', 0.9],
        ['brands.index', 0.9],
        ['notes.index', 0.9],
        ['academy', 0.8],
        ['academy.introduction', 0.6],
        ['academy.notes', 0.6],
        ['academy.families', 0.6],
        ['academy.concentration', 0.6],
        ['academy.performance', 0.6],
        ['academy.seasons', 0.6],
        ['academy.application', 0.6],
        ['academy.collection', 0.6],
        ['about', 0.5],
    ];

    public function index(): Response
    {
        $urls = [];

        foreach (self::STATIC_ROUTES as [$name, $priority]) {
            $urls[] = ['loc' => route($name), 'priority' => $priority];
        }

        foreach (Perfume::orderBy('id')->get(['slug', 'updated_at']) as $perfume) {
            $urls[] = [
                'loc' => route('perfume.detail', $perfume->slug),
                'lastmod' => $perfume->updated_at,
                'priority' => 0.7,
            ];
        }

        foreach (Brand::orderBy('id')->get(['slug', 'updated_at']) as $brand) {
            $urls[] = [
                'loc' => route('brand.detail', $brand->slug),
                'lastmod' => $brand->updated_at,
                'priority' => 0.7,
            ];
        }

        foreach (Note::orderBy('id')->get(['slug', 'updated_at']) as $note) {
            $urls[] = [
                'loc' => route('note.detail', $note->slug),
                'lastmod' => $note->updated_at,
                'priority' => 0.6,
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
