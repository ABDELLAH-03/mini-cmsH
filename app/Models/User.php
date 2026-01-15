<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
   
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isEditor()
    {
        return in_array($this->role, ['admin', 'editor']);
    }
    public function sites()
    {
        return $this->hasMany(Site::class);
    }
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
    public function media()
    {
        return $this->hasMany(Media::class);
    }

 
}