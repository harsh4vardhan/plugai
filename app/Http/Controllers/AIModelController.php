<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AIModel;
use Illuminate\Support\Facades\Auth;

class AIModelController extends Controller
{
    public function index()
    {
        return AIModel::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
        ]);

        $model = AIModel::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'provider' => $request->type,
            'api_key' => $request->api_key,
        ]);

        return response()->json($model);
    }

    public function destroy($id)
    {
        $model = AIModel::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $model->delete();

        return response()->json(['success' => true]);
    }
}

