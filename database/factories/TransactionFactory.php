<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        $types = [Transaction::TYPE_DEPOSIT, Transaction::TYPE_WITHDRAWAL];
        return [
            'type' => $types[array_rand($types)],
            'value' => $this->faker->randomFloat(0, 10, 300),
        ];
    }
}
