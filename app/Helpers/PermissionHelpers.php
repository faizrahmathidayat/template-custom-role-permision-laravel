<?php

function initPermissions()
{
    return new App\Helpers\GlobalHelpers;
}

if (!function_exists('can')) {
    function can($access = 'view')
    {
        $data = initPermissions()->accessPermissions($access);
        if(!empty($data)) {
            if(count($data['permissions']) > 0) {
                if(isset($data['permissions'][0][$access]) && $data['permissions'][0][$access]) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
        
    }
}

if (!function_exists('menu')) {
    function menu()
    {
        $data = initPermissions()->menuPermissions();
        return $data;
    }
}

