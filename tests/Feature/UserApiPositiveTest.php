<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserApiPositiveTest extends TestCase
{
    use RefreshDatabase;

    protected $token;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'haidartester@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $this->token = $response->json('access_token');
    }

    public function test_create_user()
    {
        $response = $this->postJson('/api/users', [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'name' => 'New User',
            'age' => 25,
            'status' => 'superadmin'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'msg' => 'Successfully added data',
                'data' => [
                    'name' => 'New User',
                    'email' => 'newuser@example.com',
                    'age' => 25,
                    'status' => 'superadmin',
                ]
            ]);
    }

    public function test_login_user()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'access_token',
            ]);

        $this->token = $response->json('access_token');
    }

    public function test_get_users()
    {
        $this->assertNotNull($this->token, 'Token should not be null before using it');

        $response = $this->getJson('/api/users', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'age',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'first_page_url',
                'last_page_url',
                'next_page_url',
                'prev_page_url',
                'path',
                'per_page',
                'from',
                'to',
                'total',
            ]);
    }

    public function test_get_user_by_id()
    {
        $this->assertNotNull($this->token, 'Token should not be null before using it');

        $response = $this->getJson("/api/users/{$this->user->id}", [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'age',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_update_user()
    {
        $this->assertNotNull($this->token, 'Token should not be null before using it');

        $payload = [
            'name' => 'Haidar',
            'age' => 18,
            'email' => 'test@mail.com',
            'password' => 'test',
            'status' => 'superadmin',
        ];

        $response = $this->putJson("/api/users/{$this->user->id}", $payload, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'msg',
                'data' => [
                    'name',
                    'email',
                    'age',
                    'status',
                ],
            ]);
    }

    public function test_delete_user()
    {
        $this->assertNotNull($this->token, 'Token should not be null before using it');

        $response = $this->deleteJson("/api/users/{$this->user->id}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'msg',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'age',
                    'status',
                    'password',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
}
