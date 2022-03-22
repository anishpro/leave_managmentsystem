<?php

use Illuminate\Support\Facades\DB; 

function showMenu()
{
    $role_id = session('ROLE');
    //print_r('role'.$role_id); exit;
    $modules = json_decode(DB::table('modules')->get()->toJson(), true);
    //print_r($modules); //exit;
    $menus  = json_decode(DB::table('menus')
            ->select(DB::raw('menus.id, menus.menu_name, menus.menu_url, menus.parent_id, menus.module_id'))
            ->join('menu_permissions', 'menu_permissions.menu_id', '=', 'menus.id')
            ->where('menu_permissions.role_id', $role_id)
            ->where('menus.status', 1)
           // ->whereNull('action')
            ->orderBy('menus.id', 'ASC')
            ->get()->toJson(), true);

    //     print_r(DB::table('menus')
    //     ->select(DB::raw('menus.id, menus.menu_name, menus.menu_url, menus.parent_id, menus.module_id'))
    //     ->join('menu_permissions', 'menu_permissions.menu_id', '=', 'menus.id')
    //     ->where('menu_permissions.role_id', $role_id)
    //     ->where('menus.status', 1)
    //    // ->whereNull('action')
    //     ->orderBy('menus.id', 'ASC')->toSql());

    $sideMenu = [];
    if ($menus) {
        foreach ($menus as $menu) {
            //print_r('module'.$menu['module_id']);
            if (!isset($sideMenu[$menu['module_id']])) {
                $moduleId = array_search($menu['module_id'], array_column($modules, 'id'));

                $sideMenu[$menu['module_id']]               = [];
                $sideMenu[$menu['module_id']]['id']         = $modules[$moduleId]['id'];
                $sideMenu[$menu['module_id']]['name']       = $modules[$moduleId]['module_name'];
                $sideMenu[$menu['module_id']]['icon_class'] = $modules[$moduleId]['icon_class'];
                $sideMenu[$menu['module_id']]['menu_url']   = '#';
                $sideMenu[$menu['module_id']]['parent_id']  = '';
                $sideMenu[$menu['module_id']]['module_id']  = $modules[$moduleId]['id'];
                $sideMenu[$menu['module_id']]['sub_menu']   = [];
            }
            if ($menu['parent_id'] == 0) {
                $sideMenu[$menu['module_id']]['sub_menu'][$menu['id']]             = $menu;
                $sideMenu[$menu['module_id']]['sub_menu'][$menu['id']]['sub_menu'] = [];                
            } else {
                array_push($sideMenu[$menu['module_id']]['sub_menu'][$menu['parent_id']]['sub_menu'], $menu);
            }

        }
    }
    //print_r($sideMenu);
    return $sideMenu;
}

?>