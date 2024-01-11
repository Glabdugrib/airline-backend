<?php

use App\Models\User;
use Illuminate\Http\JsonResponse;

use function Pest\Laravel\{postJson};

describe('auth', function () {
    it('logs in a user and returns an access token', function () {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Assert
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->tap(fn ($response) => $response->assertJsonStructure(['access_token']))
            ->assertJson(['access_token' => true]);
    });

    it('handles validation errors during login', function () {
        // Act
        $response = postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'invalid-password',
        ]);

        // Assert
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    });

    it('handles wrong credentials during login', function () {
        // Act
        $response = postJson('/api/login', [
            'email' => 'valid-email@example.com',
            'password' => 'valid-password',
        ]);

        // Assert
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    });
});
