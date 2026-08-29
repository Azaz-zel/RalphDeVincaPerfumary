<?php

namespace App\Http\Controllers;

use App\Models\Perfume;


class PerfumeController extends Controller
{
    public function show($slug)
    {
        $perfume = Perfume::with([
            'brand',
            'fragranceFamily',
            'notes.characteristics',
            'seasons',
            'occasions',
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        $similarPerfumes = Perfume::with([
            'brand',
            'fragranceFamily',
            'seasons',
        ])
        ->where('id', '!=', $perfume->id)
        ->where('fragrance_family_id', $perfume->fragrance_family_id)
        ->latest()
        ->take(4)
        ->get();

        return view('pages.perfume-detail', compact(
            'perfume',
            'similarPerfumes'
        ));
    }
}