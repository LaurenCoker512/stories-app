<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * This class is a model for users, with associated relationships and methods.
 */
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

    /**
     * This method establishes a one-to-one relationship with a User.
     * 
     * @return Collection
     */
    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    /**
     * This method establishes a one-to-many-relationship with Stories.
     * 
     * @return Collection
     */
    public function stories()
    {
        return $this->hasMany(Story::class)->latest('updated_at');
    }

    /**
     * This method establishes a one-to-many-relationship with Comments.
     * 
     * @return Collection
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * This method establishes a polymorphic one-to-many-relationship with
     * Subscriptions.
     * 
     * @return Collection
     */
    public function subscribers()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    /**
     * This method establishes a one-to-many-relationship with Subscriptions.
     * 
     * @return Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * This method returns the URI for a given user's dashboard.
     * 
     * @return string
     */
    public function path()
    {
        return "/dashboard/{$this->id}";
    }

    /**
     * This method gets the avatar of a given user or returns the placeholder
     * avatar.
     * 
     * @return string
     */
    public function getUserAvatar()
    {
        return $this->avatar ? ($this->avatar->image_type === 'upload' ?
            $this->avatar->image_upload :
            $this->avatar->image_url) :
            "/img/avatar-placeholder.png";
    }
}
