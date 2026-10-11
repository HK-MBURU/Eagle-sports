<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {!! $seoTags !!}
</head>
<body>
    <div style="max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
        <h1>{{ $title }}</h1>
        <p><strong>{{ $story->author_name }}</strong> | {{ $story->published_date->format('F d, Y') }}</p>

        @if($image)
        <img src="{{ $image }}" alt="{{ $title }}" style="max-width: 100%; height: auto; margin: 20px 0;">
        @endif

        <div style="font-size: 18px; line-height: 1.6; color: #333;">
            {!! $story->excerpt !!}
        </div>

        <p style="margin-top: 30px;">
            <a href="{{ $url }}" style="background: #FF6600; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Read Full Story
            </a>
        </p>
    </div>
</body>
</html>
