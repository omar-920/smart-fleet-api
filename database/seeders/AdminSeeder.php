<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@smartfleet.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@smartfleet.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ]);
        }
    }
}
