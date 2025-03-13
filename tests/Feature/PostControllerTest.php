<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\AuthenticationUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository;

class PostControllerTest extends TestCase
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

        // Set up Passport clients (personal access client)
        $clientRepository = app(ClientRepository::class);
        $clientRepository->createPersonalAccessClient(
            null, // user_id (null for system-level client)
            'Test Personal Access Client', // name
            'http://localhost' // redirect URI (can be a dummy value)
        );

        // Authenticate a user using the trait
        $auth = $this->authUser();
        $this->user = $auth['user'];
        $this->token = $auth['token'];
    }
    public function test_can_store_post()
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id, // Use user ID here
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Post created successfully',
            ]);
    }

    public function test_can_update_post()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post tow',
            'content' => 'This is a test post tow',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);

        $data = [
            'title' => 'Test Post Updated',
            'content' => 'This is a test post updated',
            'status' => 'active',
            'user_id' => $this->user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/posts/' . $post->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post updated successfully',
            ]);
    }

    public function test_can_show_post()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post retrieved successfully',
            ]);
    }

    public function test_can_delete_post()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'status' => 'active',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post deleted successfully',
            ]);
    }
}
