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
        Schema::create('monthly_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('bill_date')->index(); // Added index for potential querying
            $table->smallInteger('year')->unsigned()->index();
            $table->tinyInteger('month')->unsigned()->index();
            $table->decimal('current_month_expense', 10, 2)->default(0.00);
            $table->decimal('current_total_collection', 10, 2)->default(0.00);
            $table->decimal('current_month_total_guest_amount', 10, 2)->default(0.00);
            $table->decimal('current_month_total_cash_on_hand', 10, 2)->default(0.00);            
            $table->decimal('current_month_total_amount', 10, 2)->default(0.00);
            $table->decimal('current_total_remaining', 10, 2)->default(0.00);
            $table->decimal('current_month_total_eat_day', 10, 2)->default(0.00);
            $table->decimal('current_month_total_cut_day', 10, 2)->default(0.00);
            $table->decimal('current_month_total_day', 10, 2)->default(0.00);
            $table->decimal('current_month_profit', 10, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['year', 'month'], 'unique_month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_transactions');
    }
};
