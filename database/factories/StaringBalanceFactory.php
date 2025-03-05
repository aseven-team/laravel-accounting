<?php

namespace AsevenTeam\LaravelAccounting\Database\Factories;

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\StartingBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaringBalanceFactory extends Factory
{
    protected $model = StartingBalance::class;

    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'debit' => $this->faker->randomFloat(2, 0, 1000),
            'credit' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
