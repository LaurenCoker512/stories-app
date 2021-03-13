<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
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

    public function addChapter($body)
    {
        return $this->chapters()->create(compact('body'));
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
}
