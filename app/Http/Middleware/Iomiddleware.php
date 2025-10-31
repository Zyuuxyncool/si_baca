<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $user_roles = $user->list_akses->pluck('akses')->toArray();
        $role = $user->akses->akses;
        $active_role = session('active_role', $role);
        

        if ($request->method() === 'GET' && !$request->ajax()) {
            $menuService = new MenuService();
            $menus = $menuService->list_menu($active_role);

            view()->share(['menus' => $menus, 'active_role' => $active_role, 'all_roles' => $user_roles]);
            $current_route = $request->route()->getName();
            $current_route_params = $request->query();
            $data = $menuService::current_menu($menus, $current_route, $active_role, $current_route_params);
            if (empty($data['current_menu']) && empty($data['current_sub_menu']) && empty($data['current_side_menu']) && $current_route !== 'admin' && count(explode('.', $current_route)) < 3) {
                abort(401);
            }
            view()->share($data);

            $route = $request->route()->getName();
            $head_route = head(explode('.', $route));
            
            if (in_array(strtolower($role), ['user']) && $head_route === 'admin') {
                abort(403, 'Access Denied.');
            }

        }
        return $next($request);
    }
}
