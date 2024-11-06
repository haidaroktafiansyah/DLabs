<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserApiNegativeTest extends TestCase
{
    use RefreshDatabase;

    protected $token;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $this->token = $response->json('access_token');
    }

    public function test_create_user_invalid_data()
    {
        $response = $this->postJson('/api/users', [
            'email' => 'invalidemail',
            'password' => '123',
            'name' => '',
            'age' => 'invalid',
            'status' => 'superadmin'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => [
                    'email' => ['The email must be a valid email address.'],
                    'name' => ['The name field is required.'],
                    'age' => [
                        'The age must be an integer.',
                        'The age must be at least 18.',
                    ],
                ]
            ]);
    }

    public function test_login_user_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invaliduser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid credentials'
            ]);
    }

    public function test_get_users_unauthorized()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'You do not have permission to access this resource.'
            ]);
    }

    public function test_get_user_by_id_not_found()
    {
        $invalidUserId = 9999;

        $response = $this->getJson("/api/users/{$invalidUserId}", [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                "error" => [
                    "id" => [
                        "User not found."
                    ]
                ]
            ]);
    }

    public function test_update_user_invalid_data()
    {
        $this->assertNotNull($this->token, 'Token should not be null before using it');

        $payload = [
            'name' => '',
            'age' => -1,
            'email' => 'invalidemail',
            'password' => 'short',
            'status' => 'superadmin',
        ];

        $response = $this->putJson("/api/users/{$this->user->id}", $payload, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => [
                    'email' => ['The email must be a valid email address.'],
                    'name' => ['The name field is required.'],
                    'age' => [
                        'The age must be at least 18.',
                    ],
                ]
            ]);
    }

    public function test_delete_user_not_found()
    {
        $invalidUserId = 9999;

        $response = $this->deleteJson("/api/users/{$invalidUserId}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                "error" => [
                    "id" => [
                        "Invalid id."
                    ]
                ]
            ]);
    }
}
