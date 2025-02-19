<?php

return [
    'models' => [
        /**
         * The model class for the accounting account. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\Account` class.
         */
        'account' => AsevenTeam\LaravelAccounting\Models\Account::class,

        /**
         * The model class for the accounting transaction. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\Transaction` class.
         */
        'transaction' => AsevenTeam\LaravelAccounting\Models\Transaction::class,

        /**
         * The model class for the accounting transaction line. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\TransactionLine` class.
         */
        'transaction_line' => AsevenTeam\LaravelAccounting\Models\TransactionLine::class,

        /**
         * The model class for the accounting ledger. You can use your own model class
         * by extending `AsevenTeam\LaravelAccounting\Models\Ledger` class.
         */
        'ledger' => AsevenTeam\LaravelAccounting\Models\Ledger::class,
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
    ],

    /**
     * The service class for the accounting service. You can use your own service class
     * by implementing `AsevenTeam\LaravelAccounting\Contracts\ReportService` interface.
     */
    'report_service' => AsevenTeam\LaravelAccounting\Services\ReportService::class,
];
