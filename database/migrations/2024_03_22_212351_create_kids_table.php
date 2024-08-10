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
        Schema::create('kids', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("last_name")->nullable();
            $table->enum("gender",["Masculino","Femenino"])->default("Masculino");
            $table->date("born_date")->nullable();
            $table->string("address")->nullable();
            $table->string("region")->nullable();
            $table->string("province")->nullable();
            $table->string("municipality")->nullable();
            $table->string("district")->nullable();
            $table->string("sections")->nullable();
            $table->string("neighborhood")->nullable();
            $table->string("classroom")->nullable();
            $table->string("insurance")->nullable();
            $table->string("insurance_number")->nullable();
            $table->text("allergies")->nullable();
            $table->text("medical_conditions")->nullable();
            $table->text("medications")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kids');
    }
};
