<?php

namespace Database\Factories;

use App\Models\Airport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Retrieve two random airports
        $randomAirports = Airport::inRandomOrder()
            ->limit(2)
            ->get();

        // Calculate a random departure and arrival time in the future
        $currentDatetime = Carbon::now();
        $departureAt = $currentDatetime->addDays(random_int(1, 200))
            ->addHours(random_int(0, 23))
            ->addMinutes(random_int(0, 59));
        $arrivalAt = Carbon::parse($departureAt)->addHours(random_int(1, 3))
            ->addMinutes(random_int(0, 59));

        // Define the attributes for the flight model
        return [
            'departure_airport_id' => $randomAirports[0]->id,
            'arrival_airport_id' => $randomAirports[1]->id,
            'departure_at' => $departureAt,
            'arrival_at' => $arrivalAt,
            'price' => fake()->randomFloat(2, 50, 500),
            'stopovers' => fake()->numberBetween(0, 2),
        ];
    }
}
