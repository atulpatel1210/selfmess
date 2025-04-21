<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('attendance_date');
            $table->boolean('is_present')->default(false);
            $table->boolean('is_feast_day')->default(false);
            $table->float('student_charge')->default(0);
            $table->integer('simple_guest_count')->default(0);
            $table->float('simple_guest_charge')->default(0);
            $table->integer('feast_guest_count')->default(0);
            $table->float('feast_guest_charge')->default(0);
            $table->text('remark')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->unique(['student_id', 'attendance_date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};