<?php

namespace App\Http\Controllers;

use App\Models\ApiRequestLog;
use Illuminate\Support\Facades\Auth;

class UsageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalRequests = ApiRequestLog::where('user_id', $userId)->count();
        $totalTokens = ApiRequestLog::where('user_id', $userId)->sum('tokens_used');

        $byModel = ApiRequestLog::where('user_id', $userId)
            ->selectRaw('model_name, COUNT(*) as count, SUM(tokens_used) as tokens')
            ->groupBy('model_name')
            ->get();

        return response()->json([
            'total_requests' => $totalRequests,
            'total_tokens' => $totalTokens,
            'by_model' => $byModel,
        ]);
    }
}
