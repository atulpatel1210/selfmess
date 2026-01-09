<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('day_meals', function (Blueprint $table) {
            $table->id();
            $table->enum('day', [
                'monday','tuesday','wednesday',
                'thursday','friday','saturday','sunday'
            ])->unique();

            $table->text('breakfast')->nullable();
            $table->text('lunch')->nullable();
            $table->text('dinner')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('day_meals');
    }
};
