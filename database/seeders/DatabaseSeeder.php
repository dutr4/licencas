<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run()
{
    User::create([
        'name' => 'Administrador',
        'email' => 'admin@email.com',
        'password' => Hash::make('admin54321'),
        'role' => 'admin',
    ]);

}
}
