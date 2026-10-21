<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class StoryController extends Controller
{
    // Replace the index() method in your StoryController with this enhanced version

    public function index(Request $request)
    {
        $query = Story::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by title or author
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Get stories with pagination
        $stories = $query->latest('created_at')->paginate(10)->withQueryString();

        return view('admin.stories.index', compact('stories'));
    }

    public function create()
    {
        $categoryTree = $this->buildCategoryTree();
        return view('admin.stories.create', compact('categoryTree'));
    }

    public function store(Request $request)
    {
        $hasTempImage = $request->filled('image_temp')
            && Storage::disk('public')->exists($request->input('image_temp'));

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'excerpt'           => 'required|string',
            'content'           => 'required|string',
            'image'             => $hasTempImage
                                    ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
                                    : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'image_temp'        => 'nullable|string',
            'image_attribution' => 'nullable|string|max:255',
            'category'          => 'required|string',
            'author_name'       => 'required|string|max:255',
            'read_time'         => 'required|integer|min:1',
            'published_date'    => 'required|date',
            'is_featured'       => 'boolean',
            'status'            => 'required|in:draft,published,archived',
            'meta_title'        => 'nullable|string|max:60',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string|max:255',
            'og_image_original' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'og_image_cropped'  => 'nullable|string',
            'youtube_url'       => 'nullable|url',
            'hashtags'          => 'nullable|string|max:500',
        ]);

        // Featured image: use AJAX pre-uploaded temp file, or process fresh upload
        if ($request->hasFile('image')) {
            try {
                $validated['image'] = $this->processAndStoreImage($request->file('image'), 'stories', [
                    'max_width' => 1920, 'max_height' => 1080, 'quality' => 85,
                ]);
            } catch (\Exception $e) {
                return back()->withInput()
                    ->withErrors(['image' => 'Image processing failed: ' . $e->getMessage()
                        . '. Try a smaller file or a different format (JPEG/PNG).']);
            }
        } elseif ($hasTempImage) {
            // Move temp file to permanent stories directory
            $tempPath = $request->input('image_temp');
            $newPath  = 'stories/' . basename($tempPath);
            Storage::disk('public')->move($tempPath, $newPath);
            $validated['image'] = $newPath;
        }

        // OG image: uploaded file or cropped base64
        if ($request->hasFile('og_image_original')) {
            try {
                $validated['og_image'] = $this->processAndStoreImage(
                    $request->file('og_image_original'), 'stories/og',
                    ['width' => 1200, 'height' => 630, 'quality' => 85]
                );
            } catch (\Exception $e) {
                // Non-fatal — just skip OG image
            }
        } elseif ($request->filled('og_image_cropped')) {
            try {
                $validated['og_image'] = $this->processBase64Image(
                    $request->og_image_cropped, 'stories/og',
                    ['width' => 1200, 'height' => 630, 'quality' => 85]
                );
            } catch (\Exception $e) {
                // Non-fatal — just skip OG image
            }
        }

        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);

        // Set is_featured
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        // Auto-generate meta_title if not provided
        if (empty($validated['meta_title'])) {
            $validated['meta_title'] = $validated['title'];
        }

        // Auto-generate meta_description if not provided
        if (empty($validated['meta_description'])) {
            $validated['meta_description'] = Str::limit(strip_tags($validated['excerpt']), 160);
        }

        Story::create($validated);

        return redirect()->route('admin.stories.index')
            ->with('success', 'Story created successfully!');
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug already exists
        while (Story::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function show(Story $story)
    {
        $categoryObj = StoryCategory::where('name', $story->category)
            ->with('parent')
            ->first();
        return view('admin.stories.show', compact('story', 'categoryObj'));
    }

    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            $path = $this->processAndStoreImage($request->file('image'), 'stories/temp', [
                'max_width' => 1920,
                'max_height' => 1080,
                'quality'   => 85,
            ]);

            return response()->json([
                'success' => true,
                'path'    => $path,
                'url'     => asset('storage/' . $path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not process image: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function edit(Story $story)
    {
        $categoryTree = $this->buildCategoryTree();
        return view('admin.stories.edit', compact('story', 'categoryTree'));
    }

    public function update(Request $request, Story $story)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_attribution' => 'nullable|string|max:255',
            'category' => 'required|string',
            'author_name' => 'required|string|max:255',
            'read_time' => 'required|integer|min:1',
            'published_date' => 'required|date',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',

            // SEO fields
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image_original' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'og_image_cropped' => 'nullable|string',

            // Media field
            'youtube_url' => 'nullable|url',

            // Social media
            'hashtags' => 'nullable|string|max:500',
        ]);

        // Handle featured image upload with compression
        if ($request->hasFile('image')) {
            // Delete old image and its webp version
            $this->deleteImageWithVariants($story->image);

            $imagePath = $this->processAndStoreImage($request->file('image'), 'stories', [
                'quality' => 85,
                'max_width' => 1920,
                'max_height' => 1080,
                'create_webp' => true
            ]);
            $validated['image'] = $imagePath;
        } else {
            // Keep the existing image if no new image is uploaded
            $validated['image'] = $story->image;
        }

        // Handle OG image - either uploaded file or cropped base64
        if ($request->hasFile('og_image_original')) {
            // Delete old OG image and its webp version
            if ($story->og_image) {
                $this->deleteImageWithVariants($story->og_image);
            }

            $ogImagePath = $this->processAndStoreImage($request->file('og_image_original'), 'stories/og', [
                'quality' => 85,
                'width' => 1200,
                'height' => 630,
                'create_webp' => true
            ]);
            $validated['og_image'] = $ogImagePath;
        } elseif ($request->filled('og_image_cropped')) {
            // Delete old OG image and its webp version
            if ($story->og_image) {
                $this->deleteImageWithVariants($story->og_image);
            }

            $ogImagePath = $this->processBase64Image($request->og_image_cropped, 'stories/og', [
                'quality' => 85,
                'width' => 1200,
                'height' => 630,
                'create_webp' => true
            ]);
            $validated['og_image'] = $ogImagePath;
        } else {
            // Keep the existing OG image if no new image is uploaded
            $validated['og_image'] = $story->og_image;
        }

        // Update slug if title changed
        if ($validated['title'] !== $story->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Set is_featured
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        $story->update($validated);

        return redirect()->route('admin.stories.index')
            ->with('success', 'Story updated successfully!');
    }


    /**
     * Upload images from TinyMCE editor — always returns JSON (never redirects).
     */
    public function uploadImage(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first('file')], 422);
        }

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file received'], 400);
        }

        try {
            $path = $request->file('file')->store('stories/content', 'public');
            return response()->json(['location' => asset('storage/' . $path)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    private function processAndStoreImage($imageFile, $directory, $options = [])
    {
        $defaultOptions = [
            'quality' => 85,
            'max_width' => 1920,
            'max_height' => 1080,
            'width' => null,
            'height' => null,
            'create_webp' => false
        ];

        $options = array_merge($defaultOptions, $options);

        // Generate unique filename
        $extension = strtolower($imageFile->getClientOriginalExtension());
        $filename = Str::random(40) . '.' . $extension;
        $filePath = $directory . '/' . $filename;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageFile->getPathname());

        if ($options['width'] && $options['height']) {
            $image = $image->cover($options['width'], $options['height']);
        } else {
            $image = $image->scaleDown($options['max_width'], $options['max_height']);
        }

        $encoded = match ($extension) {
            'png' => $image->toPng(),
            'webp' => $image->toWebp($options['quality']),
            'gif' => $image->toGif(),
            default => $image->toJpeg($options['quality']),
        };

        Storage::disk('public')->put($filePath, (string) $encoded);

        if ($options['create_webp']) {
            $webpPath = $directory . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $webpEncoded = $image->toWebp($options['quality']);
            Storage::disk('public')->put($webpPath, (string) $webpEncoded);
        }

        return $filePath;
    }

    /**
     * Process base64 image from cropper
     */
    private function processBase64Image($base64Image, $directory, $options = [])
    {
        $defaultOptions = [
            'quality' => 85,
            'width' => 1200,
            'height' => 630,
            'create_webp' => false
        ];

        $options = array_merge($defaultOptions, $options);

        // Extract base64 data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Invalid image type');
            }

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('Did not match data URI with image data');
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageData);

        if ($options['width'] && $options['height']) {
            $image = $image->cover($options['width'], $options['height']);
        }

        // Generate unique filename
        $filename = Str::random(40) . '.jpg';
        $filePath = $directory . '/' . $filename;

        $encoded = $image->toJpeg($options['quality']);
        Storage::disk('public')->put($filePath, (string) $encoded);

        if ($options['create_webp']) {
            $webpPath = $directory . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $webpEncoded = $image->toWebp($options['quality']);
            Storage::disk('public')->put($webpPath, (string) $webpEncoded);
        }

        return $filePath;
    }

    /**
     * Delete image and its variants (webp)
     */
    private function deleteImageWithVariants($imagePath)
    {
        if (!$imagePath) return;

        // Delete main image
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    public function destroy(Story $story)
    {
        // Delete images and their variants from storage
        if ($story->image) {
            $this->deleteImageWithVariants($story->image);
        }
        if ($story->og_image) {
            $this->deleteImageWithVariants($story->og_image);
        }

        $story->delete();

        return redirect()->route('admin.stories.index')
            ->with('success', 'Story deleted successfully!');
    }

    private function buildCategoryTree(): \Illuminate\Support\Collection
    {
        try {
            return StoryCategory::with(['children' => fn ($q) => $q->active()->orderBy('sort_order')->orderBy('name')])
                ->active()
                ->topLevel()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            // Table not yet migrated — wrap hardcoded list as mock objects
            return collect(Story::CATEGORIES)->map(fn ($name) => (object)[
                'name'     => $name,
                'color'    => '#6366f1',
                'children' => collect(),
            ]);
        }
    }
}
