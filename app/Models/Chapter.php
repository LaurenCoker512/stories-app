<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['body'];

    protected $touches = ['story'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function path()
    {
        return "/stories/{$this->story->id}/chapters/{$this->id}";
    }
}
