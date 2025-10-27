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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer');
            $table->unsignedBigInteger('paidIn')->nullable();
            $table->timestamp('date', $precision = 0);
            $table->text('desc')->nullable();
            $table->string('isPaid');
            $table->unsignedBigInteger('amount')->nullable();
            $table->unsignedInteger('discount')->nullable();
            $table->unsignedInteger('dc')->nullable();
            $table->unsignedBigInteger('ref');
            $table->foreignId('warehouseID')->constrained('warehouses', 'id');
            $table->foreign('customer')->references('id')->on('accounts');
            $table->foreign('paidIn')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
