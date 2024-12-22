<?php

use AsevenTeam\LaravelAccounting\Actions\Account\MarkAccountAsActive;
use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Models\Account;

test('mark account as active', function () {
    $account = Account::factory()->archived()->create();

    app(MarkAccountAsActive::class)->handle($account);

    $account->refresh();
    expect($account->status)->toBe(AccountStatus::Active)
        ->and($account->archived_at)->toBeNull();
});
