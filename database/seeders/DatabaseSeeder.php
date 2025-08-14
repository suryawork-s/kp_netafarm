<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PositionsSeeder::class,
        ]);

        $users = [
            // [
            //     'position_id' => 1,
            //     'department_id' => 1,
            //     'name' => 'Super Admin',
            //     'username' => 'superadmin',
            //     'email' => 'superadmin@example.com',
            //     'phone' => '089662164536',
            //     'join_year' => '2024',
            //     'password' => bcrypt('password'),
            //     'status' => 'active'
            // ],
            // [
            //     'position_id' => 1,
            //     'department_id' => 1,
            //     'name' => 'Staff',
            //     'username' => 'staff',
            //     'email' => 'staff@example.com',
            //     'phone' => '089662164536',
            //     'join_year' => '2024',
            //     'password' => bcrypt('password'),
            //     'status' => 'active'
            // ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class
        ]);
    }
}
