<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->integer('total_day');
            $table->integer('total_eat_day');
            $table->integer('cut_day');
            $table->float('amount')->default(0);
            $table->date('date');
            $table->integer('simple_guest')->default(0);
            $table->float('simple_guest_amount')->default(0);
            $table->integer('feast_guest')->default(0);
            $table->float('feast_guest_amount')->default(0);
            $table->float('due_amount')->default(0);
            $table->float('penalty_amount')->default(0);
            $table->float('total_amount')->default(0);
            $table->float('paid_amount')->default(0);
            $table->float('remain_amount')->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
};
