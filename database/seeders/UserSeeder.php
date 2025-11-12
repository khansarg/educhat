<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // admin/demo user
        User::updateOrCreate(
            ['email' => 'akulalala@gmail.com'],
            ['name' => 'Aku Ulala', 'password' => Hash::make('secretTalon123')]
        );
    }
}
