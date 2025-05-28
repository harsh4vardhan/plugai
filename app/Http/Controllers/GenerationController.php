<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\ApiRequestLog;
use App\Models\RequestLog;
use App\Services\UserApiParameters;
use Illuminate\Http\Request;
use App\Services\GeneratorFactory;
use Illuminate\Support\Facades\Auth;

class GenerationController extends Controller
{
    public function __construct(protected GeneratorFactory $factory, protected UserApiParameters $apiParamsService) {}


    public function generate(Request $request)
    {
        $request->validate([
            'tag' => 'required|string',
        ]);

        $request->merge($this->apiParamsService->getUserApiParameters($request->tag));

        $model = AIModel::where('name', $request->name)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $service = $this->factory->make($model->provider);
        $response = $service->generate($model, $request);

        RequestLog::create([
            'user_id' => Auth::id(),
            'a_i_model_id' => $model->id,
            'provider' => $model->provider,
            'model_name' => $model->name,
            'prompt' => $request->prompt,
            'response' => is_string($response) ? $response : json_encode($response),
            'tokens_used' => $response['usage']['total_tokens'] ?? null, // adapt based on provider response
            'temperature' => $request->temperature,
        ]);

        return response()->json($response);
    }

}
