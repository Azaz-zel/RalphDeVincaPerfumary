<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        // Unrecognised filter values are ignored rather than returning an
        // empty list, matching how BrandController handles its type filter.
        $validFamilies = Note::query()
            ->whereNotNull('fragrance_family')
            ->distinct()
            ->pluck('fragrance_family');

        $family = $validFamilies->contains($request->input('family'))
            ? $request->input('family')
            : null;

        $role = in_array($request->input('role'), ['Top Note', 'Middle Note', 'Base Note'], true)
            ? $request->input('role')
            : null;

        $notes = Note::withCount('perfumes')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($family, fn ($query) => $query->where('fragrance_family', $family))
            ->when($role, fn ($query) => $query->where('common_role', $role))
            ->orderBy('name')
            ->paginate(36)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'grid' => view('components.notes-grid', ['notes' => $notes])->render(),
                'pagination' => view('components.pagination', [
                    'paginator' => $notes,
                    'route' => 'notes.index',
                ])->render(),
            ]);
        }

        return view('pages.notes', [
            'notes' => $notes,
            'search' => $search,
            'family' => $family,
            'role' => $role,
            'families' => $validFamilies->sort()->values(),
        ]);
    }

    public function show($slug)
    {
        $note = Note::with([
            'perfumes.brand',
            'perfumes.fragranceFamily',
            'characteristics',
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        return view('pages.note-detail', compact('note'));
    }
}