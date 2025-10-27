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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer')->nullable();
            $table->string("walkIn")->nullable();
            $table->timestamp('date', $precision = 0);
            $table->unsignedInteger('discount')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('validTill');
            $table->text('desc')->nullable();
            $table->foreignId('warehouseID')->constrained('warehouses', 'id');
            $table->unsignedInteger('ref');
            $table->foreign('customer')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
