<?php

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\StartingBalance;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;
use AsevenTeam\LaravelAccounting\Services\ReportService;

return [
    'models' => [
        /**
         * The model class for the accounting account. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\Account` class.
         */
        'account' => Account::class,

        /**
         * The model class for the accounting transaction. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\Transaction` class.
         */
        'transaction' => Transaction::class,

        /**
         * The model class for the accounting transaction line. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\TransactionLine` class.
         */
        'transaction_line' => TransactionLine::class,

        /**
         * The model class for the accounting ledger. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\Ledger` class.
         */
        'ledger' => Ledger::class,

        /**
         * The model class for the accounting starting balance. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\StartingBalance` class.
         */
        'starting_balance' => StartingBalance::class,
    ],

    'table_names' => [
        /**
         * The table name for the accounts table. We provide a default value for this but
         * you can change it to any table name you like.
         */
        'accounts' => 'accounts',

        /**
         * The table name for the transactions table. We provide a default value for this but
         * you can change it to any table name you like.
         */
        'transactions' => 'transactions',

        /**
         * The table name for the transaction lines table. We provide a default value for this but
         * you can change it to any table name you like.
         */
        'transaction_lines' => 'transaction_lines',

        /**
         * The table name for the ledgers table. We provide a default value for this but
         * you can change it to any table name you like.
         */
        'ledgers' => 'ledgers',

        /**
         * The table name for the starting balances table. We provide a default value for this but
         * you can change it to any table name you like.
         */
        'starting_balances' => 'starting_balances',
    ],

    /**
     * The service class for the accounting service. You can use your own service class
     * by implementing `AsevenTeam\LaravelAccounting\Contracts\ReportService` interface.
     */
    'report_service' => ReportService::class,
];
