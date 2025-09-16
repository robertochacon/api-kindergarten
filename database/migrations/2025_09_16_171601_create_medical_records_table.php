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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kid_id')->constrained('kids')->onDelete('cascade');
            
            // PRENATAL HISTORY
            $table->boolean('low_risk')->nullable();
            $table->boolean('high_risk')->nullable();
            $table->boolean('preeclampsia')->nullable();
            $table->boolean('bleeding')->nullable();
            $table->boolean('infections')->nullable();
            $table->boolean('teenage_mother')->nullable();
            $table->boolean('full_term_newborn')->nullable();
            $table->boolean('premature_newborn')->nullable();
            $table->string('weeks_at_birth')->nullable();
            
            // BIRTH
            $table->boolean('vaginal_delivery')->nullable();
            $table->boolean('cesarean_delivery')->nullable();
            $table->boolean('cried_at_birth')->nullable();
            $table->string('birth_weight')->nullable();
            $table->string('birth_length')->nullable();
            $table->string('head_circumference')->nullable();
            $table->boolean('birth_complications')->nullable();
            $table->text('complications_description')->nullable();
            $table->string('hospitalization_time')->nullable();
            $table->boolean('incubator')->nullable();
            $table->boolean('oxygen_therapy')->nullable();
            $table->boolean('mechanical_ventilation')->nullable();
            
            // DEVELOPMENT AND NUTRITION
            $table->boolean('breastfeeding_at_birth')->nullable();
            $table->string('breastfeeding_duration')->nullable();
            $table->string('complementary_feeding_start')->nullable();
            $table->boolean('iron_supplement')->nullable();
            $table->boolean('vitamin_a_supplement')->nullable();
            $table->boolean('multivitamin_supplement')->nullable();
            $table->boolean('appetite_stimulants')->nullable();
            $table->boolean('eats_independently')->nullable();
            $table->string('eating_frequency')->nullable();
            $table->boolean('good_appetite')->nullable();
            $table->boolean('food_allergy')->nullable();
            
            // PSYCHOMOTOR ASPECTS
            $table->boolean('controls_sphincters')->nullable();
            $table->boolean('uses_diapers')->nullable();
            $table->boolean('diagnosed_disability')->nullable();
            $table->text('disability_description')->nullable();
            $table->text('pathological_history')->nullable();
            $table->boolean('medication_allergy')->nullable();
            $table->text('permanent_medication')->nullable();
            $table->boolean('previous_diseases')->nullable();
            $table->text('disease_type')->nullable();
            $table->boolean('hospitalized')->nullable();
            $table->string('times_hospitalized')->nullable();
            $table->boolean('anemia')->nullable();
            $table->boolean('fever')->nullable();
            $table->boolean('previous_infections')->nullable();
            $table->boolean('dehydration')->nullable();
            $table->boolean('appears_malnourished')->nullable();
            $table->boolean('accidents')->nullable();
            $table->boolean('surgeries')->nullable();
            
            // PHYSICAL EXAMINATION
            $table->text('head')->nullable();
            $table->text('fontanelles')->nullable();
            $table->text('face')->nullable();
            $table->text('eyes_ears_nose')->nullable();
            $table->text('mouth')->nullable();
            $table->text('neck')->nullable();
            $table->text('chest')->nullable();
            $table->text('abdomen')->nullable();
            $table->text('upper_lower_extremities')->nullable();
            $table->text('genitourinary')->nullable();
            $table->text('skin')->nullable();
            $table->string('current_weight_lbs')->nullable();
            $table->string('current_height_cms')->nullable();
            $table->string('arm_fold_measurement')->nullable();
            $table->string('bmi')->nullable();
            $table->string('nutritional_status')->nullable();
            
            // DOCTOR DATA
            $table->string('doctor_signature')->nullable();
            $table->string('medical_license')->nullable();
            $table->string('medical_college_registration')->nullable();
            $table->text('growth_development_chart')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
