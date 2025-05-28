<?php

namespace App\Http\Controllers;

use App\Models\UserApiTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApiTypeController extends Controller
{
    /**
     * Display a listing of the user's API entries.
     */
    public function index()
    {
        $userApiTypes = UserApiTypes::where('user_id', Auth::id())->get();

        $response = $userApiTypes->map(function ($item) {
            return collect($item->toArray())->except([
                'user_id',
                'created_at',
                'deleted_at',
            ]);
        })->values();
        return response()->json($response);
    }

    /**
     * Store a newly created API entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'provider' => 'required|string',
            'tag' => 'required|string',
            'prompt' => 'required|string',
            'temperature'=> 'nullable|numeric',
            'max_tokens'=> 'nullable|integer',
        ]);

        $apiType = UserApiTypes::create([
            ...$validated,
            'user_id' => Auth::id(),
            'mode_name' => $validated['name'],
        ]);



        return response()->json($apiType, 201);
    }

    /**
     * Show a single API entry.
     */
    public function show(UserApiTypes $userApiType)
    {
        $this->authorize('view', $userApiType);
        return response()->json($userApiType);
    }

    /**
     * Update an existing API entry.
     */
    public function update(Request $request, UserApiTypes $userApiType)
    {
        $this->authorize('update', $userApiType);

        $validated = $request->validate([
            'provider' => 'sometimes|string',
            'tag' => 'sometimes|string',
            'prompt' => 'sometimes|string',
            'temperature' => 'nullable|numeric',
            'max_tokens' => 'nullable|integer',
            'mode_name' => 'sometimes|string',
        ]);

        $userApiType->update($validated);

        return response()->json($userApiType);
    }

    /**
     * Delete an API entry.
     */
    public function destroy(UserApiTypes $userApiType)
    {
        $this->authorize('delete', $userApiType);
        $userApiType->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
