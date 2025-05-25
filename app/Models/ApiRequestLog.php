<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    protected $fillable = [
        'user_id',
        'model_name',
        'provider',
        'prompt',
        'tokens_used',
        'response_meta',
    ];

    protected $casts = [
        'response_meta' => 'array',
    ];
}
