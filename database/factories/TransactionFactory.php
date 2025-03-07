<?php

namespace AsevenTeam\LaravelAccounting\Database\Factories;

use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'date' => now(),
            'description' => null,
        ];
    }
}
