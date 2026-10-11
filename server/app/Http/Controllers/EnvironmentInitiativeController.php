<?php

namespace App\Http\Controllers;
use App\Models\EnvironmentalInitiative;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class EnvironmentInitiativeController extends Controller
{
    /**
     * Display a listing of the environmental initiatives.
     */
    public function index()
    {
        $initiatives = EnvironmentalInitiative::orderBy('display_order')->paginate(12);
        return view('admin.environment-initiative.index', compact('initiatives'));
    }

    /**
     * Show the form for creating a new initiative.
     */
    public function create()
    {
        // Available icons for selection
        $availableIcons = [
            'Leaf' => 'Leaf',
            'TreePine' => 'Tree Pine',
            'Droplet' => 'Water Drop',
            'Wind' => 'Wind',
            'Sun' => 'Sun',
            'Mountain' => 'Mountain',
            'Flower' => 'Flower',
            'Sprout' => 'Sprout',
            'Globe' => 'Globe',
            'Heart' => 'Heart',
            'Recycle' => 'Recycle',
            'Bird' => 'Bird',
        ];

        return view('admin.environment-initiative.create', compact('availableIcons'));
    }

    /**
     * Store a newly created initiative in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'stat_value' => 'required|string|max:50',
            'stat_label' => 'required|string|max:100',
            'icon_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['icon_color'] = $data['icon_color'] ?? '#0F3E22';
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['display_order'] = $data['display_order'] ?? 0;

            EnvironmentalInitiative::create($data);

            return redirect()->route('admin.environmentInitiative.index')
                ->with('success', 'Environmental initiative created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create initiative: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified initiative.
     */
    public function show(EnvironmentalInitiative $environmentInitiative)
    {
        return view('admin.environment-initiative.show', compact('environmentInitiative'));
    }

    /**
     * Show the form for editing the specified initiative.
     */
    public function edit(EnvironmentalInitiative $environmentInitiative)
    {
        // Available icons for selection
        $availableIcons = [
            'Leaf' => 'Leaf',
            'TreePine' => 'Tree Pine',
            'Droplet' => 'Water Drop',
            'Wind' => 'Wind',
            'Sun' => 'Sun',
            'Mountain' => 'Mountain',
            'Flower' => 'Flower',
            'Sprout' => 'Sprout',
            'Globe' => 'Globe',
            'Heart' => 'Heart',
            'Recycle' => 'Recycle',
            'Bird' => 'Bird',
        ];

        return view('admin.environment-initiative.edit', compact('environmentInitiative', 'availableIcons'));
    }

    /**
     * Update the specified initiative in storage.
     */
    public function update(Request $request, EnvironmentalInitiative $environmentInitiative)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'stat_value' => 'required|string|max:50',
            'stat_label' => 'required|string|max:100',
            'icon_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['display_order'] = $data['display_order'] ?? $environmentInitiative->display_order;

            $environmentInitiative->update($data);

            return redirect()->route('admin.environmentInitiative.index')
                ->with('success', 'Environmental initiative updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update initiative: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified initiative from storage.
     */
    public function destroy(EnvironmentalInitiative $environmentInitiative)
    {
        try {
            $environmentInitiative->delete();

            return redirect()->route('admin.environmentInitiative.index')
                ->with('success', 'Environmental initiative deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete initiative: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status of initiative.
     */
    public function toggleActive(EnvironmentalInitiative $environmentInitiative)
    {
        try {
            $environmentInitiative->update(['is_active' => !$environmentInitiative->is_active]);

            return redirect()->back()
                ->with('success', 'Initiative status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete initiatives.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:environmental_initiatives,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid selection for bulk delete.');
        }

        try {
            EnvironmentalInitiative::whereIn('id', $request->ids)->delete();

            return redirect()->route('admin.environmentInitiative.index')
                ->with('success', count($request->ids) . ' initiative(s) deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete initiatives: ' . $e->getMessage());
        }
    }

    /**
     * Update display order of initiatives.
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:environmental_initiatives,id',
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
                EnvironmentalInitiative::where('id', $orderData['id'])
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
     * Search initiatives.
     */
    public function search(Request $request)
    {
        $query = EnvironmentalInitiative::query();

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

        $initiatives = $query->orderBy('display_order')->paginate(12);

        return view('admin.environment-initiative.index', compact('initiatives'));
    }

    /**
     * Export initiatives to JSON.
     */
    public function export()
    {
        try {
            $initiatives = EnvironmentalInitiative::all();
            $filename = 'environmental_initiatives_' . date('Y-m-d_H-i-s') . '.json';

            return response()->json($initiatives)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate an initiative.
     */
    public function duplicate(EnvironmentalInitiative $environmentInitiative)
    {
        try {
            $newInitiative = $environmentInitiative->replicate();
            $newInitiative->title = $environmentInitiative->title . ' (Copy)';
            $newInitiative->is_active = false;
            $newInitiative->display_order = EnvironmentalInitiative::max('display_order') + 1;
            $newInitiative->save();

            return redirect()->route('admin.environmentInitiative.index')
                ->with('success', 'Initiative duplicated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to duplicate initiative: ' . $e->getMessage());
        }
    }
}
