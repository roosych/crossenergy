<?php

namespace Database\Factories;

use App\Models\Owner;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => fake()->name('male'),
            'number' => fake()->unique()->randomNumber(2),
            'phone' => fake()->e164PhoneNumber(),
            'vehicle_type_id' => VehicleType::all()->random()->id,
            'owner_id' => Owner::all()->random()->id,
            'availability' => fake()->boolean,
            'dnu' => fake()->boolean,
            'capacity' => fake()->word(),
            'dimension' => fake()->word(),
            'citizenship' => fake()->word(),
            'zipcode' => fake()->randomNumber(6),
            'location' => fake()->city(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'future_zipcode' => fake()->randomNumber(6),
            'future_location' => fake()->city(),
            'future_latitude' => fake()->latitude(),
            'future_longitude' => fake()->longitude(),
            'future_datetime' => fake()->dateTime(now()),
            'note' => fake()->sentence(),

        ];
    }
}
