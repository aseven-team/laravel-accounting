<?php

use AsevenTeam\LaravelAccounting\Actions\Account\MarkAccountAsArchived;
use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Models\Account;

test('mark account as archived', function () {
    $account = Account::factory()->create();

    $this->travelTo($now = now()->subDay());
    app(MarkAccountAsArchived::class)->handle($account);

    $account->refresh();
    expect($account->status)->toBe(AccountStatus::Archived)
        ->and($account->archived_at->format('Y-m-d H:i:s'))->toBe($now->format('Y-m-d H:i:s'));
});
