<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class GoalFactory extends Factory
{
    public function definition(): array
    {
        $target = $this->faker->randomFloat(2, 1000, 50000);
        return [
            'user_id' => User::factory(),
            'name' => 'Save for ' . $this->faker->word(),
            'target_amount' => $target,
            'current_amount' => $this->faker->randomFloat(2, 0, $target),
            'due_date' => $this->faker->dateTimeBetween('+6 months', '+2 years'),
        ];
    }
}
