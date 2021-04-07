<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This class is a model for subscriptions, with associated relationships and methods.
 */
class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id'];

    /**
     * This method establishes a polymorphic relationship with either a Story
     * or a User.
     * 
     * @return Collection
     */
    public function subscribable()
    {
        return $this->morphTo();
    }

    /**
     * This method establishes a one-to-many-relationship with a User.
     * 
     * @return Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
