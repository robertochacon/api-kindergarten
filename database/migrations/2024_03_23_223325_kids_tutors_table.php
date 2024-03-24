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
        Schema::create('kids_tutors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kids_id')->nullable();
            $table->foreign('kids_id')->references('id')->on('kids');
            $table->unsignedBigInteger('tutors_id')->nullable();
            $table->foreign('tutors_id')->references('id')->on('tutors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kids_tutors');
    }
};
