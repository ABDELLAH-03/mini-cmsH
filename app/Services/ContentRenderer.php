<?php

namespace App\Services;

class ContentRenderer
{
    public static function renderSection($section)
    {
        $type = $section['type'] ?? 'content';
        $content = $section['content'] ?? [];
        $settings = $section['settings'] ?? [];

        $view = "components.sections.{$type}";

        if (!view()->exists($view)) {
            $view = 'components.sections.content';
        }

        return view($view, [
            'content' => $content,
            'settings' => $settings,
            'section' => $section
        ])->render();
    }

    public static function renderPage($page)
    {
        $sections = $page->content['sections'] ?? [];
        $html = '';

        foreach ($sections as $section) {
            $html .= self::renderSection($section);
        }

        return $html;
    }

    public static function generatePreview($content)
    {
        // Generate HTML preview for editor
        $sections = $content['sections'] ?? [];
        $preview = '';

        foreach (array_slice($sections, 0, 3) as $section) {
            $type = $section['type'];
            $title = $section['content']['title'] ?? ucfirst($type);
            $preview .= "<div class='section-preview'>{$title}</div>";
        }

        return $preview;
    }
}