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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productID')->constrained('products', 'id');
            $table->foreignId('fromID')->constrained('warehouses', 'id');
            $table->foreignId('toID')->constrained('warehouses', 'id');
            $table->date('date');
            $table->float('qty');
            $table->integer('ref');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
