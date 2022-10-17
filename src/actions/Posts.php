<?php

namespace App\actions;

use App\Models\Post;

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

    /*
     * index($user, $limit), which returns a list of posts with an added mark
     * about whether the current user liked this post or not.
     * [
     *    ['post' => [...], 'liked' => true],
     *    ['post' => [...], 'liked' => false],
     *    ['post' => [...], 'liked' => true],
     * ]
     */
    public static function index($user, $limit)
    {
        // BEGIN (write your solution here)
        $posts = Post::limit($limit)->orderBy('created_at', 'desc')->get();
        $postIds = $posts->pluck('id');
        $likedPostIds = $user->postLikes()->whereIn('post_id', $postIds)->pluck('post_id');

        $result = $posts->map(function ($post) use ($likedPostIds) {
            return [
                'post' => $post->toArray(),
                'liked' => $likedPostIds->contains($post->id)
            ];
        });
        return $result;
        // END
    }

    /*
     * returns a list of the most popular (most likes) published posts, working with scope
     */
    public static function indexPopular($user, $limit)
    {
        return $user->posts()->published()->likesLimit($limit);
    }
}
