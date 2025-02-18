<?php

namespace AsevenTeam\LaravelAccounting\Commands;

use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Facades\Accounting;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SyncLedgerCommand extends Command
{
    protected $signature = 'accounting:sync-ledger
                            {--start-date= : The start date of the transactions to sync}';

    protected $description = 'Sync the ledger with the transactions';

    public function handle(): void
    {
        /** @var ?Carbon $startDate */
        $startDate = $this->option('start-date') ? Carbon::parse($this->option('start-date'))->startOfDay() : null;

        $this->info('Clearing ledger...');

        if ($startDate) {
            Accounting::getLedgerClass()::query()->where('date', '>=', $startDate)->delete();
        } else {
            Accounting::getLedgerClass()::query()->truncate();
        }

        $this->info('Syncing ledger...');

        $transactions = Accounting::getTransactionClass()::query()
            ->with('lines')
            ->when($startDate, fn ($query) => $query->where('date', '>=', $startDate))
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
