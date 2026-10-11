<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryViewController extends Controller
{
    public function show(string $slug)
    {
        $story = Story::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Prepare meta data
        $metaTitle = $story->meta_title ?? $story->title;
        $metaDescription = $story->meta_description ?? strip_tags($story->excerpt);
        $ogImage = $story->og_image_url;
        $canonicalUrl = url("/stories/{$story->slug}");

        return view('stories.show', compact('story', 'metaTitle', 'metaDescription', 'ogImage', 'canonicalUrl'));
    }
}