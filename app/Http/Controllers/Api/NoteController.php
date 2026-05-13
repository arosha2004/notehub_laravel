<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Note::class);

        $query = $request->user()->notes()->with('category');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by favorite
        if ($request->has('is_favorite')) {
            $query->where('is_favorite', filter_var($request->is_favorite, FILTER_VALIDATE_BOOLEAN));
        }

        $notes = $query->latest()->paginate(10);

        return \App\Http\Resources\NoteResource::collection($notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Note::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_favorite' => 'boolean',
            'reminder_at' => 'nullable|date',
            'reminder_completed' => 'boolean',
        ]);

        $note = $request->user()->notes()->create($validated);

        return new \App\Http\Resources\NoteResource($note);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);

        return new \App\Http\Resources\NoteResource($note->load('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_favorite' => 'boolean',
            'reminder_at' => 'nullable|date',
            'reminder_completed' => 'boolean',
        ]);

        // Save version if content changes
        if ($request->has('content') && $request->content !== $note->content) {
            $note->versions()->create([
                'old_content' => $note->content ?? '',
                'updated_by' => $request->user()->id,
            ]);
        }

        $note->update($validated);

        return new \App\Http\Resources\NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
}
