<?php

namespace Tests\Feature\Api\V1;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
    }

    public function test_can_create_like()
    {
        $likeData = [
            'post_id' => $this->post->id
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/likes', $likeData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'post_id',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    public function test_cannot_create_like_without_authentication()
    {
        $likeData = [
            'post_id' => $this->post->id
        ];

        $response = $this->postJson('/api/v1/likes', $likeData);

        $response->assertStatus(401);
    }

    public function test_cannot_create_duplicate_like()
    {
        Like::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $likeData = [
            'post_id' => $this->post->id
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/likes', $likeData);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'You have already liked this post.',
                'success' => false
            ]);
    }

    public function test_can_delete_own_like()
    {
        $like = Like::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/likes/{$like->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('likes', ['id' => $like->id]);
    }

    public function test_cannot_delete_like_of_another_user()
    {
        $anotherUser = User::factory()->create();
        $like = Like::factory()->create([
            'user_id' => $anotherUser->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/likes/{$like->id}");

        $response->assertStatus(403);
    }

    public function test_cannot_delete_like_without_authentication()
    {
        $like = Like::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->deleteJson("/api/v1/likes/{$like->id}");

        $response->assertStatus(401);
    }
}
