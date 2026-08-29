<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $perfumes = $this->getPerfumes($request);

        return view('pages.explore', [
            'perfumes' => $perfumes,
            'search' => trim($request->input('search', '')),
            'trending' => $request->boolean('trending'),
            'type' => $request->input('type'),
            'families' => (array) $request->input('family', []),
        ]);
    }

    public function filter(Request $request)
    {
        $perfumes = $this->getPerfumes($request);

        return response()->json([
            'grid' => view('components.explore-grid', [
                'perfumes' => $perfumes,
                'search' => trim($request->input('search', '')),
            ])->render(),

            'pagination' => view('components.explore-pagination', [
                'perfumes' => $perfumes,
            ])->render(),

            'total' => $perfumes->total(),
        ]);
    }

    private function getPerfumes(Request $request)
    {
        $search = trim($request->input('search', ''));
        $trending = $request->boolean('trending');
        $type = $request->input('type');
        $season = $request->input('season');
        $gender = $request->input('gender');
        $sort = $request->input('sort', 'Most Popular');
        $families = (array) $request->input('family', []);

        // Only the relations the grid actually renders are eager-loaded.
        // Notes/seasons/occasions are still filterable below via whereHas,
        // which doesn't require loading them onto every result.
        return Perfume::with([
            'brand',
            'fragranceFamily',
        ])

        // ===============================
        // Search
        // ===============================

        ->when($search !== '', function ($query) use ($search) {

            $query->where(function ($query) use ($search) {

                $query->where('name', 'like', "%{$search}%")

                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('fragranceFamily', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('notes', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });

            });

        })

        // ===============================
        // Trending
        // ===============================

        ->when($trending, function ($query) {

            $query->where('is_trending', true);

        })

        // ===============================
        // Designer / Niche
        // ===============================

        ->when($type, function ($query) use ($type) {

            $query->whereHas('brand', function ($query) use ($type) {

                $query->where('type', $type);

            });

        })

        // ===============================
        // Fragrance Family
        // ===============================

        ->when(!empty($families), function ($query) use ($families) {

            $query->whereHas('fragranceFamily', function ($query) use ($families) {

                $query->whereIn('name', $families);

            });

        })

        // ===============================
        // Season
        // ===============================

        ->when($season, function ($query) use ($season) {

            $query->whereHas('seasons', function ($query) use ($season) {

                $query->where('name', $season);

            });

        })

        // ===============================
        // Gender
        // ===============================

        ->when($gender, function ($query) use ($gender) {

            $query->where('gender', $gender);

        })

        // ===============================
        // Sorting
        // ===============================

        ->when($sort === 'Most Popular', function ($query) {

            $query->orderByDesc('is_trending')
                ->orderByDesc('created_at');

        })

        ->when($sort === 'Newest', function ($query) {

            $query->orderByDesc('created_at');

        })

        ->when($sort === 'Highest Rated', function ($query) {

            $query->orderByDesc('rating');

        })

        ->when($sort === 'A-Z', function ($query) {

            $query->orderBy('name', 'asc');

        })

        ->when($sort === 'Z-A', function ($query) {

            $query->orderBy('name', 'desc');

        })

        // ===============================
        // Pagination
        // ===============================

        ->paginate(12)

        ->withQueryString()

        ->withPath(route('explore'));
    }
}