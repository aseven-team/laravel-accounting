<?php

namespace AsevenTeam\LaravelAccounting\Database\Factories;

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionLineFactory extends Factory
{
    protected $model = TransactionLine::class;

    public function definition(): array
    {
        return [
            'debit' => $this->faker->randomFloat(2, 0, 1000),
            'credit' => function (array $attributes) {
                return $attributes['debit'] > 0 ? 0 : $this->faker->randomFloat(2, 0, 1000);
            },
            'description' => null,
            'transaction_id' => Transaction::factory(),
            'account_id' => Account::factory(),
        ];
    }

    public function debit(): static
    {
        return $this->state([
            'debit' => $this->faker->randomFloat(2, 0, 1000),
            'credit' => 0,
        ]);
    }

    public function credit(): static
    {
        return $this->state([
            'debit' => 0,
            'credit' => $this->faker->randomFloat(2, 0, 1000),
        ]);
    }
}
