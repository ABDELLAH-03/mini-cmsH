<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'subdomain',
        'custom_domain',
        'settings',
        'status'
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'site_template');
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function homepage()
    {
        return $this->pages()->where('is_homepage', true)->first();
    }
}