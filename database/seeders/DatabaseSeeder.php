<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => env('KELASQU_ADMIN_EMAIL', 'admin@kelasqu.test')],
            [
                'name' => 'Administrator KelasQu',
                'email_verified_at' => now(),
                'password' => env('KELASQU_ADMIN_PASSWORD', 'KelasQuAdmin2026!'),
                'role' => 'admin',
            ]
        );
    }
}
