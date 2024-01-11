<?php

use function Pest\Laravel\{getJson, postJson};
use App\Models\Airport;
use Illuminate\Http\Response;
use App\Models\User;

/**
 * Helper method to log in a user and obtain the authentication token.
 *
 * @param \App\Models\User $user
 * @return string
 */
function loginUser(User $user): string
{
    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    return $response->json('access_token');
}

beforeEach(function () {
    // Setup a user and obtain the authentication token
    $user = User::factory()->create();
    $this->token = loginUser($user);
});

describe('index', function () {

    it('returns a list of airports with default pagination', function () {
        // Arrange
        Airport::factory(10)->create();
    
        // Act
        $response = getJson('/api/v1/airports', ['Authorization' => 'Bearer ' . $this->token]);
    
        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'city', 'country']]]);
    });

    it('returns a list of airports with custom pagination', function () {
        // Arrange
        $airports = Airport::factory(15)->create();
    
        // Act
        $response = getJson('/api/v1/airports?page=2&limit=5', ['Authorization' => 'Bearer ' . $this->token]);
    
        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'city', 'country']]])
            ->assertJsonFragment(['id' => $airports[5]->id]);
    });
    
    it('filters airports by city', function () {
        // Arrange
        $airport = Airport::factory()->create(['city' => 'TestCity']);
    
        // Act
        $response = getJson('/api/v1/airports?city=TestCity', ['Authorization' => 'Bearer ' . $this->token]);
    
        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $airport->id]);
    });
    
    it('filters airports by country', function () {
        // Arrange
        $airport = Airport::factory()->create(['country' => 'TestCountry']);
    
        // Act
        $response = getJson('/api/v1/airports?country=TestCountry', ['Authorization' => 'Bearer ' . $this->token]);
    
        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $airport->id]);
    });
    
    it('sorts airports by name in ascending order', function () {
        // Arrange
        Airport::factory()->create(['name' => 'B Airport']);
        Airport::factory()->create(['name' => 'A Airport']);
    
        // Act
        $response = getJson('/api/v1/airports?sort_by=name');
    
        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertSeeInOrder(['A Airport', 'B Airport']);
    });
    
    it('handles validation errors when sorting', function () {
        // Act
        $response = getJson('/api/v1/airports?sort_by=+city,.country', ['Authorization' => 'Bearer ' . $this->token]);

    
        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    });
 });
