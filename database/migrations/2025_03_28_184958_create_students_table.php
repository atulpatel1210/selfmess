<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hostel_name');
            $table->string('room_no');
            $table->string('email')->unique();
            $table->string('residential_address')->nullable();
            $table->string('currently_pursuing');
            $table->integer('currently_studying_year');
            $table->date('date');
            $table->integer('year');
            $table->string('mobile')->unique();
            $table->string('alternative_mobile')->nullable();
            $table->string('advisor_guide')->nullable();
            $table->string('blood_group')->nullable();
            $table->float('deposit')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};