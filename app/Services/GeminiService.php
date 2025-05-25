<?php

namespace App\Services;

use App\Models\AIModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\Contracts\GeneratorService;

class GeminiService implements GeneratorService
{
    public function generate(AIModel $model, Request $request): array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model->name}:generateContent?key={$model->api_key}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $request->prompt],
                    ],
                ],
            ],
        ];

        // Optional config
        $generationConfig = [];

        if ($request->has('temperature')) {
            $generationConfig['temperature'] = $request->temperature;
        }

        if ($request->has('max_tokens')) {
            $generationConfig['maxOutputTokens'] = $request->max_tokens;
        }

        if (!empty($generationConfig)) {
            $payload['generationConfig'] = $generationConfig;
        }

        $res = Http::post($url, $payload);

        return $res->json();
    }
}
