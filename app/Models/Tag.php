<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function stories()
    {
        return $this->belongsToMany(Story::class);
    }

    public function path()
    {
        return "/tags/{$this->id}";
    }
}
