<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'kid_id',
        // PRENATAL HISTORY
        'low_risk',
        'high_risk',
        'preeclampsia',
        'bleeding',
        'infections',
        'teenage_mother',
        'full_term_newborn',
        'premature_newborn',
        'weeks_at_birth',
        
        // BIRTH
        'vaginal_delivery',
        'cesarean_delivery',
        'cried_at_birth',
        'birth_weight',
        'birth_length',
        'head_circumference',
        'birth_complications',
        'complications_description',
        'hospitalization_time',
        'incubator',
        'oxygen_therapy',
        'mechanical_ventilation',
        
        // DEVELOPMENT AND NUTRITION
        'breastfeeding_at_birth',
        'breastfeeding_duration',
        'complementary_feeding_start',
        'iron_supplement',
        'vitamin_a_supplement',
        'multivitamin_supplement',
        'appetite_stimulants',
        'eats_independently',
        'eating_frequency',
        'good_appetite',
        'food_allergy',
        
        // PSYCHOMOTOR ASPECTS
        'controls_sphincters',
        'uses_diapers',
        'diagnosed_disability',
        'disability_description',
        'pathological_history',
        'medication_allergy',
        'permanent_medication',
        'previous_diseases',
        'disease_type',
        'hospitalized',
        'times_hospitalized',
        'anemia',
        'fever',
        'previous_infections',
        'dehydration',
        'appears_malnourished',
        'accidents',
        'surgeries',
        
        // PHYSICAL EXAMINATION
        'head',
        'fontanelles',
        'face',
        'eyes_ears_nose',
        'mouth',
        'neck',
        'chest',
        'abdomen',
        'upper_lower_extremities',
        'genitourinary',
        'skin',
        'current_weight_lbs',
        'current_height_cms',
        'arm_fold_measurement',
        'bmi',
        'nutritional_status',
        
        // DOCTOR DATA
        'doctor_signature',
        'medical_license',
        'medical_college_registration',
        'growth_development_chart'
    ];

    protected $casts = [
        'low_risk' => 'boolean',
        'high_risk' => 'boolean',
        'preeclampsia' => 'boolean',
        'bleeding' => 'boolean',
        'infections' => 'boolean',
        'teenage_mother' => 'boolean',
        'full_term_newborn' => 'boolean',
        'premature_newborn' => 'boolean',
        'vaginal_delivery' => 'boolean',
        'cesarean_delivery' => 'boolean',
        'cried_at_birth' => 'boolean',
        'birth_complications' => 'boolean',
        'incubator' => 'boolean',
        'oxygen_therapy' => 'boolean',
        'mechanical_ventilation' => 'boolean',
        'breastfeeding_at_birth' => 'boolean',
        'iron_supplement' => 'boolean',
        'vitamin_a_supplement' => 'boolean',
        'multivitamin_supplement' => 'boolean',
        'appetite_stimulants' => 'boolean',
        'eats_independently' => 'boolean',
        'good_appetite' => 'boolean',
        'food_allergy' => 'boolean',
        'controls_sphincters' => 'boolean',
        'uses_diapers' => 'boolean',
        'diagnosed_disability' => 'boolean',
        'medication_allergy' => 'boolean',
        'previous_diseases' => 'boolean',
        'hospitalized' => 'boolean',
        'anemia' => 'boolean',
        'fever' => 'boolean',
        'previous_infections' => 'boolean',
        'dehydration' => 'boolean',
        'appears_malnourished' => 'boolean',
        'accidents' => 'boolean',
        'surgeries' => 'boolean',
    ];

    public function kid()
    {
        return $this->belongsTo(Kids::class, 'kid_id', 'id');
    }
}