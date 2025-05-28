<?php

namespace App\Services;

use App\Models\AIModel;
use App\Models\UserApiTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserApiParameters
{
    public function __construct(protected UserApiTypes $userApiTypes)
    {
    }
    public function getUserApiParameters($apiTag): array
    {
        $params = [];
        $params = $this->userApiTypes->where('tag', $apiTag)->first()?->toArray();
        $params['name'] = $params['mode_name'];
//        var_dump($params);
        return $params;
    }

    public function addUserApiParameters(): void
    {

    }
}
