<?php

namespace App\Services;

use App\Models\Masukan;

class KontakService extends Service
{
    public function search($params = [])
    {
        $masukan = masukan::orderBy('id');
        $email = $params['email'] ?? '';
        if ($email !== '') {
            $masukan = $masukan->whereHas('user', fn($user) => $user->where('email', 'like', "%$email%"));
        }
        $masukan = $this->searchFilter($params, $masukan, ['nama']);
        return $this->searchResponse($params, $masukan);
    }

    public function find($value, $column = 'id')
    {
        return masukan::where($column, $value)->first();
    }

    public function store($params)
    {
        return Masukan::create($params);
    }
}
