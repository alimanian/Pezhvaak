<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_followers_returns_paginated_followers()
    {
        $user = User::factory()->create();
        $followers = User::factory()->count(15)->create();

        foreach ($followers as $follower) {
            $user->followers()->create(['follower_id' => $follower->id]);
        }

        $response = $this->getJson("/api/v1/users/{$user->id}/followers");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'follower' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'created_at',
                        'updated_at',
                    ]
                ],
                'message',
                'success',
                'links',
            ])
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'message' => 'User followers received.',
                'success' => true,
            ]);
    }

    public function test_following_returns_paginated_following()
    {
        $user = User::factory()->create();
        $following = User::factory()->count(15)->create();

        foreach ($following as $followedUser) {
            $user->following()->create(['following_id' => $followedUser->id]);
        }

        $response = $this->getJson("/api/v1/users/{$user->id}/following");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'following' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'created_at',
                        'updated_at',
                    ]
                ],
                'message',
                'success',
                'links',
            ])
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'message' => 'User following received.',
                'success' => true,
            ]);
    }

    public function test_follow_user()
    {
        $user = User::factory()->create();
        $userToFollow = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/v1/users/{$userToFollow->id}/follow");

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User followed successfully.',
                'success' => true,
            ]);
    }

    public function test_unfollow_user()
    {
        $user = User::factory()->create();
        $userToUnfollow = User::factory()->create();

        $user->following()->create(['following_id' => $userToUnfollow->id]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/v1/users/{$userToUnfollow->id}/unfollow");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Unfollow was successfully.',
                'success' => true,
            ]);
    }

    public function test_cannot_follow_self()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/v1/users/{$user->id}/follow");

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'You cannot follow yourself.',
                'success' => false,
            ]);
    }

    public function test_follow_requires_authentication()
    {
        $userToFollow = User::factory()->create();

        $response = $this->postJson("/api/v1/users/{$userToFollow->id}/follow");

        $response->assertStatus(401);
    }

    public function test_unfollow_requires_authentication()
    {
        $userToUnfollow = User::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$userToUnfollow->id}/unfollow");

        $response->assertStatus(401);
    }
}
