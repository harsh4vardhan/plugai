<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApiTypes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'tag',
        'prompt',
        'temperature',
        'tokens_used',
        'api_count',
        'max_tokens',
        'mode_name'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
