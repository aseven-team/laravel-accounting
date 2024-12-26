<?php

use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Support\Carbon;

test('to array', function () {
    $transaction = Transaction::factory()->create()->fresh();

    expect(array_keys($transaction->toArray()))->toBe([
        'id',
        'reference_type',
        'reference_id',
        'sequence',
        'number',
        'date',
        'description',
        'created_at',
        'updated_at',
    ]);
});

test('date cast', function () {
    $transaction = Transaction::factory()->create()->fresh();

    expect($transaction->date)->toBeInstanceOf(Carbon::class);
});

test('sequence', function () {
    $transaction1 = Transaction::factory()->create();
    $transaction2 = Transaction::factory()->create();

    expect($transaction1->sequence)->toBe(1)
        ->and($transaction2->sequence)->toBe(2);
});

test('auto set number', function () {
    $transaction = Transaction::factory()->create();

    expect($transaction->number)->toBeString();
});

it('does not override number', function () {
    $transaction = Transaction::factory()->create([
        'number' => 'INV-001',
    ]);

    expect($transaction->number)->toBe('INV-001');
});

it('can have reference', function () {
    $reference = Transaction::factory()->create();
    $transaction = Transaction::factory()->for($reference, 'reference')->create();

    expect($transaction->reference)->toBeInstanceOf($reference::class)
        ->and($transaction->reference->id)->toBe($reference->id);
});

it('can have no reference', function () {
    $transaction = Transaction::factory()->create();

    expect($transaction->reference)->toBeNull();
});

test('has lines', function () {
    $transaction = Transaction::factory()->hasLines(3)->create();

    expect($transaction->lines)->toHaveCount(3);
});
