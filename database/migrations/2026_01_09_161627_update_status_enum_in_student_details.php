<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            DB::statement("ALTER TABLE student_details MODIFY COLUMN status ENUM('pending', 'generated', 'lock', 'finalize') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            DB::statement("ALTER TABLE student_details MODIFY COLUMN status ENUM('pending', 'generated', 'lock') DEFAULT 'pending'");
        });
    }
};
