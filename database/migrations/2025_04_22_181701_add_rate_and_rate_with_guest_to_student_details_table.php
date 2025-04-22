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
        Schema::table('student_details', function (Blueprint $table) {
            $table->decimal('rate', 10, 2)->default(0.00);
            $table->decimal('rate_with_guest', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'generated', 'lock'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            $table->dropColumn(['rate', 'rate_with_guest', 'status']);
        });
    }
};
