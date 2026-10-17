<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AthleteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\RaceEventController;
use App\Http\Controllers\Admin\ResultEntryController;
use App\Http\Controllers\Admin\ResultsController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClimateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MetaTagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\StoryViewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WomenRunningController;
use App\Models\Race;
use App\Models\RaceEvent;
use App\Models\Story;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/stories/{slug}', [MetaTagController::class, 'storyMetaTags'])
    ->name('story.meta')
    // Keep the crawler route stateless to avoid session/CSRF issues for bots
    ->withoutMiddleware([
        StartSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
    ]);

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard

    // Athletes Management
    Route::resource('athletes', AthleteController::class);

    // Events Management
    Route::resource('events', RaceEventController::class);

    // Videos Management
    Route::resource('videos', VideoController::class);
    Route::get('videos/category/{category}', [VideoController::class, 'byCategory'])
        ->name('videos.byCategory');
    Route::get('videos/featured', [VideoController::class, 'featured'])
        ->name('videos.featured');
    Route::post('videos/search', [VideoController::class, 'search'])
        ->name('videos.search');
    Route::patch('videos/{video}/toggle-featured', [VideoController::class, 'toggleFeatured'])
        ->name('videos.toggleFeatured');

    // Women Running Management
    Route::resource('women-running', WomenRunningController::class);

    Route::patch('women-running/{womenRunning}/toggle-status', [WomenRunningController::class, 'toggleStatus'])
        ->name('women-running.toggle-status');
    Route::patch('women-running/{womenRunning}/toggle-featured', [WomenRunningController::class, 'toggleFeatured'])
        ->name('women-running.toggle-featured');

    // Climate Management
    Route::resource('climate', ClimateController::class);
    Route::post('climate/{climate}/toggle-active', [ClimateController::class, 'toggleActive'])
        ->name('climate.toggleActive');
    Route::post('climate/bulk-delete', [ClimateController::class, 'bulkDelete'])
        ->name('climate.bulkDelete');
    Route::post('climate/update-order', [ClimateController::class, 'updateOrder'])
        ->name('climate.updateOrder');
    Route::get('climate-search', [ClimateController::class, 'search'])
        ->name('climate.search');
    Route::get('climate/export/json', [ClimateController::class, 'export'])
        ->name('climate.export');
    Route::post('climate/{climate}/duplicate', [ClimateController::class, 'duplicate'])
        ->name('climate.duplicate');

    // Races Management
    Route::resource('races', RaceController::class);

    // Result Entries (feeds the frontend Results Centre widget)
    Route::resource('result-entries', ResultEntryController::class);

    // Race Results (file uploads)
    Route::resource('results', ResultsController::class);

    // Additional routes for race results
    Route::get('results/{result}/download', [ResultsController::class, 'download'])
        ->name('results.download');

    Route::patch('results/{result}/toggle-featured', [ResultsController::class, 'toggleFeatured'])
        ->name('results.toggle-featured');

    Route::patch('results/{result}/toggle-published', [ResultsController::class, 'togglePublished'])
        ->name('results.toggle-published');

    Route::post('results/bulk-delete', [ResultsController::class, 'bulkDelete'])
        ->name('results.bulk-delete');

    Route::get('results-search', [ResultsController::class, 'search'])
        ->name('results.search');

    Route::get('results-statistics', [ResultsController::class, 'statistics'])
        ->name('results.statistics');

    // Stories Management
    Route::resource('stories', StoryController::class);

    // Image upload route for TinyMCE
    Route::post('stories/upload-image', [StoryController::class, 'uploadImage'])
        ->name('stories.upload-image');

    // Quotes Management
    Route::resource('quotes', QuoteController::class);

    // Partners Management
    Route::resource('partners', PartnerController::class);

    // Users Management
    Route::resource('users', UserController::class);

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Notifications
    Route::get('/notifications/poll',         [NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/{id}/read',   [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all',    [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}',      [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
