<?php

namespace AsevenTeam\LaravelAccounting\Models;

use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Exceptions\AccountDoesNotExist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $code
 * @property string $name
 * @property AccountType $type
 * @property NormalBalance $normal_balance
 * @property AccountStatus $status
 * @property ?string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Account $parent
 * @property-read Collection<int, Account> $children
 */
class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
            'normal_balance' => NormalBalance::class,
            'status' => AccountStatus::class,
            'archived_at' => 'datetime',
        ];
    }

    public static function findByCode(string $code): static
    {
        $account = static::query()->where('code', $code)->first();

        if (! $account) {
            throw AccountDoesNotExist::create($code);
        }

        return $account;
    }

    public function transactionLines(): HasMany
    {
        return $this->hasMany(TransactionLine::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }
}
