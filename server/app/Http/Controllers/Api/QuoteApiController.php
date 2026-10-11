<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteApiController extends Controller
{
    /**
     * Display a listing of the quotes for API consumption.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            $quotes = Quote::with('addedBy')
                ->select(['id', 'content', 'owner', 'owner_title', 'added_by', 'created_at', 'updated_at'])
                ->latest()
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'quotes' => $quotes->items(),
                    'pagination' => [
                        'current_page' => $quotes->currentPage(),
                        'last_page' => $quotes->lastPage(),
                        'per_page' => $quotes->perPage(),
                        'total' => $quotes->total(),
                        'from' => $quotes->firstItem(),
                        'to' => $quotes->lastItem(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quotes',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display a random quote for API consumption.
     */
    public function random(): JsonResponse
    {
        try {
            $quote = Quote::with('addedBy')
                ->select(['id', 'content', 'owner', 'owner_title', 'added_by', 'created_at'])
                ->inRandomOrder()
                ->first();

            if (!$quote) {
                return response()->json([
                    'success' => false,
                    'message' => 'No quotes found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $quote
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch random quote',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified quote for API consumption.
     */
    public function show($id): JsonResponse
    {
        try {
            $quote = Quote::with('addedBy')
                ->select(['id', 'content', 'owner', 'owner_title', 'added_by', 'created_at', 'updated_at'])
                ->find($id);

            if (!$quote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quote not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $quote
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quote',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created quote via API.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'owner' => 'required|string|max:255',
                'owner_title' => 'nullable|string|max:255',
            ]);

            $validated['added_by'] = auth()->id();

            $quote = Quote::create($validated);
            $quote->load('addedBy');

            return response()->json([
                'success' => true,
                'message' => 'Quote created successfully',
                'data' => $quote
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quote',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified quote via API.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $quote = Quote::find($id);

            if (!$quote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quote not found'
                ], 404);
            }

            $validated = $request->validate([
                'content' => 'sometimes|required|string',
                'owner' => 'sometimes|required|string|max:255',
                'owner_title' => 'nullable|string|max:255',
            ]);

            $quote->update($validated);
            $quote->load('addedBy');

            return response()->json([
                'success' => true,
                'message' => 'Quote updated successfully',
                'data' => $quote
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quote',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified quote via API.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $quote = Quote::find($id);

            if (!$quote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quote not found'
                ], 404);
            }

            $quote->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quote deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quote',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
