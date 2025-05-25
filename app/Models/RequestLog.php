<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RequestLog extends Model
{
    protected $fillable = [
        'user_id',
        'a_i_model_id',
        'provider',
        'model_name',
        'prompt',
        'response',
        'tokens_used',
        'temperature',
    ];
}
