<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class NoteDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = null;
    public $showFavoritesOnly = false;

    protected $updatesQueryString = ['search', 'categoryId', 'showFavoritesOnly'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleFavorite($noteId)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($noteId);
        $note->update(['is_favorite' => !$note->is_favorite]);
    }

    public function deleteNote($noteId)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($noteId);
        $note->delete();
    }

    public function render()
    {
        $query = Note::where('user_id', Auth::id())
            ->with('category')
            ->when($this->search, function($q) {
                $q->where(function($inner) {
                    $inner->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryId, function($q) {
                $q->where('category_id', $this->categoryId);
            })
            ->when($this->showFavoritesOnly, function($q) {
                $q->where('is_favorite', true);
            })
            ->latest();

        return view('livewire.note-dashboard', [
            'notes' => $query->paginate(12),
            'categories' => Category::all()
        ])->layout('layouts.app');
    }
}
