<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id',
        'name',
        'type',
        'thumbnail',
        'content',
        'preview_data',
        'category',
        'visibility',
        'usage_count'
    ];

    protected $casts = [
        'content' => 'array',
        'preview_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopePrivate($query, $userId)
    {
        return $query->where('user_id', $userId)->where('visibility', 'private');
    }

    public function scopeSystem($query)
    {
        return $query->where('visibility', 'system');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function getPreviewHtmlAttribute()
    {
        if ($this->preview_data && isset($this->preview_data['html'])) {
            return $this->preview_data['html'];
        }

        // Generate preview based on type
        $previews = [
            'hero' => '<div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg">
                <h3 class="text-xl font-bold">Hero Section</h3>
                <p class="mt-2">Large header with title and CTA button</p>
            </div>',
            'content' => '<div class="p-4 bg-white border rounded-lg">
                <h3 class="text-lg font-semibold">Content Section</h3>
                <p class="mt-2 text-gray-600">Rich text content area</p>
            </div>',
            'features' => '<div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold">Features Grid</h3>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <div class="p-2 bg-white rounded">Feature 1</div>
                    <div class="p-2 bg-white rounded">Feature 2</div>
                </div>
            </div>',
            'full_page' => '<div class="p-4 bg-gradient-to-br from-green-50 to-blue-50 rounded-lg border">
                <h3 class="text-lg font-semibold">Full Page Template</h3>
                <p class="mt-2 text-gray-600">Complete page layout</p>
            </div>'
        ];

        return $previews[$this->type] ?? '<div class="p-4 bg-gray-100 rounded-lg">Template Preview</div>';
    }
}