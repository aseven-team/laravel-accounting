<?php

namespace AsevenTeam\LaravelAccounting\Contracts;

use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @mixin \AsevenTeam\LaravelAccounting\Models\Account
 *
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
interface Account
{
    /**
     * Get the parent account for the account.
     */
    public function parent(): BelongsTo;

    /**
     * Get the children accounts for the account.
     */
    public function children(): HasMany;

    /**
     * Get the transaction lines for the account.
     */
    public function transactionLines(): HasMany;
}
