<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('accounting.table_names');

        Schema::create($tableNames['accounts'], function (Blueprint $table) use ($tableNames) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained($tableNames['accounts'])->restrictOnDelete();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('type', 20);
            $table->string('normal_balance', 6);
            $table->text('description')->nullable();
            $table->string('status', 20);
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });

        Schema::create($tableNames['transactions'], function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('reference');
            $table->unsignedBigInteger('sequence');
            $table->string('number', 20)->unique();
            $table->date('date')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create($tableNames['transaction_lines'], function (Blueprint $table) use ($tableNames) {
            $table->id();
            $table->foreignId('transaction_id')->constrained($tableNames['transactions'])->cascadeOnDelete();
            $table->foreignId('account_id')->constrained($tableNames['accounts'])->restrictOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
