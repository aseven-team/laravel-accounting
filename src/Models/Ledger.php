<?php

namespace AsevenTeam\LaravelAccounting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $transaction_id
 * @property int $transaction_line_id
 * @property int $account_id
 * @property Carbon $date
 * @property string $transaction_title
 * @property ?string $description
 * @property float $debit
 * @property float $credit
 * @property float $debit_balance
 * @property float $credit_balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Account $account
 * @property-read Transaction $transaction
 * @property-read TransactionLine $transactionLine
 */
class Ledger extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionLine(): BelongsTo
    {
        return $this->belongsTo(TransactionLine::class);
    }
}
