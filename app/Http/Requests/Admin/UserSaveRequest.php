<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route()->parameter('users') ?? '';
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email' . ($id != '' ? (',' . $id) : ''),
            'password' => 'confirmed'
        ];
    }
}
