<?php

namespace App\Tests;

use App\actions\Users;
use App\Models\User;

class UsersTest extends BaseTest
{
    private $user1;
    private $user2;
    private $user3;

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->create(User::class);
        $this->factory->create(User::class);

        $this->user1 = $this->factory->create(User::class);
        $this->user2 = $this->factory->create(User::class);
        $this->user3 = $this->factory->create(User::class);
    }

    public function testIndex()
    {
        $users = Users::index();
//      print_r($users->toArray());
        $this->assertCount(6, $users);
    }

    public function testCreate()
    {
        $params = $this->factory->make(User::class)->toArray();
        $expected = collect($params)->except('password')->toArray();
        $user = Users::create($params);
        $actual = collect($user->toArray())
            ->except('password', 'created_at', 'updated_at', 'id')
            ->toArray();
        $this->assertEquals($expected, $actual);
    }

    public function testUpdate()
    {
        $user = $this->factory->create(User::class);
        $params = $this->factory->make(User::class)->toArray();
        $expected = collect($params)->except('password')->toArray();
        $user = Users::update($user->id, $params);
        $actual = collect($user->toArray())
            ->except('password', 'created_at', 'updated_at', 'id')
            ->toArray();
        $this->assertEquals($expected, $actual);
    }

    public function testDelete()
    {
        $user = $this->factory->create(User::class);
        $result = Users::delete($user->id);
        $this->assertTrue($result);

        $result2 = Users::delete($user->id);
        $this->assertFalse($result2);

        $user2 = User::find($user->id);
        $this->assertNull($user2);
    }

    public function testQuery()
    {
        $result = Users::indexQuery([]);
        $this->assertCount(6, $result);

        $params = [
            'q' => [
                'email' => $this->user1->email
            ]
        ];

        $result = Users::indexQuery($params);
        $this->assertCount(1, $result);

        $params = [
            'q' => [
                'email' => $this->user1->email,
                'first_name' => $this->user2->first_name
            ]
        ];

        $result = Users::indexQuery($params);
        $this->assertCount(2, $result);

        $params = [
            's' => 'id:asc'
        ];

        $result = Users::indexQuery($params);
        $this->assertCount(6, $result);

//      print_r($this->user3->id);

//      $expected = [ 1, 2, 4, 5, $this->user1->id, $this->user2->id, $this->user3->id];
//      $this->assertEquals($expected, $result->pluck('id')->toArray());
//
//      $params = [
//          's' => 'id:desc'
//      ];

//      $result = Users::indexQuery($params);
//      $this->assertCount(7, $result);
//      $expected = [$this->user3->id, $this->user2->id, $this->user1->id, 5, 4, 2, 1];
//
//      $this->assertEquals($expected, $result->pluck('id')->toArray());
    }
}