<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Climate;
use App\Models\ClimateContent;
use App\Models\Environment_initiative;
use App\Models\EnvironmentalInitiative;
use Illuminate\Http\Request;

class ClimateController extends Controller
{
    /**
     * Get climate impact content
     */
    public function getClimateContent()
    {
        try {
            $content = Climate::getFeatured();
            $initiatives = Environment_initiative::getAll();

            return response()->json([
                'success' => true,
                'data' => [
                    'content' => $content,
                    'initiatives' => $initiatives,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch climate content',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get only main climate content
     */
    public function getMainContent()
    {
        try {
            $content = Climate::getFeatured();

            return response()->json([
                'success' => true,
                'data' => $content,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch content',
            ], 500);
        }
    }

    /**
     * Get environmental initiatives
     */
    public function getInitiatives()
    {
        try {
            $initiatives = Environment_initiative::getAll();

            return response()->json([
                'success' => true,
                'data' => $initiatives,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch initiatives',
            ], 500);
        }
    }
}
