<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResultsController extends Controller
{
    /**
     * Display a listing of race results
     */
    public function index()
    {
        $results = Result::with('uploader')
            ->latest()
            ->paginate(15);

        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new race result
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.results.create', compact('categories'));
    }

    /**
     * Store a newly created race result
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt', // Max 10MB
            'description' => 'nullable|string',
            'race_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Generate unique filename
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

                // Store file in storage/app/public/race-results
                $filePath = $file->storeAs('race-results', $filename, 'public');

                // Create race result record
                $Result = Result::create([
                    'title' => $validated['title'],
                    'category' => $validated['category'],
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                    'description' => $validated['description'] ?? null,
                    'race_date' => $validated['race_date'] ?? null,
                    'location' => $validated['location'] ?? null,
                    'is_featured' => $request->has('is_featured'),
                    'is_published' => $request->has('is_published') ? true : false,
                ]);

                return redirect()
                    ->route('admin.results.index')
                    ->with('success', 'Race result created successfully!');
            }

            return back()
                ->withInput()
                ->with('error', 'Please select a file to upload.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create race result: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified race result
     */
    public function show(Result $result)
    {
        $result->load('uploader');

        // Increment views count
        $result->incrementViews();

        return view('admin.results.show', compact('result'));
    }

    /**
     * Show the form for editing the specified race result
     */
    public function edit(Result $result)
    {
        $categories = $this->getCategories();
        return view('admin.results.edit', compact('result', 'categories'));
    }

    /**
     * Update the specified race result
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt',
            'description' => 'nullable|string',
            'race_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        try {
            // Handle file upload if new file is provided
            if ($request->hasFile('file')) {
                // Delete old file
                if ($result->file_path && Storage::disk('public')->exists($result->file_path)) {
                    Storage::disk('public')->delete($result->file_path);
                }

                $file = $request->file('file');

                // Generate unique filename
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

                // Store new file
                $filePath = $file->storeAs('race-results', $filename, 'public');

                // Update file information
                $validated['file_path'] = $filePath;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_type'] = $file->getClientOriginalExtension();
                $validated['file_size'] = $file->getSize();
            }

            // Update other fields
            $result->update([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'file_path' => $validated['file_path'] ?? $result->file_path,
                'file_name' => $validated['file_name'] ?? $result->file_name,
                'file_type' => $validated['file_type'] ?? $result->file_type,
                'file_size' => $validated['file_size'] ?? $result->file_size,
                'description' => $validated['description'] ?? null,
                'race_date' => $validated['race_date'] ?? null,
                'location' => $validated['location'] ?? null,
                'is_featured' => $request->has('is_featured'),
                'is_published' => $request->has('is_published'),
            ]);

            return redirect()
                ->route('admin.results.index')
                ->with('success', 'Race result updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update race result: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified race result
     */
    public function destroy(Result $result)
    {
        try {
            // Delete file from storage
            if ($result->file_path && Storage::disk('public')->exists($result->file_path)) {
                Storage::disk('public')->delete($result->file_path);
            }

            // Delete record
            $result->delete();

            return redirect()
                ->route('admin.results.index')
                ->with('success', 'Race result deleted successfully!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete race result: ' . $e->getMessage());
        }
    }

    /**
     * Download race result file
     */
    public function download(Result $result)
    {
        try {
            // Increment downloads count
            $result->incrementDownloads();

            // Build full path
            $filePath = storage_path('app/public/' . $result->file_path);

            // Check if file exists
            if (file_exists($filePath)) {
                return response()->download($filePath, $result->file_name);
            }

            return back()->with('error', 'File not found.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to download file: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Result $result)
    {
        try {
            $result->update([
                'is_featured' => !$result->is_featured
            ]);

            return back()->with('success', 'Featured status updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update featured status: ' . $e->getMessage());
        }
    }

    /**
     * Toggle published status
     */
    public function togglePublished(Result $result)
    {
        try {
            $result->update([
                'is_published' => !$result->is_published
            ]);

            return back()->with('success', 'Published status updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update published status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete race results
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:race_results,id'
        ]);

        try {
            $results = Result::whereIn('id', $request->ids)->get();

            foreach ($results as $result) {
                // Delete file from storage
                if ($result->file_path && Storage::disk('public')->exists($result->file_path)) {
                    Storage::disk('public')->delete($result->file_path);
                }

                $result->delete();
            }

            return back()->with('success', count($request->ids) . ' race results deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete race results: ' . $e->getMessage());
        }
    }

    /**
     * Search race results
     */
    public function search(Request $request)
    {
        $query = Result::with('uploader');

        // Search by title or category
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('race_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('race_date', '<=', $request->date_to);
        }

        $results = $query->latest()->paginate(15);

        return view('admin.results.index', compact('results'));
    }

    /**
     * Get statistics for race results
     */
    public function statistics()
    {
        $stats = [
            'total' => Result::count(),
            'published' => Result::published()->count(),
            'featured' => Result::featured()->count(),
            'total_views' => Result::sum('views_count'),
            'total_downloads' => Result::sum('downloads_count'),
            'by_category' => Result::selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->get(),
            'recent' => Result::with('uploader')
                ->latest()
                ->limit(5)
                ->get(),
        ];

        return view('admin.results.statistics', compact('stats'));
    }

    /**
     * Get race categories
     */
    private function getCategories()
    {
        return [
            'Marathon (42.195 km)',
            'Half Marathon (21.097 km)',
            '10K Run',
            '5K Run',
            'Ultra Marathon',
            'Trail Run',
            'Cross Country',
            'Track & Field',
            'Relay Race',
            'Fun Run',
            'Other',
        ];
    }
}
