<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserSaveRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
protected $userService;

public function __construct()
{
    $this->userService = new UserService();
    view()->share(['list_akses' => $this->userService->list_akses()]);
}

public function index()
{
    return view('admin.user.index');
}

public function search(Request $request)
{
    $users = $this->userService->search($request->all());
    return view('admin.user._table', compact('users'));
}

public function create()
{
    return view('admin.user._form');
}

public function store(UserSaveRequest $request)
{
//        $request->merge(['akses' => 'Admin Siap Kerja']);
    return $this->userService->store($request->all());
}

public function edit($id)
{
    $user = $this->userService->find($id);
    return view('admin.user._form', compact('user'));
}

public function show($id)
{
    return $this->userService->find($id);
}

public function update(UserSaveRequest $request, $id)
{
    return $this->userService->update($request->all(), $id);
}

public function destroy($id)
{
    return $this->userService->delete($id);
}
}
