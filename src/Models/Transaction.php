<?php

namespace AsevenTeam\LaravelAccounting\Models;

use AsevenTeam\LaravelAccounting\Concerns\AutoSetNumber;
use AsevenTeam\LaravelAccounting\Contracts\HasNumber;
use AsevenTeam\LaravelAccounting\QueryBuilders\TransactionQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 */
class Transaction extends Model implements HasNumber
{
    use AutoSetNumber;
    use HasFactory;

    protected $table = 'transactions';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function title(): Attribute
    {
        return Attribute::get(fn () => sprintf('Journal Entry #%s', $this->number));
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function lines(): HasMany
    {
        return $this->hasMany(TransactionLine::class, 'transaction_id');
    }

    public function newEloquentBuilder($query): TransactionQueryBuilder
    {
        return new TransactionQueryBuilder($query);
    }
}
