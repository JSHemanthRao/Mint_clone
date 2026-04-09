<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->creditCardType() . ' Account',
            'type' => $this->faker->randomElement(['Checking', 'Savings', 'Credit Card', 'Investment']),
            'balance' => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
