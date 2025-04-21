<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('date');
            $table->integer('total_day');
            $table->integer('eat_day');
            $table->integer('cut_day');
            $table->float('student_charge')->default(0);
            $table->integer('simple_guest')->default(0);
            $table->float('simple_guest_charge')->default(0);
            $table->integer('feast_guest')->default(0);
            $table->float('feast_guest_charge')->default(0);
            $table->float('due_amount')->default(0);
            $table->float('penalty_amount')->default(0);
            $table->float('total_bill')->default(0);
            $table->float('paid_bill')->default(0);
            $table->float('remain_amount')->default(0);
            $table->text('remark')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_summaries');
    }
};