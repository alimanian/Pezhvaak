<?php

namespace Tests\Feature\Api\V1;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
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

    public function test_can_create_comment()
    {
        $commentData = [
            'content' => $this->faker->sentence,
            'post_id' => $this->post->id
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/comments', $commentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'post_id',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => $commentData['content'],
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    public function test_cannot_create_comment_without_authentication()
    {
        $commentData = [
            'content' => $this->faker->sentence,
            'post_id' => $this->post->id
        ];

        $response = $this->postJson('/api/v1/comments', $commentData);

        $response->assertStatus(401);
    }

    public function test_can_delete_own_comment()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_cannot_delete_comment_of_another_user()
    {
        $anotherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $anotherUser->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(403);
    }

    public function test_cannot_delete_comment_without_authentication()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(401);
    }
}
