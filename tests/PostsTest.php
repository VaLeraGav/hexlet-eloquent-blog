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
}