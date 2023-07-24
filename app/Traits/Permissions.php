<?php

namespace App\Traits;

use App\Models\Settings\Menus;
use App\Models\Settings\Roles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

trait Permissions
{
    public function __construct()
    {
        $this->pathUrl = Route::current()->getPrefix() != '' ? Route::current()->getPrefix() : str_replace(url('').'/','', URL::current());
    }

    public function userPermissions()
    {
        $data = Roles::with('users','permissions')->get();
        return $data;
    }

    public function accessPermissions($access = 'view', $url = null)
    {
        if(is_null($url)) {
            $url = substr($this->pathUrl, 0, 1) == '/' ? str_replace('/','',$this->pathUrl) : $this->pathUrl;
        }
        
        $data = Menus::with(['permissions' => function($table) use ($access) {
            $table->where([
                    'is_active' => true,
                    'role_id' => Auth::user()->role_id,
                    "$access" => true
                ]
            );
        }])->where('url', $url)->first();
        
        return $data;
    }

    public function menuPermissions()
    {
        $main_menu = Menus::select('menus.*','menu_permissions.view','menu_permissions.role_id','menu_permissions.menu_id')->join('menu_permissions', 'menu_permissions.menu_id', '=', 'menus.id')->where([
            'menus.is_parent' => true,
            'menus.is_active' => true,
            'menu_permissions.is_active' => true,
            'menu_permissions.role_id' => 1,
            // 'menu_permissions.role_id' => Auth::user()->role_id,
            'menu_permissions.view' => true
        ])->orderBy('menus.position', 'ASC')->get();

        $result = array();
        foreach($main_menu as $main_menus) {
            $sub_menu = Menus::select('menus.*','menu_permissions.view','menu_permissions.role_id','menu_permissions.menu_id')->join('menu_permissions', 'menu_permissions.menu_id', '=', 'menus.id')->where([
                'menus.is_parent' => false,
                'menus.is_active' => true,
                'menu_permissions.is_active' => true,
                'menu_permissions.role_id' => 1,
                // 'menu_permissions.role_id' => Auth::user()->role_id,
                'menu_permissions.view' => true,
                'menus.parent_menu_id' => $main_menus['id'],
            ])->orderBy('menus.position', 'ASC')->get();
            
            $result_menu = array(
                'main_menu' => array(
                    'id' => $main_menus['id'],
                    'title' => $main_menus['title'],
                    'url' => $main_menus['url'],
                    'url_group' => $main_menus['url_group'],
                    'icon' => $main_menus['icon'],
                    'sub_menu' => $sub_menu
                )
            );
            array_push($result, $result_menu);
        }
        
        return $result;
    }
}
