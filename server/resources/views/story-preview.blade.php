<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Primary Meta Tags -->
    <title>{{ $title }}</title>
    <meta name="title" content="{{ $title }}" />
    <meta name="description" content="{{ $description }}" />
    <meta name="author" content="{{ $story->author_name }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ $url }}" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}{{ $hashtags ? "\n\n" . $hashtags : '' }}" />
    <meta property="og:image" content="{{ $image }}" />
    <meta property="og:image:secure_url" content="{{ $image }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="{{ $title }}" />
    <meta property="og:site_name" content="{{ $siteName }}" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ $url }}" />
    <meta name="twitter:title" content="{{ $title }}" />
    <meta name="twitter:description" content="{{ $description }}{{ $hashtags ? "\n\n" . $hashtags : '' }}" />
    <meta name="twitter:image" content="{{ $image }}" />

    <!-- Article Tags -->
    <meta property="article:published_time" content="{{ $story->published_date->toIso8601String() }}" />
    <meta property="article:author" content="{{ $story->author_name }}" />
    <meta property="article:section" content="{{ $story->category }}" />

</head>
<body>
    <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>
        <a href="{{ $url }}">Read the full story</a>
    </div>
</body>
</html>
