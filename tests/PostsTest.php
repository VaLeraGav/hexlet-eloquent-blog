<?php

namespace App\Tests;

use App\actions\Posts;
use App\Models\PostLike;
use App\Models\User;
use App\Models\Post;


class PostsTest extends BaseTest
{
    private $user;
    private $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->factory->create(User::class);
        $this->post = $this->factory->create(Post::class);

        $this->params = [
            'title' => $this->faker->text,
            'body' => $this->faker->text
        ];


        $this->user1 = $this->factory->create(User::class);
        $this->user2 = $this->factory->create(User::class);

        $this->post1 = $this->factory->create(Post::class);
        $this->post2 = $this->factory->create(Post::class);
        $this->post3 = $this->factory->create(Post::class);
        $this->post4 = $this->factory->create(Post::class);

        PostLike::create([
            'creator_id' => $this->user1->id,
            'post_id' => $this->post1->id,
        ]);

        PostLike::create([
            'creator_id' => $this->user1->id,
            'post_id' => $this->post2->id,
        ]);

        PostLike::create([
            'creator_id' => $this->user2->id,
            'post_id' => $this->post4->id,
        ]);
    }

    public function testCreate()
    {
        $post = Posts::create($this->user, $this->params);
        $this->assertEquals($this->user->toArray(), $post->creator->toArray());
    }

    public function testPostLike()
    {
        $like = Posts::createLike($this->user, $this->post);
        $this->assertEquals($this->user->toArray(), $like->creator->toArray());
        $this->assertEquals($this->post->toArray(), $like->post->toArray());
    }

    public function testSaveToDataBase()
    {
        $post = Posts::create($this->user, $this->params);
        $expected = collect($post)->except('creator')->toArray();
        $actual = collect(Post::find($post->id)->toArray())
            ->except('state')->toArray();
        $this->assertEquals($expected, $actual);

        $like = Posts::createLike($this->user, $this->post);
        $expected2 = collect($like->toArray())->except('creator', 'post')->toArray();
        $actual2 = PostLike::find($like->id)->toArray();
        $this->assertEquals($expected2, $actual2);
    }

// ! сбит порядок постов и индексов
//    public function testIndex()
//    {
//        $items = Posts::index($this->user1, 4);
//        $expected = [
//            ['post' => $this->post1->toArray(), 'liked' => true],
//            ['post' => $this->post2->toArray(), 'liked' => true],
//            ['post' => $this->post3->toArray(), 'liked' => false],
//            ['post' => $this->post4->toArray(), 'liked' => false],
//        ];
//        $this->assertEquals($expected, $items->toArray());
//    }
//
//    public function testIndex2()
//    {
//        $items = Posts::index($this->user1, 1);
//        $expected = [
//            ['post' => $this->post1->toArray(), 'liked' => true],
//        ];
//        $this->assertEquals($expected, $items->toArray());
//    }
//
//    public function testIndex3()
//    {
//        $items = Posts::index($this->user2, 4);
//        $expected = [
//            ['post' => $this->post1->toArray(), 'liked' => false],
//            ['post' => $this->post2->toArray(), 'liked' => false],
//            ['post' => $this->post3->toArray(), 'liked' => false],
//            ['post' => $this->post4->toArray(), 'liked' => true],
//        ];
//        $this->assertEquals($expected, $items->toArray());
//    }

//    public function testIndexPopular()
//    {
//        $user = $this->factory->create(User::class);
//        $postsLike = collect([
//            Post::create(),
//            Post::create(),
//            Post::create(),
//            Post::create()
//        ]);
//
//        $posts = Posts::indexPopular($user, 2);
//        $postsIds = $posts->pluck('id');
//        $expected = $postsLike->where('state', 'published')
//            ->sortByDesc('likes_count')->take(2)->pluck('id');
//        $this->assertEquals($expected, $postsIds);
//    }
}