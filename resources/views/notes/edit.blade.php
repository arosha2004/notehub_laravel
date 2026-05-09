<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Note') }}: {{ $note->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('notes.update', $note) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $note->title) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                        <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $note->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                        <textarea name="content" id="content" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $note->content) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_favorite" value="1" {{ $note->is_favorite ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-indigo-600">
                            <span class="ml-2 text-gray-700 font-bold">Mark as Favorite</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Note
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 underline">Cancel</a>
                    </div>
                </form>

                @if($note->versions->count() > 0)
                    <div class="mt-12 border-t pt-6">
                        <h3 class="text-lg font-bold mb-4">Version History</h3>
                        <div class="space-y-4">
                            @foreach($note->versions()->latest()->get() as $version)
                                <div class="bg-gray-50 p-4 rounded border">
                                    <div class="text-sm text-gray-500 mb-2">
                                        Changed {{ $version->created_at->diffForHumans() }} by {{ $version->editor->name }}
                                    </div>
                                    <div class="text-gray-700 text-sm whitespace-pre-wrap truncate max-h-20">
                                        {{ $version->old_content }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
