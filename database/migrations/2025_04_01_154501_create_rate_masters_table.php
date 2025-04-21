<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_masters', function (Blueprint $table) {
            $table->id();
            $table->float('rate');
            $table->float('simple_guest_rate')->default(0);
            $table->float('feast_guest_rate')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_masters');
    }
};