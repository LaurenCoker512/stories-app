<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id'
    ];

    protected $guarded = [
        'id',
        'chapter_id',
        'created_at',
        'updated_at'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function path()
    {
        return "/stories/{$this->chapter->story->id}/chapters/{$this->chapter->getNumber()}/comments/{$this->id}";
    }
}
