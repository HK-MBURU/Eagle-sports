<?php

namespace App\Console\Commands;

use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExtractStoryContentImages extends Command
{
    protected $signature = 'stories:extract-images
                            {--dry-run : Preview changes without writing to disk or DB}';

    protected $description = 'Extract base64 images embedded in story content and save them as real files';

    public function handle(): int
    {
        $stories = Story::whereRaw("content LIKE '%data:image%'")->get(['id', 'title', 'slug', 'content']);

        if ($stories->isEmpty()) {
            $this->info('No stories with embedded base64 images found.');
            return self::SUCCESS;
        }

        $dryRun = $this->option('dry-run');
        $this->info("Found {$stories->count()} stories with base64 images." . ($dryRun ? ' (DRY RUN)' : ''));

        $totalSaved = 0;

        foreach ($stories as $story) {
            $this->line("\n[{$story->id}] {$story->title}");
            $originalSize = strlen($story->content);
            $imageCount = 0;

            $newContent = preg_replace_callback(
                '/src="(data:image\/([^;]+);base64,([A-Za-z0-9+\/=]+))"/i',
                function ($m) use ($story, &$imageCount, $dryRun): string {
                    $imageCount++;
                    $ext = $this->mimeToExt(strtolower($m[2]));
                    $hash = substr(md5($m[3]), 0, 16);
                    $path = "stories/content-images/{$story->slug}-{$hash}.{$ext}";

                    if (!$dryRun) {
                        $bytes = base64_decode($m[3], true);
                        if ($bytes === false) {
                            $this->warn("    Image #{$imageCount}: base64 decode failed — skipping");
                            return $m[0];
                        }
                        Storage::disk('public')->put($path, $bytes);
                    }

                    $url = '/storage/' . $path;
                    $this->line("    -> Image #{$imageCount} → {$url}");
                    return "src=\"{$url}\"";
                },
                $story->content
            );

            if ($imageCount === 0) {
                $this->line('    No replaceable base64 images found (may be in style attributes).');
                continue;
            }

            $newSize = strlen($newContent);
            $saved = $originalSize - $newSize;
            $totalSaved += $saved;

            $this->info(sprintf(
                '    %d image(s) extracted. %s → %s (freed %s)',
                $imageCount,
                $this->fmt($originalSize),
                $this->fmt($newSize),
                $this->fmt($saved)
            ));

            if (!$dryRun) {
                Story::where('id', $story->id)->update(['content' => $newContent]);
                $this->info('    ✓ Database updated.');
            }
        }

        $this->info("\nTotal space freed: " . $this->fmt($totalSaved));
        return self::SUCCESS;
    }

    private function mimeToExt(string $mime): string
    {
        return match ($mime) {
            'jpeg', 'jpg' => 'jpg',
            'png'         => 'png',
            'webp'        => 'webp',
            'gif'         => 'gif',
            default       => 'jpg',
        };
    }

    private function fmt(int $bytes): string
    {
        if ($bytes >= 1_000_000) return round($bytes / 1_000_000, 2) . ' MB';
        if ($bytes >= 1_000)     return round($bytes / 1_000, 1) . ' KB';
        return $bytes . ' B';
    }
}
