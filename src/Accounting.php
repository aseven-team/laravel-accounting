<?php

namespace AsevenTeam\LaravelAccounting;

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\StartingBalance;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

final class Accounting
{
    private string $accountClass;

    private string $transactionClass;

    private string $transactionLineClass;

    private string $ledgerClass;

    private string $startingBalanceClass;

    public function __construct()
    {
        $this->accountClass = config('accounting.models.account', Account::class);
        $this->transactionClass = config('accounting.models.transaction', Transaction::class);
        $this->transactionLineClass = config('accounting.models.transaction_line', TransactionLine::class);
        $this->ledgerClass = config('accounting.models.ledger', Ledger::class);
        $this->startingBalanceClass = config('accounting.models.starting_balance', StartingBalance::class);
    }

    /**
     * @return class-string<Account>
     */
    public function getAccountClass(): string
    {
        return $this->accountClass;
    }

    /**
     * @return class-string<Transaction>
     */
    public function getTransactionClass(): string
    {
        return $this->transactionClass;
    }

    /**
     * @return class-string<TransactionLine>
     */
    public function getTransactionLineClass(): string
    {
        return $this->transactionLineClass;
    }

    /**
     * @return class-string<Ledger>
     */
    public function getLedgerClass(): string
    {
        return $this->ledgerClass;
    }

    /**
     * @return class-string<StartingBalance>
     */
    public function getStartingBalanceClass(): string
    {
        return $this->startingBalanceClass;
    }
}
