<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('accounting.table_names');

        Schema::create($tableNames['ledgers'], function (Blueprint $table) use ($tableNames) {
            $table->id();
            $table->foreignId('transaction_id')->constrained($tableNames['transactions'])->cascadeOnDelete();
            $table->foreignId('transaction_line_id')->constrained($tableNames['transaction_lines'])->cascadeOnDelete();
            $table->foreignId('account_id')->constrained($tableNames['accounts'])->cascadeOnDelete();
            $table->date('date')->index();
            $table->string('transaction_title');
            $table->text('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit_balance', 15, 2)->default(0);
            $table->decimal('credit_balance', 15, 2)->default(0);
            $table->timestamps();
        });
    }
};
