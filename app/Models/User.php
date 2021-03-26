<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    public function stories()
    {
        return $this->hasMany(Story::class)->latest('updated_at');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function subscribers()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function path()
    {
        return "/dashboard/{$this->id}";
    }

    public function getUserAvatar()
    {
        return $this->avatar ? ($this->avatar->image_type === 'upload' ?
            $this->avatar->image_upload :
            $this->avatar->image_url) :
            "/img/avatar-placeholder.png";
    }
}
