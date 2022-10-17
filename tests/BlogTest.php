<?php

namespace App\Tests;

class BlogTest extends BaseTest
{
    public function testCreateUser()
    {
        $user = new \App\Models\User();
        $user->email = $this->faker->email;
        $user->first_name = $this->faker->firstName;
        $user->last_name = $this->faker->lastName;
        $user->password = 'lala';
        $user->save();
        $this->assertNotNull($user->id);
    }

    public function testCreatePost()
    {
        $post = new \App\Models\Post();
        $post->title = $this->faker->sentence;
        $post->body = $this->faker->text;
        $post->creator()->associate(\App\Models\User::first());
        $post->save();
        $this->assertNotNull($post->creator);
    }
}
