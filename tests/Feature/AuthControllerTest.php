<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function testMustEnterEmailAndPassword()
    {
        $response = $this->postJson('/api/login', [], ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ],
            ]);

    }

    public function testInvalidEmailAddress()
    {
        $payload = [
            'email' => 'abcasdf',
        ];

        $response = $this->postJson('/api/login', $payload, ['Accept' => 'application/json']);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email must be a valid email address."],
                ],
            ]);
    }

    public function testUserRegistrationSuccessful()
    {
        $this->withoutExceptionHandling();
        $payload = [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'abcxyz',
            'confirm_password' => 'abcxyz',
        ];

        $response = $this->postJson('/api/registration', $payload, ['Accept' => 'application/json']);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                "token",
            ]);
    }

    public function testRegistionWithMissingData()
    {
        $payload = [];
        $this->postJson('/api/registration', $payload, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => [
                        "The name field is required.",
                    ],
                    "email" => [
                        "The email field is required.",
                    ],
                    "password" => [
                        "The password field is required.",
                    ],
                    "confirm_password" => [
                        "The confirm password field is required.",
                    ],
                ]]);
    }

    public function testPasswordAndConfirmPasswordEquality()
    {
        $payload = [
            'name' => 'rakib',
            'email' => 'rakib.mit19@gmail.com',
            'password' => 'abcxyz',
            'confirm_password' => 'xyzabc',
        ];

        $response = $this->postJson('/api/registration', $payload, ['Accept' => 'application/json']);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "confirm_password" => [
                        "The confirm password and password must match.",
                    ],
                ],
            ]);
    }

    public function testUserLoginSuccessful()
    {
        User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('abcxyz'),
        ]);

        $payload = [
            'email' => 'sample@test.com',
            'password' => 'abcxyz',
        ];

        $response = $this->postJson('/api/login', $payload, ['Accept' => 'application/json']);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                "token",
            ]);
    }
}
