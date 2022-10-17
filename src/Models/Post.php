<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'body'];

    public function creator()
    {
        // belongsTo определяется у модели содержащей внешний ключ
        return $this->belongsTo(__NAMESPACE__ . '\User', 'creator_id');
    }

    public function comments()
    {
        return $this->hasMany(__NAMESPACE__ . '\PostComment');
    }

    public function tags()
    {
        return $this->hasMany(__NAMESPACE__ . '\Tag');
    }

    // связь поста с лайками
    public function likes()
    {
        // hasMany определяется у модели, имеющей внешние ключи в других таблицах
        return $this->hasMany(__NAMESPACE__ . '\PostLike', 'post_id');
    }

    //
    public function scopeActive($query)
    {
        return $query->where('state', 'published');
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('likes_count', 'desc')->limit($limit);
    }
}
