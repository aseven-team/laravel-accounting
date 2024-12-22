<?php

namespace AsevenTeam\LaravelAccounting\Data\Account;

use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use Spatie\LaravelData\Data;

class CreateAccountData extends Data
{
    public function __construct(
        public string $code,
        public string $name,
        public AccountType $type,
        public NormalBalance $normal_balance,
        public ?string $description = null,
        public ?int $parent_id = null,
    ) {}
}
