<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        $types = [Account::TYPE_CHECKING, Account::TYPE_SAVINGS];
        return [
            'type' => $types[array_rand($types)],
            'balance' => $this->faker->randomFloat(2, 50, 10000),
        ];
    }
}
