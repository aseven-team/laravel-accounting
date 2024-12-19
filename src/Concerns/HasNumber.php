<?php

namespace AsevenTeam\LaravelAccounting\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasNumber
{
    protected static function bootHasNumber(): void
    {
        static::creating(function (Model $model) {
            $model->{$this->getSequenceColumnName()} = $this->getNewSequence();

            if (! isset($model->number)) {
                $model->{$this->getNumberColumnName()} = $this->generateNumber($model->{$this->getSequenceColumnName()});
            }
        });
    }

    protected function getNewSequence(): int
    {
        return static::query()->max('sequence') + 1;
    }

    protected function getSequenceColumnName(): string
    {
        return 'sequence';
    }

    protected function getNumberColumnName(): string
    {
        return 'number';
    }

    protected function generateNumber(int $sequence): string
    {
        return "$sequence";
    }
}
