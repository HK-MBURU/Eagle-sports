<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\StoryLikeController;
use App\Http\Controllers\Api\CommentController; // Add this import
use App\Models\StoryLike;
use App\Http\Controllers\Api\AthleteController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ClimateController;
use App\Http\Controllers\Api\PartnerApiController;
use App\Http\Controllers\Api\QuoteApiController;
use App\Http\Controllers\Api\RaceEventApiController;
use App\Http\Controllers\Api\RaceResultsApiController;
use App\Http\Controllers\Api\VideoApiController;
use App\Http\Controllers\Api\WomenRunningApiController;
use App\Http\Controllers\Api\ResultEntryApiController;
use App\Http\Controllers\EnvironmentInitiativeController;
use App\Models\Story;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Test route to generate token (REMOVE IN PRODUCTION!)
Route::post('/auth/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]
    ]);
});

Route::post('/auth/register', [RegisterController::class, 'store']);
Route::post('/auth/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Logged out successfully'
    ]);
})->middleware('auth:sanctum');

// Public Stories API Routes
Route::prefix('stories')->group(function () {
    Route::get('/', [StoryController::class, 'index']);
    Route::get('/featured', [StoryController::class, 'featured']);
    Route::get('/latest', [StoryController::class, 'latest']);
    Route::get('/homepage-featured', [StoryController::class, 'homepageFeatured']);
    Route::get('/categories', [StoryController::class, 'categories']);
    Route::get('/category/{category}', [StoryController::class, 'byCategory']);
    Route::get('/{story}/related', [StoryController::class, 'related']);

    // ===== ADD THESE COMMENT ROUTES =====
    // Get all comments for a story (public)
    Route::get('/{story}/comments', [CommentController::class, 'index']);

    // Get comment statistics (public)
    Route::get('/{story}/comments/stats', [CommentController::class, 'stats']);
    // ===== END COMMENT ROUTES =====

    Route::get('/{identifier}', [StoryController::class, 'show']);

    // Check if story is liked (public)
    Route::get('/{story}/is-liked', [StoryLikeController::class, 'checkLiked']);
});

// ===== ADD PROTECTED COMMENT ROUTES HERE (AFTER STORIES GROUP) =====
// Comment routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Create a new comment or reply
    Route::post('/stories/{story}/comments', [CommentController::class, 'store']);

    // Update a comment
    Route::patch('/comments/{comment}', [CommentController::class, 'update']);

    // Delete a comment
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Toggle like on a comment
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike']);

    // Report a comment
    Route::post('/comments/{comment}/report', [CommentController::class, 'report']);
});
// ===== END PROTECTED COMMENT ROUTES =====

Route::prefix('athletes')->group(function () {
    Route::get('/', [AthleteController::class, 'index']);
    Route::get('/featured', [AthleteController::class, 'featured']);
    Route::get('/latest', [AthleteController::class, 'latest']);
    Route::get('/stats', [AthleteController::class, 'stats']);
    Route::get('/{id}', [AthleteController::class, 'show']);
});

// Protected Like Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Toggle like
    Route::post('/stories/{story}/like', [StoryLikeController::class, 'toggle']);

    // Get user's liked stories
    Route::get('/user/liked-stories', [StoryLikeController::class, 'userLikes']);
});

