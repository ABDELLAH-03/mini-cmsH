<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TemplateSeeder::class,
            // ... other seeders
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@minicms.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@minicms.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        $this->command->info('Test users created!');
        $this->command->info('Admin: admin@minicms.com / password');
        $this->command->info('User: user@minicms.com / password');
    }
}