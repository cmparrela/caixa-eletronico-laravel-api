<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $transaction = Transaction::factory()->count(3);
        $account = Account::factory()->count(1)->has($transaction);

        User::factory()->count(10)->has($account)->create();
    }
}
