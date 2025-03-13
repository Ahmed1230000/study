<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\ClientRepository;
use Tests\AuthenticationUser;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, AuthenticationUser;

    protected $user;
    protected $token;
    /**
     * Set up the test
     */
    protected function setUp(): void
    {
        parent::setUp();

        $clientRepository = app(ClientRepository::class);
        $clientRepository->createPersonalAccessClient(
            null,
            'Test Personal Access Client',
            'http://localhost'
        );

        $auth = $this->authUser();
        $this->user = $auth['user'];
        $this->token = $auth['token'];
    }

    public function test_can_store_comment()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);
        $data = [
            'comment' => 'Test comment',
            'status' => 'active',
            'user_id' => $this->user->id,
            'post_id' => $post->id,
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/comments', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Comment created successfully',
            ]);
    }

    public function test_can_update_comment()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);
        $comment = Comment::factory()->create([
            'comment' => 'Test comment tow',
            'status' => 'active',
            'user_id' => $this->user->id,
            'post_id' => $post->id,
        ]);

        $data = [
            'comment' => 'Test comment Updated',
            'status' => 'active',
            'user_id' => $this->user->id,
            'post_id' => $post->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Comment updated successfully',
            ]);
    }

    public function test_can_show_comment()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);
        $comment = Comment::factory()->create([
            'comment' => 'Test comment tow',
            'status' => 'active',
            'user_id' => $this->user->id,
            'post_id' => $post->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/comments/{$comment->id}");
        $response->assertJson([
            'message' => 'Comment retrieved successfully',
        ])->assertStatus(200);
    }

    public function test_can_delete_comment()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);
        $comment = Comment::factory()->create([
            'comment' => 'Test comment tow',
            'status' => 'active',
            'user_id' => $this->user->id,
            'post_id' => $post->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertJson([
            'message' => 'Comment deleted successfully',
        ])->assertStatus(200);
    }
}
