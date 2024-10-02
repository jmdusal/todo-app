<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'usertype' => 'admin',
        ]);

        // Create a regular user
        User::create([
            'username' => 'user',
            'password' => bcrypt(value: 'password'),
            'usertype' => 'user',
        ]);

    }
}
