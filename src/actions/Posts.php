<?php

namespace App\actions;

class Posts
{
    public static function create($user, $params)
    {
        // BEGIN (write your solution here)
        $post = $user->posts()->make($params);
        $post->save();

        return $post;
        // END
    }

    public static function createLike($user, $post)
    {
        // BEGIN (write your solution here)
        $like = $post->likes()->make();
        $like->creator()->associate($user);
        $like->save();
        return $like;
        // END
    }
}
