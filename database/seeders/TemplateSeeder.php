<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // System templates (available to all users)
        $systemTemplates = [
            [
                'name' => 'Modern Hero',
                'type' => 'hero',
                'category' => 'general',
                'visibility' => 'system',
                'content' => [
                    'title' => 'Welcome to Our Website',
                    'subtitle' => 'Creating amazing digital experiences',
                    'button_text' => 'Get Started',
                    'button_link' => '/contact'
                ],
                'preview_data' => [
                    'html' => '<div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg">
                        <h3 class="text-xl font-bold">Modern Hero</h3>
                        <p class="mt-2">Clean, modern hero section</p>
                    </div>',
                    'colors' => ['primary' => '#3B82F6', 'secondary' => '#8B5CF6']
                ]
            ],
            [
                'name' => 'Business Features',
                'type' => 'features',
                'category' => 'business',
                'visibility' => 'system',
                'content' => [
                    'items' => [
                        ['title' => 'Professional Design', 'description' => 'Clean, modern design that converts visitors'],
                        ['title' => 'Mobile Friendly', 'description' => 'Works perfectly on all devices and screen sizes'],
                        ['title' => 'Fast Performance', 'description' => 'Optimized for speed and user experience']
                    ]
                ],
                'preview_data' => [
                    'html' => '<div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold">Business Features</h3>
                        <div class="grid grid-cols-1 gap-2 mt-2">
                            <div class="p-2 bg-white rounded">3 Features</div>
                        </div>
                    </div>',
                    'colors' => ['primary' => '#10B981', 'secondary' => '#3B82F6']
                ]
            ],
            [
                'name' => 'Blog Content',
                'type' => 'content',
                'category' => 'blog',
                'visibility' => 'system',
                'content' => [
                    'html' => '<h2>Engaging Content</h2><p>Start writing your blog post here. You can add headings, paragraphs, images, and more.</p>'
                ],
                'preview_data' => [
                    'html' => '<div class="p-4 bg-white border rounded-lg">
                        <h3 class="text-lg font-semibold">Blog Content</h3>
                        <p class="mt-2 text-gray-600">Rich text content area</p>
                    </div>',
                    'colors' => ['primary' => '#F59E0B', 'secondary' => '#EF4444']
                ]
            ],
            [
                'name' => 'Business Homepage',
                'type' => 'full_page',
                'category' => 'business',
                'visibility' => 'system',
                'content' => [
                    'sections' => [
                        [
                            'id' => 'hero_1',
                            'type' => 'hero',
                            'content' => [
                                'title' => 'Your Business Solution',
                                'subtitle' => 'Professional services for modern businesses',
                                'button_text' => 'Learn More',
                                'button_link' => '#'
                            ],
                            'settings' => ['background' => '#f8fafc', 'textAlign' => 'center']
                        ],
                        [
                            'id' => 'content_1',
                            'type' => 'content',
                            'content' => [
                                'html' => '<h2>About Our Services</h2><p>We provide professional solutions for businesses of all sizes.</p>'
                            ],
                            'settings' => ['background' => '#ffffff']
                        ]
                    ]
                ],
                'preview_data' => [
                    'html' => '<div class="p-6 bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg border">
                        <h3 class="text-lg font-semibold">Business Homepage</h3>
                        <p class="mt-2 text-gray-600">Complete homepage layout</p>
                    </div>',
                    'colors' => ['primary' => '#3B82F6', 'secondary' => '#8B5CF6']
                ]
            ]
        ];

        foreach ($systemTemplates as $templateData) {
            Template::create($templateData);
        }

        $this->command->info('System templates created successfully!');
    }
}