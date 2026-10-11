<?php

namespace App\Http\Controllers;

use App\Models\Climate;
use App\Models\ClimateContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClimateController extends Controller
{
    /**
     * Display a listing of the climate contents.
     */
    public function index()
    {
        $contents = Climate::orderBy('display_order')->paginate(10);
        return view('admin.climate.index', compact('contents'));
    }

    /**
     * Show the form for creating a new climate content.
     */
    public function create()
    {
        return view('admin.climate.create');
    }

    /**
     * Store a newly created climate content in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'stat_1_value' => 'required|string|max:50',
            'stat_1_label' => 'required|string|max:100',
            'stat_2_value' => 'required|string|max:50',
            'stat_2_label' => 'required|string|max:100',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('image');

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('climate', $filename, 'public');
                $data['image_url'] = Storage::url($path);
            }

            // Set defaults if not provided
            $data['cta_text'] = $data['cta_text'] ?? 'Learn More About Our Climate Work';
            $data['cta_url'] = $data['cta_url'] ?? '/impact/climate';
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['display_order'] = $data['display_order'] ?? 0;

            Climate::create($data);

            return redirect()->route('admin.climate.index')
                ->with('success', 'Climate content created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create climate content: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified climate content.
     */
    public function show(Climate $climate)
    {
        return view('admin.climate.show', compact('climate'));
    }

    /**
     * Show the form for editing the specified climate content.
     */
    public function edit(Climate $climate)
    {
        return view('admin.climate.edit', compact('climate'));
    }

    /**
     * Update the specified climate content in storage.
     */
    public function update(Request $request, Climate $climate)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'stat_1_value' => 'required|string|max:50',
            'stat_1_label' => 'required|string|max:100',
            'stat_2_value' => 'required|string|max:50',
            'stat_2_label' => 'required|string|max:100',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('image');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($climate->image_url) {
                    $oldImagePath = str_replace('/storage/', '', $climate->image_url);
                    Storage::disk('public')->delete($oldImagePath);
                }

                $image = $request->file('image');
                $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('climate', $filename, 'public');
                $data['image_url'] = Storage::url($path);
            }

            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['display_order'] = $data['display_order'] ?? $climate->display_order;

            $climate->update($data);

            return redirect()->route('admin.climate.index')
                ->with('success', 'Climate content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update climate content: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified climate content from storage.
     */
    public function destroy(Climate $climate)
    {
        try {
            // Delete associated image if exists
            if ($climate->image_url) {
                $imagePath = str_replace('/storage/', '', $climate->image_url);
                Storage::disk('public')->delete($imagePath);
            }

            $climate->delete();

            return redirect()->route('admin.climate.index')
                ->with('success', 'Climate content deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete climate content: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status of climate content.
     */
    public function toggleActive(Climate $climate)
    {
        try {
            $climate->update(['is_active' => !$climate->is_active]);

            return redirect()->back()
                ->with('success', 'Climate content status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete climate contents.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:climate_contents,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid selection for bulk delete.');
        }

        try {
            $contents = Climate::whereIn('id', $request->ids)->get();

            foreach ($contents as $content) {
                // Delete associated images
                if ($content->image_url) {
                    $imagePath = str_replace('/storage/', '', $content->image_url);
                    Storage::disk('public')->delete($imagePath);
                }
            }

            Climate::whereIn('id', $request->ids)->delete();

            return redirect()->route('admin.climate.index')
                ->with('success', count($request->ids) . ' climate content(s) deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete climate contents: ' . $e->getMessage());
        }
    }

    /**
     * Update display order of climate contents.
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:climate_contents,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order data.',
            ], 422);
        }

        try {
            foreach ($request->orders as $orderData) {
                Climate::where('id', $orderData['id'])
                    ->update(['display_order' => $orderData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Display order updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search climate contents.
     */
    public function search(Request $request)
    {
        $query = Climate::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $contents = $query->orderBy('display_order')->paginate(10);

        return view('admin.climate.index', compact('contents'));
    }

    /**
     * Export climate contents to JSON.
     */
    public function export()
    {
        try {
            $contents = Climate::all();
            $filename = 'climate_contents_' . date('Y-m-d_H-i-s') . '.json';

            return response()->json($contents)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a climate content.
     */
    public function duplicate(Climate $climate)
    {
        try {
            $newContent = $climate->replicate();
            $newContent->title = $climate->title . ' (Copy)';
            $newContent->is_active = false;
            $newContent->display_order = Climate::max('display_order') + 1;
            $newContent->save();

            return redirect()->route('admin.climate.index')
                ->with('success', 'Climate content duplicated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to duplicate climate content: ' . $e->getMessage());
        }
    }
}
