<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_type',
        'image_upload',
        'image_url',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
