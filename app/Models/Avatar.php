<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This class is a model for avatars, uploaded as a file or a URL.
 */
class Avatar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_type',
        'image_upload',
        'image_url',
        'user_id'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * This method establishes a one-to-one relationship with a User.
     * 
     * @return Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
