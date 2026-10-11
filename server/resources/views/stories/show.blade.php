<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Basic Meta Tags -->
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if($story->meta_keywords)
    <meta name="keywords" content="{{ $story->meta_keywords }}">
    @endif
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $canonicalUrl }}">
    
    <!-- Open Graph Meta Tags (Facebook, LinkedIn) -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    <!-- Article Specific Meta Tags -->
    <meta property="article:published_time" content="{{ $story->published_date->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $story->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $story->author_name }}">
    <meta property="article:section" content="{{ $story->category }}">
    @if($story->meta_keywords)
        @foreach(explode(',', $story->meta_keywords) as $tag)
    <meta property="article:tag" content="{{ trim($tag) }}">
        @endforeach
    @endif
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @if($story->author_name)
    <meta name="twitter:creator" content="{{ $story->author_name }}">
    @endif
    
    <!-- Additional Meta Tags -->
    <meta name="author" content="{{ $story->author_name }}">
    <meta name="robots" content="index, follow">
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $story->title }}",
        "description": "{{ $metaDescription }}",
        "image": "{{ $ogImage }}",
        "datePublished": "{{ $story->published_date->toIso8601String() }}",
        "dateModified": "{{ $story->updated_at->toIso8601String() }}",
        "author": {
            "@type": "Person",
            "name": "{{ $story->author_name }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('logo.png') }}"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ $canonicalUrl }}"
        }
    }
    </script>
    
    <!-- Redirect to your TypeScript frontend -->
    <meta http-equiv="refresh" content="0;url={{ config('app.frontend_url') }}/stories/{{ $story->slug }}">
    
    <!-- Styling for fallback content -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .loading {
            text-align: center;
            color: #666;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .content {
            line-height: 1.6;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="loading">
            <p>Redirecting to story...</p>
            <p>If you're not redirected automatically, <a href="{{ config('app.frontend_url') }}/stories/{{ $story->slug }}">click here</a>.</p>
        </div>
        
        <!-- Fallback content for crawlers -->
        <article style="display:none;" itemscope itemtype="https://schema.org/Article">
            <h1 itemprop="headline">{{ $story->title }}</h1>
            <img src="{{ $ogImage }}" alt="{{ $story->title }}" itemprop="image">
            <div class="meta">
                <span itemprop="author">{{ $story->author_name }}</span> • 
                <time itemprop="datePublished" datetime="{{ $story->published_date->toIso8601String() }}">
                    {{ $story->formatted_date }}
                </time> • 
                {{ $story->read_time }} min read
            </div>
            <div class="content" itemprop="articleBody">
                <p>{{ $story->excerpt }}</p>
                {!! $story->content !!}
            </div>
        </article>
    </div>
</body>
</html>