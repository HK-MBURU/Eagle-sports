<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 24), 100);
        $page    = (int) $request->get('page', 1);

        $query = Photo::published()->orderBy('sort_order')->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('source_type')) {
            $query->where('source_type', $request->source_type);
        }
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('caption', 'like', '%' . $request->search . '%');
            });
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $categories = Photo::getCategories();

        $photos = $paginator->getCollection()->map(function (Photo $p) use ($categories) {
            return [
                'id'            => $p->id,
                'title'         => $p->title,
                'caption'       => $p->caption,
                'alt_text'      => $p->alt_text ?? $p->title,
                'image_url'     => $p->display_url,
                'category'      => $p->category,
                'category_name' => $p->category_name,
                'source_type'   => $p->source_type,
                'is_featured'   => $p->is_featured,
                'created_at'    => $p->created_at?->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'photos'     => $photos,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
                'categories' => $categories,
            ],
        ]);
    }

    public function featured()
    {
        $photos = Photo::published()->featured()
            ->orderBy('sort_order')->orderByDesc('created_at')
            ->take(12)->get()
            ->map(fn(Photo $p) => [
                'id'          => $p->id,
                'title'       => $p->title,
                'caption'     => $p->caption,
                'alt_text'    => $p->alt_text ?? $p->title,
                'image_url'   => $p->display_url,
                'category'    => $p->category,
                'category_name' => $p->category_name,
                'is_featured' => true,
            ]);

        return response()->json(['success' => true, 'data' => $photos]);
    }
}
