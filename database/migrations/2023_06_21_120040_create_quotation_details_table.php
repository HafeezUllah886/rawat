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
        Schema::create('quotation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quot');
            $table->unsignedBigInteger('product');
            $table->unsignedInteger('qty');
            $table->unsignedInteger('price');
            $table->float('discount', 5,2)->default(0);
            $table->unsignedInteger('ref');
            $table->foreign('quot')->references('id')->on('quotations');
            $table->foreign('product')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_details');
    }
};
