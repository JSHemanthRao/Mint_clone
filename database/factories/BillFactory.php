<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class BillFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company() . ' Bill',
            'amount' => $this->faker->randomFloat(2, 20, 500),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
