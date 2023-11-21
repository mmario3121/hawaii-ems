<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'name' => 'Толеби Жаксыбай',
            'email' => 'tolebizaksybaj@gmail.com',
            'password' => '1234',
            'email_verified_at' => now(),
        ])->assignRole(['developer']);

        User::create([
            'name' => 'admin',
            'email' => 'admin@hawaii.kz',
            'password' => 'admin',
            'email_verified_at' => now(),
        ])->assignRole(['admin']);

        User::create([
            'name' => 'hr',
            'email' => 'hr@hawaii.kz',
            'password' => 'hr',
            'email_verified_at' => now(),
        ])->assignRole(['hr']);

        User::create([
            'name' => 'manager',
            'email' => 'manager@hawaii.kz',
            'password' => 'manager',
            'email_verified_at' => now(),
        ])->assignRole(['manager']);

        User::create([
            'name' => 'treasurer',
            'email' => 'treasurer@hawaii.kz',
            'password' => 'treasurer',
            'email_verified_at' => now(),
        ])->assignRole(['treasurer']);
    }
}
