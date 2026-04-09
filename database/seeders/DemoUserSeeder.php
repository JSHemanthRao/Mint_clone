<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Bill;
use App\Models\Goal;
use App\Models\Category;
use Carbon\Carbon;

class DemoUserSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Demo User
        $user = User::where('email', 'demo@example.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => Hash::make('password'),
            ]);
            $this->command->info('Demo user created.');
        } else {
            $this->command->info('Demo user already exists. Seeding additional data...');
        }

        // 2. Create Categories (if not exist)
        $categories = [
            'Groceries',
            'Rent',
            'Utilities',
            'Salary',
            'Entertainment',
            'Dining Out',
            'Freelance'
        ];

        $categoryModels = [];
        foreach ($categories as $catName) {
            $categoryModels[$catName] = Category::firstOrCreate(['name' => $catName]);
        }

        // 3. Create Accounts
        try {
            $checking = Account::create([
                'user_id' => $user->id,
                'name' => 'Main Checking',
                'type' => 'Checking',
                'balance' => 1500.00,
            ]);

            $savings = Account::create([
                'user_id' => $user->id,
                'name' => 'Emergency Fund',
                'type' => 'Savings',
                'balance' => 5000.00,
            ]);
        } catch (\Exception $e) {
            $this->command->error("Error creating accounts: " . $e->getMessage());
            return;
        }

        // 4. Create Transactions
        // Income
        Transaction::create([
            'account_id' => $checking->id,
            'category_id' => $categoryModels['Salary']->id,
            'amount' => 3000.00,
            // 'type' => 'income', // Not in schema
            'date' => Carbon::now()->subDays(5),
            'description' => 'Monthly Salary',
        ]);

        // Expenses
        Transaction::create([
            'account_id' => $checking->id,
            'category_id' => $categoryModels['Groceries']->id,
            'amount' => 150.50,
            // 'type' => 'expense', // Not in schema
            'date' => Carbon::now()->subDays(2),
            'description' => 'Whole Foods',
        ]);

        Transaction::create([
            'account_id' => $checking->id,
            'category_id' => $categoryModels['Dining Out']->id,
            'amount' => 65.00,
            // 'type' => 'expense', // Not in schema
            'date' => Carbon::now()->subDays(1),
            'description' => 'Dinner with friends',
        ]);

        // 5. Create Budgets
        Budget::create([
            'user_id' => $user->id,
            'category_id' => $categoryModels['Groceries']->id,
            'amount' => 500.00,
            // 'month' => Carbon::now()->format('Y-m'), // Not in schema
        ]);

        Budget::create([
            'user_id' => $user->id,
            'category_id' => $categoryModels['Entertainment']->id,
            'amount' => 200.00,
            // 'month' => Carbon::now()->format('Y-m'), // Not in schema
        ]);

        // 6. Create Bills
        Bill::create([
            'user_id' => $user->id,
            'name' => 'Electric Bill',
            'amount' => 120.00,
            'due_date' => Carbon::now()->addDays(10),
            // 'status' => 'unpaid', // Not in schema
        ]);

        // 7. Create Goals
        Goal::create([
            'user_id' => $user->id,
            'name' => 'Vacation to Bali',
            'target_amount' => 3000.00,
            'current_amount' => 500.00,
            'due_date' => Carbon::now()->addMonths(6), // Schema uses due_date
        ]);

        $this->command->info('Demo data seeded successfully!');
    }
}
