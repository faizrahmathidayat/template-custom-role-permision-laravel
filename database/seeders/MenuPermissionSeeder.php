<?php

namespace Database\Seeders;

use App\Models\Settings\Menus;
use App\Models\Settings\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('username', 'admin')->first();
        $get_menu = Menus::all();
        $get_role = Roles::where('name','ADMINISTRATOR')->first();
        
        if(!empty($get_role)) {
            $data = array();
            foreach($get_menu as $menus) {
                $arr_data = array(
                    'role_id' => $get_role->id,
                    'menu_id' => $menus['id'],
                    'view' => 1,
                    'add' => 1,
                    'edit' => 1,
                    'delete' => 1,
                    'is_active' => 1,
                    'created_by' => $user->id
                );
                array_push($data, $arr_data);
            }
            DB::table('menu_permissions')->insert($data);
        }        
    }
}
