<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FrontendController extends Controller
{
    /**
     * Serve the React app with dynamic meta tags for stories
     */
    public function story($slug)
    {
        // Get the story from database
        $story = Story::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$story) {
            // If story not found, serve default React app
            return $this->serveReactApp();
        }

        // Get the React build HTML
        $html = $this->getReactAppHTML();

        // Replace meta tags with story data
        $html = $this->injectStoryMetaTags($html, $story);

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Serve home page with default meta tags
     */
    public function home()
    {
        return $this->serveReactApp();
    }

    /**
     * Get React app HTML content
     */
    private function getReactAppHTML()
    {
        // Path to your React build index.html
        $indexPath = public_path('index.html');

        if (!File::exists($indexPath)) {
            abort(404, 'React app not built. Run: npm run build');
        }

        return File::get($indexPath);
    }

    /**
     * Inject story meta tags into HTML
     */
    private function injectStoryMetaTags($html, $story)
    {
        $siteUrl = config('app.url');
        $storyUrl = "{$siteUrl}/stories/{$story->slug}";

        // Story data
        $title = htmlspecialchars($story->title, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($story->excerpt, ENT_QUOTES, 'UTF-8');
        $imageUrl = htmlspecialchars($story->og_image_url ?? $story->image_url, ENT_QUOTES, 'UTF-8');
        $hashtags = $story->formatted_hashtags ? htmlspecialchars($story->formatted_hashtags, ENT_QUOTES, 'UTF-8') : '';

        // Full description with hashtags
        $fullDescription = $description . ($hashtags ? "\n\n" . $hashtags : '');

        // Replace title
        $html = preg_replace(
            '/<title>.*?<\/title>/',
            "<title>{$title}</title>",
            $html
        );

        // Replace or add meta description
        $html = preg_replace(
            '/<meta name="description" content=".*?"/',
            '<meta name="description" content="' . $fullDescription . '"',
            $html
        );

        // Replace OG tags
        $html = preg_replace(
            '/<meta property="og:title" content=".*?"/',
            '<meta property="og:title" content="' . $title . '"',
            $html
        );

        $html = preg_replace(
            '/<meta property="og:description" content=".*?"/',
            '<meta property="og:description" content="' . $fullDescription . '"',
            $html
        );

        $html = preg_replace(
            '/<meta property="og:image" content=".*?"/',
            '<meta property="og:image" content="' . $imageUrl . '"',
            $html
        );

        $html = preg_replace(
            '/<meta property="og:url" content=".*?"/',
            '<meta property="og:url" content="' . $storyUrl . '"',
            $html
        );

        // Replace Twitter Card tags
        $html = preg_replace(
            '/<meta name="twitter:title" content=".*?"/',
            '<meta name="twitter:title" content="' . $title . '"',
            $html
        );

        $html = preg_replace(
            '/<meta name="twitter:description" content=".*?"/',
            '<meta name="twitter:description" content="' . $fullDescription . '"',
            $html
        );

        $html = preg_replace(
            '/<meta name="twitter:image" content=".*?"/',
            '<meta name="twitter:image" content="' . $imageUrl . '"',
            $html
        );

        return $html;
    }

    /**
     * Serve default React app
     */
    private function serveReactApp()
    {
        return File::get(public_path('index.html'));
    }
}
