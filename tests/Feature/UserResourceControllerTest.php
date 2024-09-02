<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_posts_returns_paginated_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/v1/users/{$user->id}/posts");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'likes_count',
                        'comments_count',
                    ]
                ],
                'message',
                'success',
                'links',
            ])
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'message' => 'User posts received.',
                'success' => true,
            ]);
    }

    public function test_user_comments_returns_paginated_comments()
    {
        $user = User::factory()->create();
        Comment::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/v1/users/{$user->id}/comments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'user_id',
                        'post_id',
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
                'message' => 'User comments received.',
                'success' => true,
            ]);
    }

    public function test_user_likes_returns_paginated_likes()
    {
        $user = User::factory()->create();
        Like::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/v1/users/{$user->id}/likes");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'post_id',
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
                'message' => 'User likes received.',
                'success' => true,
            ]);
    }

    public function test_returns_404_for_non_existent_user()
    {
        $nonExistentUserId = 9999;

        $response = $this->getJson("/api/v1/users/{$nonExistentUserId}/posts");

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'User not found or an error occurred while fetching posts.',
                'success' => false,
            ]);
    }
}
