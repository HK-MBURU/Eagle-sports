<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MetaTagController extends Controller
{
    /**
     * Generate meta tags for a story
     */
    public function storyMetaTags(string $slug, Request $request)
    {
        try {
            $story = Story::published()
                ->where('slug', $slug)
                ->first();

            if (!$story) {
                abort(404);
            }

            // Check if it's a bot/crawler
            $userAgent = $request->header('User-Agent', '');
            $isCrawler = $this->isCrawler($userAgent);

            if ($isCrawler) {
                // Serve static HTML with meta tags for crawlers
                return $this->generateStoryMetaTags($story);
            }

            // For regular users, redirect to the actual frontend URL
            $frontendUrl = $this->getFrontendStoryUrl($slug);
            return redirect($frontendUrl, 301); // Permanent redirect
        } catch (\Throwable $e) {
            // Never return a 500 to crawlers; log and fall back to a safe page
            \Log::error('Story meta tags failed', [
                'slug' => $slug,
                'message' => $e->getMessage(),
            ]);

            return $this->fallbackMetaTags($slug);
        }
    }

    /**
     * Check if the request is from a crawler/bot
     */
    private function isCrawler($userAgent)
    {
        $crawlers = [
            'facebookexternalhit',
            'Facebot',
            'meta-externalagent',
            'meta-externalfetcher',
            'Twitterbot',
            'LinkedInBot',
            'Slackbot',
            'TelegramBot',
            'WhatsApp',         // Keep
            'WhatsApp/2',       // Add WhatsApp real UAs
            'instagram',        // Instagram preview bot
            'Applebot',
            'Googlebot',
            'bingbot',
            'DuckDuckBot',
            'Baiduspider',
            'YandexBot',
            'Sogou',
            'Exabot',
            'MJ12bot',
        ];


        foreach ($crawlers as $crawler) {
            if (stripos($userAgent, $crawler) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate meta tags for a specific story
     */
    private function generateStoryMetaTags(Story $story)
    {
        $title = $story->meta_title ?? $story->title;
        $description = $story->meta_description ?? strip_tags($story->excerpt);
        $image = $this->absoluteUrl($story->og_image_url ?? $story->image_url);
        $url = $this->getFrontendStoryUrl($story->slug);
        $fbAppId = env('FACEBOOK_APP_ID', '');

        $this->applyStorySeoTags($story, $title, $description, $image, $url, $fbAppId);

        $seoTags = SEOTools::generate();

        return response()->view('meta-tags.story', compact(
            'title',
            'description',
            'image',
            'url',
            'story',
            'fbAppId',
            'seoTags'
        ))->header('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Get the frontend URL for a story
     */
    private function getFrontendStoryUrl(string $slug): string
    {
        $frontendDomain = config('app.frontend_url', env('FRONTEND_DOMAIN', 'https://beyondfinishline.com'));
        return rtrim($frontendDomain, '/') . "/stories/{$slug}";
    }

    /**
     * Fallback meta tags when something goes wrong.
     * Returns a simple but valid OG response instead of a 500.
     */
    private function fallbackMetaTags(string $slug)
    {
        $url = $this->getFrontendStoryUrl($slug);
        $image = $this->absoluteUrl(env('DEFAULT_OG_IMAGE', url('/images/default-story.jpg')));

        return response()->view('meta-tags.fallback', [
            'title' => 'Beyond the Finish Line',
            'description' => 'Read inspiring athlete stories on Beyond the Finish Line.',
            'image' => $image,
            'url' => $url,
        ])->header('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Ensure we return an absolute, https-capable URL for crawlers.
     */
    private function absoluteUrl(?string $maybeUrl): string
    {
        if (empty($maybeUrl)) {
            return $this->ensureSecureUrl(url('/images/default-story.jpg'));
        }

        if (preg_match('/^https?:\\/\\//i', $maybeUrl)) {
            return $this->ensureSecureUrl($maybeUrl);
        }

        return $this->ensureSecureUrl(url($maybeUrl));
    }

    private function applyStorySeoTags(
        Story $story,
        string $title,
        string $description,
        string $image,
        string $url,
        string $fbAppId
    ): void {
        $keywords = $story->meta_keywords
            ? array_values(array_filter(array_map('trim', explode(',', $story->meta_keywords))))
            : [];

        $imageType = $this->detectImageType($image);

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($url);

        if (!empty($keywords)) {
            SEOTools::metatags()->addKeyword($keywords);
        }

        if (!empty($fbAppId)) {
            SEOMeta::addMeta('fb:app_id', $fbAppId, 'property');
        }

        $openGraph = SEOTools::opengraph();
        $openGraph->setTitle($title);
        $openGraph->setDescription($description);
        $openGraph->setUrl($url);
        $openGraph->setType('article');
        $openGraph->addProperty('site_name', config('app.name', 'Beyond the Finish Line'));
        $openGraph->addProperty('locale', 'en_US');
        SEOMeta::addMeta('article:published_time', $story->published_date->toIso8601String(), 'property');
        SEOMeta::addMeta('article:modified_time', $story->updated_at->toIso8601String(), 'property');
        SEOMeta::addMeta('article:author', $story->author_name, 'property');
        SEOMeta::addMeta('article:section', $story->category, 'property');

        foreach ($keywords as $keyword) {
            SEOMeta::addMeta('article:tag', $keyword, 'property');
        }

        $openGraph->addImage([
            'url' => $image,
            'secure_url' => $image,
            'type' => $imageType,
            'width' => 1200,
            'height' => 630,
            'alt' => $title,
        ]);

        $twitter = SEOTools::twitter();
        $twitter->setTitle($title);
        $twitter->setDescription($description);
        $twitter->setUrl($url);
        $twitter->setType('summary_large_image');
        $twitter->setImage($image);

        $jsonLd = SEOTools::jsonLd();
        $jsonLd->setTitle($title);
        $jsonLd->setDescription($description);
        $jsonLd->setType('Article');
        $jsonLd->setUrl($url);
        $jsonLd->addImage($image);
        $jsonLd->addValue('datePublished', $story->published_date->toIso8601String());
        $jsonLd->addValue('dateModified', $story->updated_at->toIso8601String());
        $jsonLd->addValue('author', [
            '@type' => 'Person',
            'name' => $story->author_name,
        ]);
        $jsonLd->addValue('publisher', [
            '@type' => 'Organization',
            'name' => config('app.name', 'Beyond the Finish Line'),
        ]);
    }

    private function detectImageType(string $imageUrl): string
    {
        $extension = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        return match ($extension) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
    }

    private function ensureSecureUrl(string $url): string
    {
        if (request()->isSecure() && Str::startsWith($url, 'http://')) {
            return Str::replaceFirst('http://', 'https://', $url);
        }

        return $url;
    }
}
