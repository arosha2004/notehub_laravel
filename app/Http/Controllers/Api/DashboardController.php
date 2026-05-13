<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $totalNotes = $user->notes()->count();
        $favoritesCount = $user->notes()->where('is_favorite', true)->count();
        
        $upcomingReminders = $user->notes()
            ->whereNotNull('reminder_at')
            ->where('reminder_completed', false)
            ->where('reminder_at', '>=', Carbon::now())
            ->orderBy('reminder_at', 'asc')
            ->take(5)
            ->get();

        $recentNotes = $user->notes()
            ->latest()
            ->take(5)
            ->get();

        $notesByCategory = $user->categories()->withCount('notes')->get();

        return response()->json([
            'stats' => [
                'total_notes' => $totalNotes,
                'favorites_count' => $favoritesCount,
                'upcoming_reminders_count' => $upcomingReminders->count(),
            ],
            'recent_notes' => \App\Http\Resources\NoteResource::collection($recentNotes),
            'upcoming_reminders' => \App\Http\Resources\NoteResource::collection($upcomingReminders),
            'categories_chart' => $notesByCategory
        ]);
    }
}
