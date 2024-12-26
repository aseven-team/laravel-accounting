<?php

namespace AsevenTeam\LaravelAccounting\Commands;

use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Console\Command;

class SyncLedgerCommand extends Command
{
    protected $signature = 'accounting:sync-ledger';

    protected $description = 'Sync the ledger with the transactions';

    public function handle(): void
    {
        $this->info('Clearing ledger...');

        Ledger::query()->truncate();

        $this->info('Syncing ledger...');

        $transactions = Transaction::query()
            ->with('lines')
            ->orderBy('date')
            ->cursor();

        $bar = $this->output->createProgressBar($transactions->count());
        $bar->start();

        foreach ($transactions as $transaction) {
            app(PostTransactionToLedger::class)->handle($transaction);

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info('Ledger synced successfully.');
    }
}
