<?php

namespace AsevenTeam\LaravelAccounting\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \AsevenTeam\LaravelAccounting\Models\Ledger
 */
interface Ledger
{
    /**
     * Get the account for the ledger.
     */
    public function account(): BelongsTo;

    /**
     * Get the transaction for the ledger.
     */
    public function transaction(): BelongsTo;

    /**
     * Get the transaction line for the ledger.
     */
    public function transactionLine(): BelongsTo;
}
