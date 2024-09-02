<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_posts()
    {
        Post::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
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
                'message' => 'Posts successfully received.',
                'success' => true,
            ]);
    }

    public function test_can_get_single_post()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/v1/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'created_at',
                    'updated_at',
                    'likes_count',
                    'comments_count',
                ],
                'message',
                'success',
            ])
            ->assertJson([
                'message' => 'Post successfully retrieved.',
                'success' => true,
            ]);
    }

    public function test_can_create_post()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user)->postJson('/api/v1/posts', [
            'content' => 'Test post content',
            'attachments' => [$file],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'created_at',
                    'updated_at',
                    'attachments' => [
                        '*' => [
                            'id',
                            'user_id',
                            'post_id',
                            'media' => [
                                'id',
                                'url',
                            ],
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
                'message',
                'success',
            ])
            ->assertJson([
                'message' => 'Post successfully created.',
                'success' => true,
            ]);

        $this->assertDatabaseHas('posts', [
            'content' => 'Test post content',
        ]);

        Storage::disk('public')->assertExists('attachments/' . $file->hashName());
    }

    public function test_can_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson("/api/v1/posts/{$post->id}", [
            'content' => 'Updated post content',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'created_at',
                    'updated_at',
                ],
                'message',
                'success',
            ])
            ->assertJson([
                'data' => [
                    'content' => 'Updated post content',
                ],
                'message' => 'Post successfully updated.',
                'success' => true,
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => 'Updated post content',
        ]);
    }

    public function test_can_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/v1/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post successfully deleted.',
                'success' => true,
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_unauthorized_user_cannot_create_post()
    {
        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Test post content',
        ]);

        $response->assertStatus(401);
    }

    public function test_unauthorized_user_cannot_update_post()
    {
        $post = Post::factory()->create();

        $response = $this->putJson("/api/v1/posts/{$post->id}", [
            'content' => 'Updated post content',
        ]);

        $response->assertStatus(401);
    }

    public function test_unauthorized_user_cannot_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/v1/posts/{$post->id}");

        $response->assertStatus(401);
    }
}
