<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Template;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@minicms.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@minicms.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // Create default templates
        $templates = [
            [
                'name' => 'Modern Hero',
                'type' => 'hero',
                'content' => [
                    'title' => 'Welcome to Our Website',
                    'subtitle' => 'Creating amazing digital experiences',
                    'button_text' => 'Get Started',
                    'button_link' => '#'
                ],
                'is_public' => true,
                'category' => 'business'
            ],
            [
                'name' => 'Features Grid',
                'type' => 'features',
                'content' => [
                    'items' => [
                        ['title' => 'Fast Performance', 'description' => 'Lightning fast loading times'],
                        ['title' => 'Mobile Friendly', 'description' => 'Works perfectly on all devices'],
                        ['title' => 'Easy to Use', 'description' => 'Intuitive interface for everyone']
                    ]
                ],
                'is_public' => true,
                'category' => 'business'
            ],
            [
                'name' => 'Contact Form',
                'type' => 'contact',
                'content' => [
                    'title' => 'Get in Touch',
                    'description' => 'Send us a message and we\'ll respond as soon as possible'
                ],
                'is_public' => true,
                'category' => 'business'
            ]
        ];

        foreach ($templates as $template) {
            Template::create(array_merge($template, ['user_id' => $admin->id]));
        }

        $this->command->info('Default data seeded successfully!');
        $this->command->info('Admin Login: admin@minicms.com / password');
        $this->command->info('User Login: user@minicms.com / password');
    }
}