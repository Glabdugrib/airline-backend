<?php

namespace Database\Factories;

use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airport>
 */
class AirportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $uniqueCode = strtoupper(fake()->randomLetter.fake()->randomLetter.fake()->randomLetter);
        } while (Airport::where('code', $uniqueCode)->exists());

        return [
            'code' => $uniqueCode,
            'name' => Str::limit(fake()->word, 50),
            'city' => Str::limit(fake()->city, 50),
            'country' => Str::limit(fake()->country, 50),
        ];
    }
}
