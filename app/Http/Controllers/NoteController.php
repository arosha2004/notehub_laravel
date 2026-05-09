<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Note::class);
        // Note: We are using a Livewire component for the dashboard, 
        // so this index method might just redirect or load a simple view.
        return view('notes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Note::class);
        $categories = \App\Models\Category::all();
        return view('notes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $this->authorize('create', Note::class);
        
        $request->user()->notes()->create($request->validated());

        return redirect()->route('dashboard')->with('message', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        $categories = \App\Models\Category::all();
        return view('notes.edit', compact('note', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        if ($request->content !== $note->content) {
            $note->versions()->create([
                'old_content' => $note->content ?? '',
                'updated_by' => auth()->id(),
            ]);
        }

        $note->update($request->validated());

        return redirect()->route('dashboard')->with('message', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return redirect()->route('dashboard')->with('message', 'Note deleted successfully.');
    }
}
