<?php

namespace AsevenTeam\LaravelAccounting\Concerns;

use AsevenTeam\LaravelAccounting\Contracts\HasNumber;
use Illuminate\Database\Eloquent\Model;

trait AutoSetNumber
{
    protected static function bootHasNumber(): void
    {
        static::creating(function (Model $model) {
            if ($model instanceof HasNumber) {
                $model->setSequence();
                $model->setNumber();
            }
        });
    }

    public function setSequence(): void
    {
        $this->{$this->getSequenceColumnName()} = $this->getNewSequence();
    }

    public function setNumber(): void
    {
        if (! isset($this->{$this->getNumberColumnName()})) {
            $this->{$this->getNumberColumnName()} = $this->generateNumber($this->{$this->getSequenceColumnName()});
        }
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
