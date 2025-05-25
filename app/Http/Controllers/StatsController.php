<?php

namespace App\Http\Controllers;
use App\Models\RequestLog;
use Illuminate\Support\Facades\Auth;
class StatsController
{
    public function modelStats($id)
    {
        $user = Auth::user();

        $logs = RequestLog::where('user_id', $user->id)
            ->where('a_i_model_id', $id)
            ->get();

        $dailyCounts = $logs->groupBy(fn($log) => $log->created_at->format('Y-m-d'))
            ->map(fn($group) => ['date' => $group->first()->created_at->format('Y-m-d'), 'count' => $group->count()])
            ->values();

        return response()->json([
            'total_requests' => $logs->count(),
            'total_tokens' => $logs->sum('tokens_used'),
            'daily_counts' => $dailyCounts
        ]);
    }
}
