<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $type = $request->input('type');

        $brands = Brand::withCount('perfumes')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when(in_array($type, ['designer', 'niche'], true), fn ($query) => $query->where('type', $type))
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'grid' => view('components.brands-grid', ['brands' => $brands])->render(),
            ]);
        }

        return view('pages.brands', [
            'brands' => $brands,
            'search' => $search,
            'type' => $type,
            'designerCount' => Brand::where('type', 'designer')->count(),
            'nicheCount' => Brand::where('type', 'niche')->count(),
        ]);
    }

    public function show($slug)
    {
        $brand = Brand::with('milestones')
            ->where('slug', $slug)
            ->firstOrFail();

        $perfumes = $brand->perfumes()
            ->with(['fragranceFamily', 'seasons'])
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        return view('pages.brand-detail', compact('brand', 'perfumes'));
    }
}
