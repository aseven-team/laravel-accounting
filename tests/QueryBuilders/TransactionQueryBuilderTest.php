<?php

use AsevenTeam\LaravelAccounting\Models\Transaction;

test('period', function () {
    $today = now()->toDateString();
    $yesterday = now()->subDay()->toDateString();
    $twoDaysAgo = now()->subDays(2)->toDateString();

    Transaction::factory(3)->create(['date' => $today]);

    expect(Transaction::period($yesterday, $today)->count())->toBe(3)
        ->and(Transaction::period($twoDaysAgo, $yesterday)->count())->toBe(0);
});
