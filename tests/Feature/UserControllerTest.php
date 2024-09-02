<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_users()
    {
        User::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                        'posts_count',
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
                'message' => 'User retrieved successfully.',
                'success' => true,
            ]);
    }

    public function test_index_returns_empty_users_list()
    {
        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'message',
                'success',
                'links',
            ])
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'message' => 'User retrieved successfully.',
                'success' => true,
            ]);
    }
}
