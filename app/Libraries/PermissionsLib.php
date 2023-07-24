<?php

namespace App\Libraries;

use App\Models\Permissions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PermissionsLib
{
    public static function assignRoleToMenuAccess($menu_id, array $roles, array $view_access, array $add_access, array $edit_access, array $delete_access, $action = 'add')
    {
        $role_access_menu = array();
        if(count($roles) > 0) {
            for ($i = 0; $i < count($roles); $i++) {
                $arr_role_access = array(
                    'role_id' => $roles[$i],
                    'menu_id' => $menu_id,
                    'view' => $view_access[$i] == "1" ? 1 : 0,
                    'add' => $add_access[$i] == "1" ? 1 : 0,
                    'edit' => $edit_access[$i] == "1" ? 1 : 0,
                    'delete' => $delete_access[$i] == "1" ? 1 : 0,
                    'is_active' => true
                );
                if($action == 'add') {
                    $arr_role_access['created_by'] = Auth::user()->id;
                } else {
                    $arr_role_access['created_by'] = Auth::user()->id;
                    $arr_role_access['updated_at'] = Carbon::now();
                    $arr_role_access['updated_by'] = Auth::user()->id;
                }

                array_push($role_access_menu, $arr_role_access);
            }
        }

        if(count($role_access_menu) > 0) {
            if(Permissions::insert($role_access_menu)) {
                return true;
            }
            return false;
        }
        return true;             
    }

    public static function destoryRoleMenuAccess($menu_id)
    {
        $check_menu_permissions = Permissions::where('menu_id', $menu_id)->get();
        if(count($check_menu_permissions) > 0) {
            if(Permissions::where('menu_id', $menu_id)->delete()) {
                return true;
            }
            return false;
        }
        return true;
    }
}
