<?php

namespace App\Services;

use App\Models\AIModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\Contracts\GeneratorService;

class OpenAIService implements GeneratorService
{
    public function generate(AIModel $model, Request $request): array
    {
        $modelName = $model->name; // e.g., "gpt-4", "text-davinci-003"

        $isChatModel = str_starts_with($modelName, 'gpt-3.5') || str_starts_with($modelName, 'gpt-4');

        if ($isChatModel) {
            $endpoint = 'https://api.openai.com/v1/chat/completions';
            $payload = [
                'model' => $modelName,
                'messages' => [
                    ['role' => 'user', 'content' => $request->prompt]
                ],
                'max_tokens' => $request->max_tokens ?? 256,
                'temperature' => $request->temperature ?? 0.7,
            ];
        } else {
            $endpoint = 'https://api.openai.com/v1/completions';
            $payload = [
                'model' => $modelName,
                'prompt' => $request->prompt,
                'max_tokens' => $request->max_tokens ?? 256,
                'temperature' => $request->temperature ?? 0.7,
            ];
        }

        $res = Http::withToken($model->api_key)
            ->post($endpoint, $payload);

        return $res->json();
    }
}

