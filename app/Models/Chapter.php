<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This class is a model for chapters with associated relationships and methods.
 */
class Chapter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'body'
    ];

    protected $touches = ['story'];

    /**
     * This method establishes a one-to-many-relationship with a Story.
     * 
     * @return Collection
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
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
     * This method calculates the number of a given chapter within a story.
     * 
     * @return int
     */
    public function getNumber()
    {
        $allChapters = $this->story->chapters->sortBy('created_at');

        foreach($allChapters as $i=>$chapter) {
            if ($chapter->id === $this->id) {
                return $i + 1;
            }
        }
    }

    /**
     * This method returns the URI for the show page for a given chapter.
     * 
     * @return string
     */
    public function path()
    {
        return "/stories/{$this->story->id}/chapters/{$this->getNumber()}";
    }

    /**
     * This method adds a new comment to the given chapter.
     * 
     * @return Collection
     */
    public function addComment($body, $userId)
    {
        return $this->comments()->create([
            'body' => $body,
            'user_id' => $userId
        ]);
    }
}
