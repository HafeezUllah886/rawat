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
        Schema::create('returned_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saleID')->constrained('sales', 'id');
            $table->foreignId('productID')->constrained('products', 'id');
            $table->float('qty');
            $table->float('rate');
            $table->float('amount');
            $table->date('date');
            $table->foreignId('userID')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returned_sales');
    }
};
