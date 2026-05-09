<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                <div class="w-full md:w-1/2 flex items-center space-x-4">
                    <input wire:model.live="search" type="text" placeholder="Search notes..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
                
                <div class="w-full md:w-1/2 flex justify-end items-center space-x-4">
                    <select wire:model.live="categoryId" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    
                    <button wire:click="$toggle('showFavoritesOnly')" class="px-4 py-2 rounded-md {{ $showFavoritesOnly ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Favorites
                    </button>
                    
                    <a href="{{ route('notes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition duration-150">
                        + New Note
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($notes as $note)
                    <div class="border rounded-lg p-4 hover:shadow-md transition duration-150 relative group bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-gray-800">{{ $note->title }}</h3>
                            <button wire:click="toggleFavorite({{ $note->id }})" class="text-2xl focus:outline-none">
                                {{ $note->is_favorite ? '⭐' : '☆' }}
                            </button>
                        </div>
                        <p class="text-gray-600 line-clamp-3 mb-4">
                            {{ Str::limit($note->content, 150) }}
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-400">
                            <span>{{ $note->category->name ?? 'Uncategorized' }}</span>
                            <span>{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="mt-4 flex space-x-2 opacity-0 group-hover:opacity-100 transition duration-150">
                            <a href="{{ route('notes.edit', $note) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">Edit</a>
                            <button wire:click="deleteNote({{ $note->id }})" wire:confirm="Are you sure you want to delete this note?" class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">
                        No notes found.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
</div>
