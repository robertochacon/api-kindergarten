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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("type_identification")->nullable();
            $table->string("identification")->nullable();
            $table->string("parent")->nullable();
            $table->string("marital_status")->nullable();
            $table->string("phone")->nullable();
            $table->string("residence_phone")->nullable();
            $table->string("address")->nullable();
            $table->boolean("military")->default(true);
            $table->string("institution")->nullable();
            $table->string("email")->nullable();
            $table->string("work_reference")->nullable();
            $table->string("personal_reference_1")->nullable();
            $table->string("personal_reference_2")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
