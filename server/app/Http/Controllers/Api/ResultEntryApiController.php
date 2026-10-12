<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResultEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultEntryApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = ResultEntry::published()
                ->orderBy('race_date', 'desc');

            if ($series = $request->get('series')) {
                $query->where('series', $series);
            }

            $entries = $query->paginate($request->get('per_page', 20));

            return response()->json(['success' => true, 'data' => $entries]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch entries'], 500);
        }
    }

    public function latest(Request $request): JsonResponse
    {
        try {
            $limit   = (int) $request->get('limit', 8);
            $entries = ResultEntry::published()
                ->orderBy('race_date', 'desc')
                ->limit($limit)
                ->get()
                ->map(fn($e) => $this->transform($e));

            return response()->json(['success' => true, 'data' => $entries]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch latest entries'], 500);
        }
    }

    public function bySeries(Request $request, string $series): JsonResponse
    {
        try {
            $entries = ResultEntry::published()
                ->where('series', $series)
                ->orderBy('race_date', 'desc')
                ->limit(20)
                ->get()
                ->map(fn($e) => $this->transform($e));

            return response()->json(['success' => true, 'data' => $entries]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch entries'], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $entry = ResultEntry::published()->find($id);
        if (!$entry) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $this->transform($entry)]);
    }

    private function transform(ResultEntry $e): array
    {
        return [
            'id'               => $e->id,
            'competition'      => $e->competition,
            'event_discipline' => $e->event_discipline,
            'performance'      => $e->performance,
            'athlete_name'     => $e->athlete_name,
            'athlete_country'  => $e->athlete_country,
            'series'           => $e->series,
            'result_status'    => $e->result_status,
            'race_date'        => $e->race_date?->format('Y-m-d'),
            'race_date_fmt'    => $e->race_date?->format('d M Y'),
            'location'         => $e->location,
            'is_featured'      => $e->is_featured,
        ];
    }
}
