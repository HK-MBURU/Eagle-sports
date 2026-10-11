<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Display a listing of the quotes.
     */
    public function index()
    {
        $quotes = Quote::with('addedBy')->latest()->paginate(15);
        return view('admin.quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new quote.
     */
    public function create()
    {
        return view('admin.quotes.create');
    }

    /**
     * Store a newly created quote in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'owner' => 'required|string|max:255',
            'owner_title' => 'nullable|string|max:255',
        ]);

        $validated['added_by'] = auth()->id();

        Quote::create($validated);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote created successfully.');
    }

    /**
     * Display the specified quote.
     */
    public function show(Quote $quote)
    {
        $quote->load('addedBy');
        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified quote.
     */
    public function edit(Quote $quote)
    {
        return view('admin.quotes.edit', compact('quote'));
    }

    /**
     * Update the specified quote in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'owner' => 'required|string|max:255',
            'owner_title' => 'nullable|string|max:255',
        ]);

        $quote->update($validated);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote updated successfully.');
    }

    /**
     * Remove the specified quote from storage.
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote deleted successfully.');
    }
}
