<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;
use App\Models\Category;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'category_id' => Category::factory(),
            'description' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, -500, 500), // Negative for expense, positive for income
            'date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
