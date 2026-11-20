<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('addedBy')->latest()->paginate(15);
        return view('admin.quotes.index', compact('quotes'));
    }

    public function create()
    {
        return view('admin.quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'     => 'required|string',
            'owner'       => 'required|string|max:255',
            'owner_title' => 'nullable|string|max:255',
            'owner_image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
        ]);

        $validated['added_by'] = auth()->id();

        if ($request->hasFile('owner_image')) {
            $validated['owner_image'] = $this->storeImage($request->file('owner_image'));
        }

        Quote::create($validated);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote created successfully.');
    }

    public function show(Quote $quote)
    {
        $quote->load('addedBy');
        return view('admin.quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        return view('admin.quotes.edit', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'content'      => 'required|string',
            'owner'        => 'required|string|max:255',
            'owner_title'  => 'nullable|string|max:255',
            'owner_image'  => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        // Remove image if explicitly requested
        if ($request->boolean('remove_image') && $quote->owner_image) {
            $this->deleteImage($quote->owner_image);
            $validated['owner_image'] = null;
        }

        // Replace image if a new one was uploaded
        if ($request->hasFile('owner_image')) {
            if ($quote->owner_image) {
                $this->deleteImage($quote->owner_image);
            }
            $validated['owner_image'] = $this->storeImage($request->file('owner_image'));
        }

        unset($validated['remove_image']);
        $quote->update($validated);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote updated successfully.');
    }

    public function destroy(Quote $quote)
    {
        if ($quote->owner_image) {
            $this->deleteImage($quote->owner_image);
        }
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote deleted successfully.');
    }

    private function storeImage($file): string
    {
        $dir = public_path('images/quotes');
        File::ensureDirectoryExists($dir);
        $filename = uniqid('quote_') . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return 'quotes/' . $filename;
    }

    private function deleteImage(string $path): void
    {
        $full = public_path('images/' . $path);
        if (file_exists($full)) {
            unlink($full);
        }
    }
}
