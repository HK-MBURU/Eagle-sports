<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResultEntry;
use Illuminate\Http\Request;

class ResultEntryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        $series = $request->get('series');

        $query = ResultEntry::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('competition', 'like', "%{$search}%")
                  ->orWhere('event_discipline', 'like', "%{$search}%")
                  ->orWhere('athlete_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($series) {
            $query->where('series', $series);
        }

        match ($filter) {
            'published' => $query->where('is_published', true),
            'draft'     => $query->where('is_published', false),
            'featured'  => $query->where('is_featured', true),
            default     => null,
        };

        $entries = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => ResultEntry::count(),
            'published' => ResultEntry::where('is_published', true)->count(),
            'featured'  => ResultEntry::where('is_featured', true)->count(),
            'official'  => ResultEntry::where('result_status', 'official')->count(),
        ];

        $seriesList = ResultEntry::selectRaw('series, count(*) as count')
            ->whereNotNull('series')
            ->groupBy('series')
            ->pluck('count', 'series');

        return view('admin.result-entries.index',
            compact('entries', 'filter', 'search', 'series', 'stats', 'seriesList'));
    }

    public function create()
    {
        return view('admin.result-entries.create', [
            'seriesOptions' => $this->seriesOptions(),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition'      => 'required|string|max:255',
            'event_discipline' => 'required|string|max:255',
            'performance'      => 'required|string|max:100',
            'athlete_name'     => 'nullable|string|max:255',
            'athlete_country'  => 'nullable|string|max:10',
            'series'           => 'nullable|string|max:100',
            'result_status'    => 'required|in:official,pending,unofficial',
            'race_date'        => 'nullable|date',
            'location'         => 'nullable|string|max:255',
            'is_featured'      => 'boolean',
            'is_published'     => 'boolean',
        ]);

        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['created_by']   = auth()->id();

        ResultEntry::create($validated);

        return redirect()->route('admin.result-entries.index')
            ->with('success', 'Result entry added successfully.');
    }

    public function edit(ResultEntry $resultEntry)
    {
        return view('admin.result-entries.edit', [
            'entry'         => $resultEntry,
            'seriesOptions' => $this->seriesOptions(),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function update(Request $request, ResultEntry $resultEntry)
    {
        $validated = $request->validate([
            'competition'      => 'required|string|max:255',
            'event_discipline' => 'required|string|max:255',
            'performance'      => 'required|string|max:100',
            'athlete_name'     => 'nullable|string|max:255',
            'athlete_country'  => 'nullable|string|max:10',
            'series'           => 'nullable|string|max:100',
            'result_status'    => 'required|in:official,pending,unofficial',
            'race_date'        => 'nullable|date',
            'location'         => 'nullable|string|max:255',
            'is_featured'      => 'boolean',
            'is_published'     => 'boolean',
        ]);

        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        $resultEntry->update($validated);

        return redirect()->route('admin.result-entries.index')
            ->with('success', 'Result entry updated successfully.');
    }

    public function destroy(ResultEntry $resultEntry)
    {
        $resultEntry->delete();

        return redirect()->route('admin.result-entries.index')
            ->with('success', 'Result entry deleted.');
    }

    private function seriesOptions(): array
    {
        return [
            'Diamond League',
            'Continental Tour',
            'Marathons & Road Races',
            'World Athletics Championships',
            'African Athletics Championships',
            'National Championships',
            'Cross Country',
            'Other',
        ];
    }

    private function statusOptions(): array
    {
        return [
            'official'   => 'Official',
            'pending'    => 'Pending',
            'unofficial' => 'Unofficial',
        ];
    }
}
