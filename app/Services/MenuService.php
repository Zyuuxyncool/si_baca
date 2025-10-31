<?php

namespace App\Services;

use App\Models\UserAkses;

class MenuService
{
    protected static array $admin = [
        ['route' => 'admin.dashboard', 'caption' => 'Dashboard'],
        ['route' => 'admin.user.index', 'caption' => 'User Program'],
        'data_master' => ['route' => '#', 'caption' => 'Data Master', 'sub_menus' => [
            ['route' => 'admin.categories.index', 'caption' => 'Kategori'],
            ['route' => 'admin.category_sub.index', 'caption' => 'Sub Kategori'],
            ['route' => 'admin.brands.index', 'caption' => 'Brand'],
        ]],
    ];

    protected static array $user = [
        
    ];


    public function list_menu($role): array
    {
        return match ($role) {
            "Administrator" => self::$admin,
            "User" => self::$user,
            default => [],
        };
    }

    public static function current_menu($menus, $current_route, $role_active, $current_route_params = [])
    {
        unset($current_route_params['warehouse_selected']);
        unset($current_route_params['date']);
        unset($current_route_params['page']);
        unset($current_route_params['tab_active']);

        $role_captions = [
            'Administrator' => 'Administrator',
            'User'         => 'User',
        ];
        $caption = $role_captions[$role_active] ?? '';
        if ($caption == '') $caption = $role_active;

        $breadcrumbs = [['route' => head(explode('.', $current_route)), 'caption' => $caption]];
        $current_menu = [];
        $current_sub_menu = [];
        $current_side_menu = [];
        foreach ($menus as $menu) {
            if ($menu['route'] === $current_route && ($menu['params'] ?? []) === $current_route_params) {
                $current_menu = $menu;
                $breadcrumbs[] = $menu;
            }
            foreach ($menu['sub_menus'] ?? [] as $sub_menu) {
                if ($sub_menu['route'] === $current_route && ($sub_menu['params'] ?? []) === $current_route_params) {
                    $current_menu = $menu;
                    $current_sub_menu = $sub_menu;
                    if ($sub_menu['route'] !== $menu['route']) $breadcrumbs[] = $sub_menu;
                }
                foreach ($sub_menu['side_menus'] ?? [] as $side_menu) {
                    if ($side_menu['route'] === $current_route && ($side_menu['params'] ?? []) === $current_route_params) {
                        $current_menu = $menu;
                        $current_sub_menu = $sub_menu;
                        $current_side_menu = $side_menu;
                        $breadcrumbs[] = $sub_menu;
                        $breadcrumbs[] = $side_menu;
                    }
                }
            }
            foreach ($menu['side_menus'] ?? [] as $side_menu) {
                if ($side_menu['route'] === $current_route && ($side_menu['params'] ?? []) === $current_route_params) {
                    $current_menu = $menu;
                    $current_side_menu = $side_menu;
                    if (last($breadcrumbs)['route'] !== $menu['route']) $breadcrumbs[] = $menu;
                    if ($side_menu['route'] !== $menu['route'] || ($side_menu['params'] ?? []) !== ($menu['params'] ?? [])) $breadcrumbs[] = $side_menu;
                }
            }
        }

        if (empty($current_menu)) {
            $temp = explode('.', $current_route);
            if (last($temp) === 'show' || last($temp) === 'create') {
                $temp[count($temp) - 1] = 'index';
                $current_route = join('.', $temp);
                return self::current_menu($menus, $current_route, $role_active, $current_route_params);
            } else {
                if (count($temp) > 2) {
                    array_splice($temp, count($temp) - 2, 1);
                    $current_route = join('.', $temp);
                    return self::current_menu($menus, $current_route, $role_active, $current_route_params);
                }
            }
        }

        $current = $current_side_menu ?? [];
        if (empty($current)) $current = $current_sub_menu ?? [];
        if (empty($current)) $current = $current_menu;
        $actions = $current['actions'] ?? [];

        return [
            'current_menu' => $current_menu,
            'current_sub_menu' => $current_sub_menu,
            'current_side_menu' => $current_side_menu,
            'breadcrumbs' => $breadcrumbs,
            'actions' => $actions,
        ];
    }
}
