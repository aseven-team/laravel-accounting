<?php

namespace AsevenTeam\LaravelAccounting\Models;

use AsevenTeam\LaravelAccounting\Contracts\Ledger as LedgerContract;
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
class Ledger extends Model implements LedgerContract
{
    use HasFactory;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('accounting.table_names.ledgers') ?: parent::getTable();
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(config('accounting.models.account'), 'account_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(config('accounting.models.transaction'), 'transaction_id');
    }

    public function transactionLine(): BelongsTo
    {
        return $this->belongsTo(config('accounting.models.transaction_line'), 'transaction_line_id');
    }
}
