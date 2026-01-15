<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id',
        'filename',
        'path',
        'type',
        'alt_text',
        'mime_type',
        'size'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->type === 'image') {
            // You can use intervention/image to create thumbnails
            $path = str_replace('.', '-thumb.', $this->path);
            return asset('storage/' . $path);
        }
        return $this->url;
    }
}