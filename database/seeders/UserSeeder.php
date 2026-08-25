<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'User Staff',
            'username' => 'staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '081234567892',
            'email_verified_at' => now(),
            'gender' => 'female',
        ]);


        $admin = User::factory()->create([
            'name' => 'Mario Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'gender' => 'male',
        ]);

        $customer = User::factory()->create([
            'name' => 'User Customer',
            'username' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567893',
            'email_verified_at' => now(),
            'gender' => 'male',
        ]);
    }
}
