<?php

namespace AsevenTeam\LaravelAccounting\Commands;

use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
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
            Ledger::query()->where('date', '>=', $startDate)->delete();
        } else {
            Ledger::query()->truncate();
        }

        $this->info('Syncing ledger...');

        $transactions = Transaction::query()
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
