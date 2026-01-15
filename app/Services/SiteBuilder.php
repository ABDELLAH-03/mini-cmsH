<?php

namespace App\Services;

use App\Models\Site;
use App\Models\Page;
use App\Models\Template;

class SiteBuilder
{
    /**
     * Create a new class instance.
     */
    public static function createDefaultSite($user, $data)
    {
        $site = $user->sites()->create([
            'name' => $data['name'],
            'subdomain' => $data['subdomain'],
            'settings' => [
                'primary_color' => '#3B82F6',
                'font_family' => 'Inter',
                'container_width' => '1200px',
            ]
        ]);

        // Create homepage
        $homepage = $site->pages()->create([
            'title' => 'Home',
            'slug' => 'home',
            'is_homepage' => true,
            'content' => self::getDefaultContent(),
            'seo' => [
                'title' => 'Welcome to ' . $site->name,
                'description' => 'A website created with Mini CMS'
            ]
        ]);

        // Apply default templates
        $defaultTemplates = Template::public()->limit(3)->get();
        $site->templates()->sync($defaultTemplates->pluck('id'));

        return $site;
    }

    private static function getDefaultContent()
    {
        return [
            'sections' => [
                [
                    'id' => 'hero_' . uniqid(),
                    'type' => 'hero',
                    'content' => [
                        'title' => 'Welcome to Your New Website',
                        'subtitle' => 'Start editing this section to make it your own',
                        'button_text' => 'Get Started',
                        'button_link' => '/about'
                    ],
                    'settings' => [
                        'background' => '#f8fafc',
                        'text_align' => 'center'
                    ]
                ],
                [
                    'id' => 'content_' . uniqid(),
                    'type' => 'content',
                    'content' => [
                        'html' => '<p>This is your first content section. Edit it to add your own content.</p>'
                    ]
                ]
            ]
        ];
    }
}