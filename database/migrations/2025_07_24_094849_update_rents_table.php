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
        Schema::table('rents', function (Blueprint $table) {
            $table->renameColumn('amount', 'amount_due');
            $table->decimal('amount_received', 8, 2)->after('amount_due');
            $table->decimal('amount_remaining', 8, 2)->after('amount_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->renameColumn('amount_due', 'amount');
            $table->dropColumn(['amount_received', 'amount_remaining']);
        });
    }
};
