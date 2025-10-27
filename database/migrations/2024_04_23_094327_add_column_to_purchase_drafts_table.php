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
        Schema::table('purchase_drafts', function (Blueprint $table) {
            $table->float('retail', 10, 2)->nullable()->after('rate');
            $table->float('wholesale', 10, 2)->nullable()->after('retail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_drafts', function (Blueprint $table) {
            $table->float('retail', 10, 2);
            $table->float('wholesale', 10, 2);
        });
    }
};