Route::prefix('women-running')->group(function () {
    // Get all entries with filters
    Route::get('/', [WomenRunningApiController::class, 'index']);

    // Get single entry
    Route::get('/{id}', [WomenRunningApiController::class, 'show']);

    // Get featured entries
    Route::get('/filter/featured', [WomenRunningApiController::class, 'featured']);

    // Get entries by category
    Route::get('/category/{category}', [WomenRunningApiController::class, 'byCategory']);

    // Get statistics
    Route::get('/stats/all', [WomenRunningApiController::class, 'statistics']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->prefix('women-running')->group(function () {
    // Create new entry
    Route::post('/', [WomenRunningApiController::class, 'store']);

    // Update entry
    Route::put('/{id}', [WomenRunningApiController::class, 'update']);
    Route::patch('/{id}', [WomenRunningApiController::class, 'update']);

    // Delete entry
    Route::delete('/{id}', [WomenRunningApiController::class, 'destroy']);

    // Toggle status
    Route::patch('/{id}/toggle-status', [WomenRunningApiController::class, 'toggleStatus']);

    // Toggle featured
    Route::patch('/{id}/toggle-featured', [WomenRunningApiController::class, 'toggleFeatured']);
});

Route::prefix('climate')->group(function () {
    Route::get('/', [ClimateController::class, 'getClimateContent']);
    Route::get('/content', [ClimateController::class, 'getMainContent']);
    Route::get('/initiatives', [ClimateController::class, 'getInitiatives']);
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Environmental Initiative CRUD routes
    Route::resource('environmentInitiative', EnvironmentInitiativeController::class)->names([
        'index' => 'admin.environmentInitiative.index',
        'create' => 'admin.environmentInitiative.create',
        'store' => 'admin.environmentInitiative.store',
        'show' => 'admin.environmentInitiative.show',
        'edit' => 'admin.environmentInitiative.edit',
        'update' => 'admin.environmentInitiative.update',
        'destroy' => 'admin.environmentInitiative.destroy',
    ]);

    // Additional routes
    Route::post('environmentInitiative/{environmentInitiative}/toggle-active', [EnvironmentInitiativeController::class, 'toggleActive'])
        ->name('admin.environmentInitiative.toggleActive');
    Route::post('environmentInitiative/bulk-delete', [EnvironmentInitiativeController::class, 'bulkDelete'])
        ->name('admin.environmentInitiative.bulkDelete');
    Route::post('environmentInitiative/update-order', [EnvironmentInitiativeController::class, 'updateOrder'])
        ->name('admin.environmentInitiative.updateOrder');
    Route::get('environmentInitiative-search', [EnvironmentInitiativeController::class, 'search'])
        ->name('admin.environmentInitiative.search');
    Route::get('environmentInitiative/export/json', [EnvironmentInitiativeController::class, 'export'])
        ->name('admin.environmentInitiative.export');
    Route::post('environmentInitiative/{environmentInitiative}/duplicate', [EnvironmentInitiativeController::class, 'duplicate'])
        ->name('admin.environmentInitiative.duplicate');
});

// Add this NEW route for meta tags (for bots only)
Route::get('/stories/{slug}/meta', function (Request $request, $slug) {
    $story = Story::where('slug', $slug)
        ->where('status', 'published')
        ->first();

    if (!$story) {
        abort(404);
    }

    // Prepare meta data optimized for social previews
    $title = $story->meta_title ?? $story->title;
    $description = $story->meta_description ?? strip_tags($story->excerpt);
    $image = $story->og_image_url ?? $story->image_url;
    $url = config('app.frontend_url') . "/stories/{$story->slug}";
    $hashtags = $story->formatted_hashtags ?? '';
    $siteName = "Beyond the Finish Line";

    // Serve static OG page to crawlers, redirect humans to the story page.
    $userAgent = strtolower($request->userAgent() ?? '');
    $crawlerSignatures = [
        'facebookexternalhit',
        'facebot',
        'meta-externalagent',
        'meta-externalfetcher',
        'twitterbot',
        'whatsapp',
        'linkedinbot',
        'slackbot',
        'telegrambot',
        'discordbot',
        'googlebot',
        'bingbot',
    ];

    $isCrawler = collect($crawlerSignatures)->contains(
        fn($signature) => str_contains($userAgent, $signature)
    );

    if (!$isCrawler) {
        return redirect()->away($url, 302);
    }

    return view('story-preview', compact('story', 'title', 'description', 'image', 'url', 'hashtags', 'siteName'));
})->name('api.stories.meta');

// Public Race Results API Routes
Route::prefix('race-results')->group(function () {
    // Get all results (with filters, search, pagination)
    Route::get('/', [RaceResultsApiController::class, 'index']);

    // Get featured results
    Route::get('/featured', [RaceResultsApiController::class, 'featured']);

    // Get latest results
    Route::get('/latest', [RaceResultsApiController::class, 'latest']);

    // Get available categories
    Route::get('/categories', [RaceResultsApiController::class, 'categories']);

    // Get results by category
    Route::get('/category/{category}', [RaceResultsApiController::class, 'byCategory']);

    // Get statistics
    Route::get('/stats', [RaceResultsApiController::class, 'statistics']);

    // Get single result
    Route::get('/{id}', [RaceResultsApiController::class, 'show']);

    // Get download link
    Route::get('/{id}/download', [RaceResultsApiController::class, 'download']);
});

Route::apiResource('quotes', QuoteApiController::class);
Route::get('/quotes/random/one', [QuoteApiController::class, 'random']);

Route::get('/race-events/calendar/featured', [RaceEventApiController::class, 'calendarFeatured']);
Route::get('/race-events/flagship/current', [RaceEventApiController::class, 'flagship']);
Route::get('/race-events/featured/all', [RaceEventApiController::class, 'featured']);
Route::get('/race-events/upcoming/list', [RaceEventApiController::class, 'upcoming']);
Route::get('/race-events/date-range/filter', [RaceEventApiController::class, 'byDateRange']);
Route::apiResource('race-events', RaceEventApiController::class);

Route::apiResource('videos', VideoApiController::class);
Route::get('/videos/featured/all', [VideoApiController::class, 'featured']);
Route::get('/videos/category/{category}', [VideoApiController::class, 'byCategory']);
Route::get('/videos/categories/list', [VideoApiController::class, 'categories']);
Route::post('/videos/{id}/toggle-featured', [VideoApiController::class, 'toggleFeatured']);
Route::post('/videos/search/query', [VideoApiController::class, 'search']);

Route::apiResource('partners', PartnerApiController::class);
Route::get('/partners/featured/all', [PartnerApiController::class, 'featured']);
Route::get('/partners/alphabetical/list', [PartnerApiController::class, 'alphabetical']);
Route::post('/partners/{id}/toggle-featured', [PartnerApiController::class, 'toggleFeatured']);
Route::post('/partners/search/query', [PartnerApiController::class, 'search']);

Route::get('/stories/{slug}/share-data', [StoryController::class, 'shareData']);

// Result Entries — individual performance data
Route::get('/result-entries/latest', [ResultEntryApiController::class, 'latest']);
Route::get('/result-entries/series/{series}', [ResultEntryApiController::class, 'bySeries']);
Route::apiResource('result-entries', ResultEntryApiController::class)->only(['index', 'show']);
