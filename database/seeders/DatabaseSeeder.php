<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Bill;
use App\Models\Goal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Primary User requested by the user
        $user = User::firstOrCreate(
            ['email' => 'hemanthrao@gmail.com'],
            [
                'name' => 'Hemanth Rao',
                'password' => bcrypt('Hemanthrao@1234'),
            ]
        );

        // 2. Create Categories (Shared Standard Categories)
        $categoriesRaw = [
            'Groceries',
            'Rent',
            'Utilities',
            'Entertainment',
            'Dining Out',
            'Transport',
            'Healthcare',
            'Shopping',
            'Salary',
            'Freelance',
            'Investments',
            'Education',
            'Travel',
            'Subscription',
            'Gifts'
        ];
        $categories = [];
        foreach ($categoriesRaw as $catName) {
            $categories[] = Category::firstOrCreate(['name' => $catName]);
        }

        // 3. Create Accounts for the user (Checking, Savings, Credit Card, etc.)
        $accounts = Account::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);

        // 4. Create Transactions for each account (High volume)
        foreach ($accounts as $account) {
            Transaction::factory()->count(50)->create([
                'account_id' => $account->id,
                'category_id' => $categories[array_rand($categories)]->id,
            ]);
        }

        // 5. Create Budgets for the user
        foreach (array_slice($categories, 0, 10) as $category) {
            Budget::factory()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => rand(500, 5000),
            ]);
        }

        // 6. Create Bills
        Bill::factory()->count(15)->create([
            'user_id' => $user->id,
        ]);

        // 7. Create Goals
        Goal::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        // 8. Create Realistic Notifications for the primary user
        $notificationMessages = [
            'Low balance alert: Your Savings account is below ₹1,000.',
            'Budget exceeded: You spent more than planned on Dining Out this month.',
            'New bill due: Your Electricity bill is due in 3 days.',
            'Large transaction: A ₹5,000 withdrawal was made from your Checking account.',
            'Goal progress: You are 50% closer to your Vacation goal!',
            'Security alert: New login detected from a new device.',
            'Monthly summary: Your spending last month was 10% lower than average.',
            'Refund received: A refund of ₹250 has been credited to your account.',
            'Subscription renewal: Your Netflix subscription will renew tomorrow.',
            'Investment update: Your portfolio value has increased by 5%.'
        ];

        foreach ($notificationMessages as $message) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'message' => $message,
                'read' => false,
            ]);
        }

        // Create some extra users to make the DB look populated, with their own data
        User::factory(10)->create()->each(function ($u) use ($categories) {
            Account::factory()->count(2)->create(['user_id' => $u->id])->each(function ($a) use ($categories) {
                Transaction::factory()->count(5)->create([
                    'account_id' => $a->id,
                    'category_id' => $categories[array_rand($categories)]->id
                ]);
            });
        });

        // 9. Run Demo User Seeder
        $this->call(DemoUserSeeder::class);
    }
}
