<?php

namespace App\Providers;

use App\Models\Note;
use App\Models\Category;
use App\Models\NoteVersion;
use App\Policies\NotePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\NoteVersionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Explicitly register policies so they are recognized by authorize()
        Gate::policy(Note::class, NotePolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(NoteVersion::class, NoteVersionPolicy::class);
    }
}
