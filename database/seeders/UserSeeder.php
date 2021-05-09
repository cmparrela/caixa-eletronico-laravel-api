<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->count(10)
            ->has(Account::factory()->count(1))
            ->create();
    }
}
