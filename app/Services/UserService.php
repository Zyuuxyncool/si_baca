<?php

namespace App\Services;

use App\Models\User;
use APp\Models\UserAkses;

class UserService extends Service
{
    public function search($params = [])
    {
        $user = User::orderBy('id');
        $name = $params['name'] ?? '';
        if ($name !== '') $user = $user->where('name', 'like', "%$name%");

        $not_id = $params['not_id'] ?? '';
        if ($not_id !== '') $user = $user->where('id', '<>', $not_id);

        $akses = $params['akses'] ?? '';
        if ($akses !== '') $user = $user->whereHas('list_akses', fn($list_akses) => $list_akses->where('akses', $akses));
        $user = $this->searchFilter($params, $user, ['email']);
        return $this->searchResponse($params, $user);
    }

    public function find($value, $column = 'id')
    {
        return User::where($column, $value)->first();
    }

    public function store($params)
    {
        $params = $this->clean_password($params);
        $user = User::create($params);
        if (!empty($params['akses'])) {
            UserAkses::create([
                'user_id' => $user->id,
                'akses' => $params['akses'],
            ]);
        }
        return $user;
    }

    public function update($id, $params)
    {
        $params = $this->clean_password($params);
        $user = User::find($id);
        if (!empty($user)) $user->update($params);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            try {
                $user->delete();
                return true;
            } catch (\Exception $e) {
                return ['error' => 'Delete user failed! This user currently being used'];
            }
        }
        return $user;
    }

    public function clean_password($params)
    {
        $password = $params['password'] ?? '';
        if ($password === '') unset($params['password']);
        else $params['password'] = bcrypt($password);
        return $params;
    }

    public function list_akses()
    {
        return array_combine(User::LIST_AKSES, User::LIST_AKSES);
    }

    public function base_routes()
    {
        return User::BASE_ROUTES;
    }
}
