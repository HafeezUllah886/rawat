<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->timestamp('date', $precision = 0);
            $table->unsignedBigInteger('paidBy')->nullable();
            $table->unsignedDecimal('deduction')->nullable();
            $table->unsignedDecimal('amount');
            $table->unsignedInteger('ref');
            $table->foreignId('warehouseID')->constrained('warehouses', 'id');
            $table->foreign('bill_id')->references('id')->on('sales');
            $table->foreign('paidBy')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
