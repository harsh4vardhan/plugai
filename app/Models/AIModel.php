<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ModelProvider;

class AIModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'provider',
        'api_key',
    ];

    protected $casts = [
        'provider' => ModelProvider::class,
    ];
}
