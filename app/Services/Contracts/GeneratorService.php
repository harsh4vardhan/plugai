<?php

namespace App\Services\Contracts;

use App\Models\AIModel;
use Illuminate\Http\Request;

interface GeneratorService
{
    public function generate(AIModel $model, Request $request): array;
}
