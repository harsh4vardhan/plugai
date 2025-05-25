<?php

namespace App\Services;

use App\Models\AIModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\Contracts\GeneratorService;

class AnthropicService implements GeneratorService
{
    public function generate(AIModel $model, Request $request): array
    {
        $params = [
            'model' => 'claude-3-opus-20240229',
            'prompt' => $request->prompt,
            'max_tokens_to_sample' => $request->max_tokens ?? 256,
        ];

        $res = Http::withToken($model->api_key)
            ->post('https://api.anthropic.com/v1/complete', $params);

        return $res->json();
    }
}
