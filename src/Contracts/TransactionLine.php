<?php

namespace AsevenTeam\LaravelAccounting\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \AsevenTeam\LaravelAccounting\Models\TransactionLine
 */
interface TransactionLine
{
    /**
     * Get the transaction for the transaction line.
     */
    public function transaction(): BelongsTo;

    /**
     * Get the account for the transaction line.
     */
    public function account(): BelongsTo;
}
