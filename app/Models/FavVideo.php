<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavVideo extends Model
{
    protected $table = 'fav_video';

    protected $fillable = [
        'user_id',
        'video_id',
        'titel',
        'thumbnail',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
