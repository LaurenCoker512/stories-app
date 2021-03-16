<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['body'];

    protected $guarded = ['id', 'title', 'created_at', 'updated_at'];

    protected $touches = ['story'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getNumber()
    {
        $allChapters = $this->story->chapters->sortBy('created_at');

        foreach($allChapters as $i=>$chapter) {
            if ($chapter->id === $this->id) {
                return $i + 1;
            }
        }
    }

    public function path()
    {
        return "/stories/{$this->story->id}/chapters/{$this->getNumber()}";
    }

    public function addComment($body, $userId)
    {
        return $this->comments()->create([
            'body' => $body,
            'user_id' => $userId
        ]);
    }
}
