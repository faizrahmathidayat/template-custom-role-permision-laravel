<?php

namespace Database\Seeders;

use App\Models\Settings\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Roles::where('name', 'ADMINISTRATOR')->first();
        DB::table('users')->insert([
            'role_id' => $role->id,
            'username' => 'admin',
            'password' => Hash::make('12345678'),
            'name' => 'ADMIN',
            'is_active' => true,
            'created_by' => 1
        ]);
    }
}
