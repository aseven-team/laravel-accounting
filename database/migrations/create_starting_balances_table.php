<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('accounting.table_names');

        Schema::create($tableNames['starting_balances'], function (Blueprint $table) use ($tableNames) {
            $table->id();
            $table->foreignId('account_id')->constrained($tableNames['accounts'])->cascadeOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();
        });
    }
};
