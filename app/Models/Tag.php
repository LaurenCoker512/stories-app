<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This class is a model for tags, with associated relationships and methods.
 */
class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    /**
     * This method establishes a many-to-many relationship with Stories.
     * 
     * @return Collection
     */
    public function stories()
    {
        return $this->belongsToMany(Story::class);
    }

    /**
     * This method returns the URI for the show page for a given tag.
     * 
     * @return string
     */
    public function path()
    {
        return "/tags/{$this->id}";
    }
}
