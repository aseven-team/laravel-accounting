<?php

namespace AsevenTeam\LaravelAccounting\Models;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $account_id
 * @property float $debit
 * @property float $credit
 * @property Carbon|CarbonImmutable $created_at
 * @property Carbon|CarbonImmutable $updated_at
 * @property-read Account $account
 */
class StartingBalance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('accounting.table_names.starting_balances') ?: parent::getTable();
    }

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(config('accounting.models.account'), 'account_id');
    }
}
