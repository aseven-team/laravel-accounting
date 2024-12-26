<?php

namespace AsevenTeam\LaravelAccounting\Database\Factories;

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LedgerFactory extends Factory
{
    protected $model = Ledger::class;

    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'transaction_line_id' => TransactionLine::factory(),
            'account_id' => Account::factory(),
            'date' => Carbon::now(),
            'transaction_title' => $this->faker->word(),
            'description' => null,
            'debit' => $this->faker->randomFloat(2, 0, 1000),
            'credit' => function (array $attributes) {
                return $attributes['debit'] > 0 ? 0 : $this->faker->randomFloat(2, 0, 1000);
            },
            'debit_balance' => function (array $attributes) {
                return $attributes['debit'];
            },
            'credit_balance' => function (array $attributes) {
                return $attributes['credit'];
            },
        ];
    }
}
