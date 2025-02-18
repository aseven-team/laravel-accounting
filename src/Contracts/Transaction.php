<?php

namespace AsevenTeam\LaravelAccounting\Contracts;

use AsevenTeam\LaravelAccounting\Models\TransactionLine;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $reference_type
 * @property int $reference_id
 * @property int $sequence
 * @property string $number
 * @property Carbon $date
 * @property ?string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $title
 * @property-read Model $reference
 * @property-read Collection<int, TransactionLine> $lines
 *
 * @mixin \AsevenTeam\LaravelAccounting\Models\Transaction
 */
interface Transaction
{
    /**
     * Get the title attribute for the transaction.
     */
    public function title(): Attribute;

    /**
     * Get the reference model for the transaction.
     */
    public function reference(): MorphTo;

    /**
     * Get the transaction lines for the transaction.
     */
    public function lines(): HasMany;
}
