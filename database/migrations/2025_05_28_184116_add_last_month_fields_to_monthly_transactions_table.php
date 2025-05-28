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
        Schema::table('monthly_transactions', function (Blueprint $table) {
            $table->decimal('last_month_total_collection', 10, 2)->nullable()->after('current_month_total_day');
            $table->decimal('last_month_total_case_on_hand', 10, 2)->nullable()->after('last_month_total_collection');
            $table->decimal('last_month_total_cash_guest_amount', 10, 2)->nullable()->after('last_month_total_case_on_hand');
            $table->decimal('last_month_total_amount', 10, 2)->nullable()->after('last_month_total_cash_guest_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'last_month_total_collection',
                'last_month_total_case_on_hand',
                'last_month_total_cash_guest_amount',
                'last_month_total_amount'
            ]);
        });
    }
};
