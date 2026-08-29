<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\FragranceFamily;
use App\Models\Note;
use App\Models\Perfume;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        // Rotate through the trending pool daily so the home page shows a
        // different slice each day while staying stable within one visit.
        // Only ids are pulled for the whole pool — the full records (and their
        // relations) are then loaded for just the handful actually displayed.
        $trendingIds = Perfume::where('is_trending', true)
            ->orderByDesc('rating')
            ->orderBy('id')
            ->pluck('id');

        $shownIds = $this->dailySlice($trendingIds, 4);
        $heroId = $this->dailyHeroId($trendingIds, $shownIds);

        $perfumes = Perfume::with(['brand', 'fragranceFamily', 'seasons'])
            ->whereIn('id', $shownIds->push($heroId)->filter())
            ->get()
            ->keyBy('id');

        $trending = $shownIds->map(fn ($id) => $perfumes->get($id))->filter()->values();

        // The catalogue was bulk-imported, so most rows share a created_at
        // value. Falling back to id keeps "newest" stable between requests
        // instead of letting MySQL return tied rows in arbitrary order.
        $newest = Perfume::with(['brand', 'fragranceFamily', 'seasons'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        return view('pages.home', [
            'trending' => $trending,

            // The hero artwork also rotates daily, picked from the trending
            // pool but offset so it isn't a duplicate of the cards below.
            'heroPerfume' => $heroId ? $perfumes->get($heroId) : null,

            'newest' => $newest,
            'featuredBrand' => $this->featuredBrand(),
            'featuredNote' => $this->featuredNote(),

            'families' => FragranceFamily::withCount('perfumes')
                ->orderByDesc('perfumes_count')
                ->take(6)
                ->get(),

            'stats' => [
                'perfumes' => Perfume::count(),
                'brands' => Brand::count(),
                'notes' => Note::count(),
            ],
        ]);
    }

    /**
     * Rotations advance once a day rather than on every request, so the home
     * page feels alive without shuffling while a visitor is browsing it.
     */
    private function dailyOffset(int $total): int
    {
        return $total > 0 ? (int) date('z') % $total : 0;
    }

    /**
     * Takes `$count` items starting at today's offset, wrapping around the
     * end of the collection so the slice is always full.
     */
    private function dailySlice(Collection $items, int $count): Collection
    {
        if ($items->count() <= $count) {
            return $items;
        }

        $offset = $this->dailyOffset($items->count());

        return $items->concat($items)->slice($offset, $count)->values();
    }

    /**
     * Picks the daily hero fragrance, preferring one that isn't already shown
     * in the trending cards so the page doesn't repeat the same bottle twice.
     */
    private function dailyHeroId(Collection $poolIds, Collection $shownIds): ?int
    {
        if ($poolIds->isEmpty()) {
            return null;
        }

        $remaining = $poolIds->diff($shownIds)->values();

        if ($remaining->isEmpty()) {
            return $shownIds->first();
        }

        return $remaining->get($this->dailyOffset($remaining->count()));
    }

    private function featuredBrand(): ?Brand
    {
        $candidates = Brand::withCount('perfumes')
            ->having('perfumes_count', '>=', 2)
            ->whereNotNull('about')
            ->orderBy('id');

        $total = $candidates->count();

        return $candidates->skip($this->dailyOffset($total))->first();
    }

    private function featuredNote(): ?Note
    {
        $candidates = Note::withCount('perfumes')
            ->having('perfumes_count', '>=', 3)
            ->whereNotNull('description')
            ->orderBy('id');

        $total = $candidates->count();

        return $candidates->with('characteristics')
            ->skip($this->dailyOffset($total))
            ->first();
    }
}
