<?php

namespace AsevenTeam\LaravelAccounting\Models;

use AsevenTeam\LaravelAccounting\Contracts\TransactionLine as TransactionLineContract;
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
class TransactionLine extends Model implements TransactionLineContract
{
    use HasFactory;

    protected $table = 'transaction_lines';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('accounting.table_names.transaction_lines') ?: parent::getTable();
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(config('accounting.models.transaction'), 'transaction_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(config('accounting.models.account'), 'account_id');
    }
}
