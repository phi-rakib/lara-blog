<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function getHeader()
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function getAuth($user)
    {
        Sanctum::actingAs(
            $user,
            ['*']
        );
    }

    public function testProfileCreateSuccessfully()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $profile = Profile::factory()->make()->toArray();

        $response = $this->postJson(route('profiles.store'), $profile, $this->getHeader());

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    'body' => $profile['body'],
                    'website_url' => $profile['website_url'],
                    'user_id' => $user->id,
                ]
            );

    }

    public function testProfileRetrieveByIdSuccessfully()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->getAuth($user);

        $profile = Profile::factory()
            ->for($user)
            ->create();

        $response = $this->getJson(route('profiles.show', ['profile' => $profile->id]), $this->getHeader());

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($profile->toArray());
    }
}
