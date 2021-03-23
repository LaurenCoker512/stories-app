<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Events\StoryCreated;
use App\Events\StoryUpdated;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'user_id'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function path()
    {
        return "/stories/{$this->id}";
    }

    public function firstChapterPath()
    {
        return "/stories/{$this->id}/chapters/1";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function subscribers()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    public function addChapter($name, $body)
    {
        $chapter = $this->chapters()->create(compact('name', 'body'));

        if ($this->chapters->count() > 1) {
            StoryUpdated::dispatch($this);
        } else {
            StoryCreated::dispatch($this);
        }

        return $chapter;
    }

    public function getChapterByNumber($num)
    {
        $allChapters = $this->chapters->sortBy('created_at');

        foreach($allChapters as $i=>$chapter) {
            if ($i + 1 === $num) {
                return $chapter;
            }
        }
    }

    public function updateTags($tags)
    {
        return $this->tags()->sync($tags);
    }

    public function scopeFiction($query)
    {
        return $query->where('type', 'fiction');
    }

    public function scopeNonfiction($query)
    {
        return $query->where('type', 'nonfiction');
    }

    public function scopePoetry($query)
    {
        return $query->where('type', 'poetry');
    }
}
