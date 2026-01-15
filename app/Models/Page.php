<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'parent_id',
        'title',
        'slug',
        'content',
        'seo',
        'is_homepage',
        'order',
        'published_at'
    ];

    protected $casts = [
        'content' => 'array',
        'seo' => 'array',
        'published_at' => 'datetime',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function getUrlAttribute()
    {
        if ($this->is_homepage) {
            return url('/');
        }
        return url('/' . $this->slug);
    }
}