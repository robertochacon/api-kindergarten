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
        Schema::create('authorized_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string("name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("identification")->nullable();
            $table->string("parent")->nullable();
            $table->string("phone")->nullable();
            $table->string("residence_phone")->nullable();
            $table->string("address")->nullable();
            $table->boolean("military")->default(true);
            $table->string("file")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorized_persons');
    }
};
