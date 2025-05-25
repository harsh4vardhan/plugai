<?php

namespace App\Services;

use App\Enums\ModelProvider;
use App\Services\Contracts\GeneratorService;
use InvalidArgumentException;

class GeneratorFactory
{
    public function make(ModelProvider $provider): GeneratorService
    {
        return match ($provider) {
            ModelProvider::OPENAI => new OpenAIService(),
            ModelProvider::GEMINI => new GeminiService(),
            ModelProvider::ANTHROPIC => new AnthropicService(),
            default => throw new InvalidArgumentException("Unsupported provider: {$provider->value}"),
        };
    }
}
