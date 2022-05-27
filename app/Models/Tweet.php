<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tweets';

    protected $fillable = [
        'user_id',
        'content',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'likes', 'tweet_id', 'user_id');
    }

}
