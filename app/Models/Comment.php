<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This class is a model for comments, with associated relationships and methods.
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * This method establishes a one-to-many-relationship with a User.
     * 
     * @return Collection
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * This method establishes a one-to-many-relationship with a Chapter.
     * 
     * @return Collection
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * This method returns the URI for a given comment.
     * 
     * @return Collection
     */
    public function path()
    {
        return "/stories/{$this->chapter->story->id}/chapters/{$this->chapter->getNumber()}/comments/{$this->id}";
    }
}
