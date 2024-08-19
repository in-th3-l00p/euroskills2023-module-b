<?php

namespace Database\Factories;

use App\Models\ApiToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usage>
 */
class UsageFactory extends Factory
{
    public function definition(): array
    {
        return [
            "duration_in_ms" => rand(10, 20),
            "created_at" => fake()->dateTimeBetween("-3 months", "+1 month"),
            "updated_at" => fake()->dateTimeBetween("-3 months", "+1 month")
        ];
    }

    public function production() {
        return $this->state(fn () => [
            "duration_in_ms" => rand(20, 40),
        ]);
    }
}
