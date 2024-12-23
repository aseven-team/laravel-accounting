<?php

namespace AsevenTeam\LaravelAccounting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $transaction_id
 * @property int $account_id
 * @property float $debit
 * @property float $credit
 * @property ?string $description
 * @property-read Transaction $transaction
 * @property-read Account $account
 */
class TransactionLine extends Model
{
    use HasFactory;

    protected $table = 'transaction_lines';

    protected $guarded = [];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
