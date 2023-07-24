<?php

namespace Database\Seeders;

use App\Models\Settings\Menus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('username', 'admin')->first();
        $data_parent = array(
            array(
                'title' => 'Dashboard',
                'is_parent' => true,
                'url' => 'dashboard',
                'url_group' => 'dashboard',
                'icon' => 'fa fa-home',
                'position' => 1,
                'is_active' => true,
                'created_by' => $user->id
            ),
            array(
                'title' => 'Pengaturan',
                'is_parent' => true,
                'url' => '#',
                'url_group' => 'settings',
                'icon' => 'fa fa-cogs',
                'position' => 2,
                'is_active' => true,
                'created_by' => $user->id
            )
        );
        DB::table('menus')->insert($data_parent); // parent
        
        $get_pengaturan = Menus::where('title', 'pengaturan')->first();
        $data_submenu = array(
            array(
                'title' => 'Pengguna',
                'is_parent' => false,
                'parent_menu_id' => $get_pengaturan->id,
                'url' => 'settings/users',
                'position' => 1,
                'is_active' => true,
                'created_by' => $user->id
            ),
            array(
                'title' => 'Menu',
                'is_parent' => false,
                'parent_menu_id' => $get_pengaturan->id,
                'url' => 'settings/menus',
                'position' => 2,
                'is_active' => true,
                'created_by' => $user->id
            ),
            array(
                'title' => 'Peran',
                'is_parent' => false,
                'parent_menu_id' => $get_pengaturan->id,
                'url' => 'settings/roles',
                'position' => 3,
                'is_active' => true,
                'created_by' => $user->id
            )
        );
        DB::table('menus')->insert($data_submenu); // parent
    }
}
