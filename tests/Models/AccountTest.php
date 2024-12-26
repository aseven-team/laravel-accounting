<?php

use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Exceptions\AccountDoesNotExist;
use AsevenTeam\LaravelAccounting\Models\Account;
use Illuminate\Support\Carbon;

test('to array', function () {
    $account = Account::factory()->create()->fresh();

    expect(array_keys($account->toArray()))->toBe([
        'id',
        'parent_id',
        'code',
        'name',
        'type',
        'normal_balance',
        'description',
        'status',
        'archived_at',
        'created_at',
        'updated_at',
    ]);
});

test('type cast', function () {
    $account = Account::factory()->create()->fresh();

    expect($account->type)->toBeInstanceOf(AccountType::class);
});

test('normal balance cast', function () {
    $account = Account::factory()->create()->fresh();

    expect($account->normal_balance)->toBeInstanceOf(NormalBalance::class);
});

test('status cast', function () {
    $account = Account::factory()->create()->fresh();

    expect($account->status)->toBeInstanceOf(AccountStatus::class);
});

test('archived_at cast', function () {
    $account = Account::factory()->create(['archived_at' => now()])->fresh();

    expect($account->archived_at)->toBeInstanceOf(Carbon::class);
});

test('parent relationship', function () {
    $parent = Account::factory()->create();
    $child = Account::factory()->create(['parent_id' => $parent->id]);

    expect($child->parent)->toBeInstanceOf(Account::class)
        ->and($child->parent->id)->toBe($parent->id);
});

test('children relationship', function () {
    $parent = Account::factory()->hasChildren(3)->create();

    Account::factory()->create(['parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(4);
});

test('find by code', function () {
    $account = Account::factory()->create();

    expect(Account::findByCode($account->code)->id)->toBe($account->id);
});

it('throw exception when account not found by code', function () {
    Account::findByCode('invalid-code');
})->throws(AccountDoesNotExist::class);
