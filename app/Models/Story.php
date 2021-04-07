<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\StoryCreated;
use App\Events\StoryUpdated;

use App\Facades\StoryChapterFacade;

/**
 * This class is a model for stories, with associated relationships and methods.
 */
class Story extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'user_id'
    ];

    /**
     * This method returns the URI for the show page for a given story.
     * 
     * @return string
     */
    public function path()
    {
        return "/stories/{$this->id}";
    }

    /**
     * This method returns the URI for the show page of the first chapter of
     * a given story.
     * 
     * @return string
     */
    public function firstChapterPath()
    {
        return "/stories/{$this->id}/chapters/1";
    }

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
     * This method establishes a one-to-many-relationship with Chapters.
     * 
     * @return Collection
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * This method establishes a many-to-many relationship with Tags.
     * 
     * @return Collection
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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
     * This method adds a new chapter to a given story.
     * 
     * @return Collection
     */
    public function addChapter($name, $body)
    {
        $chapter = $this->chapters()->create(compact('name', 'body'));

        if ($this->chapters->count() > 1) {
            StoryUpdated::dispatch(
                $this, 
                $chapter->name ?? "Chapter {$chapter->getNumber()}", 
                StoryChapterFacade::getChapterNumFromId($chapter->id
            ));
        } else {
            StoryCreated::dispatch($this);
        }

        return $chapter;
    }

    // public function getChapterByNumber($num)
    // {
    //     $allChapters = $this->chapters->sortBy('created_at');

    //     foreach($allChapters as $i=>$chapter) {
    //         if ($i + 1 === $num) {
    //             return $chapter;
    //         }
    //     }
    // }

    /**
     * This method updates the tags of a given story.
     * 
     * @return Collection
     */
    public function updateTags($tags)
    {
        return $this->tags()->sync($tags);
    }

    /**
     * This method scopes a story query to only include stories of type fiction.
     * 
     * @return query
     */
    public function scopeFiction($query)
    {
        return $query->where('type', 'fiction');
    }

    /**
     * This method scopes a story query to only include stories of type nonfiction.
     * 
     * @return query
     */
    public function scopeNonfiction($query)
    {
        return $query->where('type', 'nonfiction');
    }

    /**
     * This method scopes a story query to only include stories of type poetry.
     * 
     * @return query
     */
    public function scopePoetry($query)
    {
        return $query->where('type', 'poetry');
    }
}
