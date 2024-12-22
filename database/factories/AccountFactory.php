<?php

namespace AsevenTeam\LaravelAccounting\Database\Factories;

use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomNumber(4),
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(AccountType::cases()),
            'normal_balance' => $this->faker->randomElement(NormalBalance::cases()),
            'status' => AccountStatus::Active,
            'description' => null,
            'parent_id' => null,
        ];
    }

    public function archived(): static
    {
        return $this->state([
            'status' => AccountStatus::Archived,
        ]);
    }
}
