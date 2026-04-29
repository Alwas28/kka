<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@umkendari.ac.id'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@umkendari.ac.id',
                'password' => Hash::make('password'),
            ]
        );

        $adminRole = DB::table('roles')->where('nama', 'Administrator')->value('id');

        if ($adminRole && !DB::table('user_role')->where('user_id', $user->id)->where('role_id', $adminRole)->exists()) {
            DB::table('user_role')->insert([
                'user_id' => $user->id,
                'role_id' => $adminRole,
            ]);
        }
    }
}
