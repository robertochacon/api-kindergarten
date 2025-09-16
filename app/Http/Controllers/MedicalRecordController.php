<?php

namespace App\Http\Controllers;

use App\Models\Kids;
use App\Models\MedicalRecord;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/medical-records",
     *     operationId="all_medical_records",
     *     tags={"Medical Records"},
     *     summary="Get all medical records",
     *     description="Retrieve all medical records with pagination and filtering options",
     *     @OA\Parameter(
     *         in="query",
     *         name="kid_id",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by kid ID"
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1),
     *         description="Page number for pagination"
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="per_page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10),
     *         description="Number of items per page"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=25)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No medical records found")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::with('kid');

        if ($request->has('kid_id')) {
            $query->where('kid_id', $request->input('kid_id'));
        }

        $perPage = $request->input('per_page', 10);
        $medicalRecords = $query->paginate($perPage);

        return response()->json(["data" => $medicalRecords], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/medical-records/{id}",
     *     operationId="show_medical_record",
     *     tags={"Medical Records"},
     *     summary="Get medical record by ID",
     *     description="Retrieve a specific medical record with all its details",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Medical record ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="kid_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="kid",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan"),
     *                 @OA\Property(property="last_name", type="string", example="Pérez")
     *             ),
     *             @OA\Property(property="low_risk", type="boolean", example=true),
     *             @OA\Property(property="high_risk", type="boolean", example=false),
     *             @OA\Property(property="preeclampsia", type="boolean", example=false),
     *             @OA\Property(property="bleeding", type="boolean", example=false),
     *             @OA\Property(property="infections", type="boolean", example=false),
     *             @OA\Property(property="teenage_mother", type="boolean", example=false),
     *             @OA\Property(property="full_term_newborn", type="boolean", example=true),
     *             @OA\Property(property="premature_newborn", type="boolean", example=false),
     *             @OA\Property(property="weeks_at_birth", type="string", example="40"),
     *             @OA\Property(property="vaginal_delivery", type="boolean", example=true),
     *             @OA\Property(property="cesarean_deliveryn_delivery", type="boolean", example=false),
     *             @OA\Property(property="cried_at_birth", type="boolean", example=true),
     *             @OA\Property(property="birth_weight", type="string", example="3.2 kg"),
     *             @OA\Property(property="birth_length", type="string", example="50 cm"),
     *             @OA\Property(property="head_circumference", type="string", example="35 cm"),
     *             @OA\Property(property="birth_complications", type="boolean", example=false),
     *             @OA\Property(property="complications_description", type="string", example=""),
     *             @OA\Property(property="hospitalization_time", type="string", example="2 days"),
     *             @OA\Property(property="incubator", type="boolean", example=false),
     *             @OA\Property(property="oxygen_therapy", type="boolean", example=false),
     *             @OA\Property(property="mechanical_ventilation", type="boolean", example=false),
     *             @OA\Property(property="breastfeeding_at_birth", type="boolean", example=true),
     *             @OA\Property(property="breastfeeding_duration", type="string", example="6 months"),
     *             @OA\Property(property="complementary_feeding_start", type="string", example="6 months"),
     *             @OA\Property(property="iron_supplement", type="boolean", example=true),
     *             @OA\Property(property="vitamin_a_supplement", type="boolean", example=false),
     *             @OA\Property(property="multivitamin_supplement", type="boolean", example=false),
     *             @OA\Property(property="appetite_stimulants", type="boolean", example=false),
     *             @OA\Property(property="eats_independently", type="boolean", example=true),
     *             @OA\Property(property="eating_frequency", type="string", example="3 times a day"),
     *             @OA\Property(property="good_appetite", type="boolean", example=true),
     *             @OA\Property(property="food_allergy", type="boolean", example=false),
     *             @OA\Property(property="controls_sphincters", type="boolean", example=true),
     *             @OA\Property(property="uses_diapers", type="boolean", example=false),
     *             @OA\Property(property="diagnosed_disability", type="boolean", example=false),
     *             @OA\Property(property="disability_description", type="string", example=""),
     *             @OA\Property(property="pathological_history", type="string", example="None"),
     *             @OA\Property(property="medication_allergy", type="boolean", example=false),
     *             @OA\Property(property="permanent_medication", type="string", example=""),
     *             @OA\Property(property="previous_diseases", type="boolean", example=false),
     *             @OA\Property(property="disease_type", type="string", example=""),
     *             @OA\Property(property="hospitalized", type="boolean", example=false),
     *             @OA\Property(property="times_hospitalized", type="string", example=""),
     *             @OA\Property(property="anemia", type="boolean", example=false),
     *             @OA\Property(property="fever", type="boolean", example=false),
     *             @OA\Property(property="previous_infections", type="boolean", example=false),
     *             @OA\Property(property="dehydration", type="boolean", example=false),
     *             @OA\Property(property="appears_malnourished", type="boolean", example=false),
     *             @OA\Property(property="accidents", type="boolean", example=false),
     *             @OA\Property(property="surgeries", type="boolean", example=false),
     *             @OA\Property(property="head", type="string", example="Normal"),
     *             @OA\Property(property="fontanelles", type="string", example="Closed"),
     *             @OA\Property(property="face", type="string", example="Normal"),
     *             @OA\Property(property="eyes_ears_nose", type="string", example="Normal"),
     *             @OA\Property(property="mouth", type="string", example="Normal"),
     *             @OA\Property(property="neck", type="string", example="Normal"),
     *             @OA\Property(property="chest", type="string", example="Normal"),
     *             @OA\Property(property="abdomen", type="string", example="Normal"),
     *             @OA\Property(property="upper_lower_extremities", type="string", example="Normal"),
     *             @OA\Property(property="genitourinary", type="string", example="Normal"),
     *             @OA\Property(property="skin", type="string", example="Normal"),
     *             @OA\Property(property="current_weight_lbs", type="string", example="25 lbs"),
     *             @OA\Property(property="current_height_cms", type="string", example="100 cm"),
     *             @OA\Property(property="arm_fold_measurement", type="string", example="Normal"),
     *             @OA\Property(property="bmi", type="string", example="15.5"),
     *             @OA\Property(property="nutritional_status", type="string", example="Normal"),
     *             @OA\Property(property="doctor_signature", type="string", example="Dr. María González"),
     *             @OA\Property(property="medical_license", type="string", example="12345"),
     *             @OA\Property(property="medical_college_registration", type="string", example="CMD-001"),
     *             @OA\Property(property="growth_development_chart", type="string", example="According to WHO chart"),
     *             @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $medicalRecord = MedicalRecord::with('kid')->find($id);
            
            if (!$medicalRecord) {
                return response()->json(['message' => 'Medical record not found'], 404);
            }

            return response()->json(["data" => $medicalRecord], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "Error retrieving medical record"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/medical-records",
     *     operationId="store_medical_record",
     *     tags={"Medical Records"},
     *     summary="Create medical record",
     *     description="Create a new medical record for a kid",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"kid_id"},
     *             @OA\Property(property="kid_id", type="integer", example=1, description="ID of the kid"),
     *             @OA\Property(property="low_risk", type="boolean", example=true, description="Low risk pregnancy"),
     *             @OA\Property(property="high_risk", type="boolean", example=false, description="High risk pregnancy"),
     *             @OA\Property(property="preeclampsia", type="boolean", example=false, description="Preeclampsia during pregnancy"),
     *             @OA\Property(property="bleeding", type="boolean", example=false, description="Bleeding during pregnancy"),
     *             @OA\Property(property="infections", type="boolean", example=false, description="Infections during pregnancy"),
     *             @OA\Property(property="teenage_mother", type="boolean", example=false, description="Teenage mother"),
     *             @OA\Property(property="full_term_newborn", type="boolean", example=true, description="Full-term newborn"),
     *             @OA\Property(property="premature_newborn", type="boolean", example=false, description="Premature newborn"),
     *             @OA\Property(property="weeks_at_birth", type="string", example="40", description="Weeks at birth"),
     *             @OA\Property(property="vaginal_delivery", type="boolean", example=true, description="Vaginal delivery"),
     *             @OA\Property(property="cesarean_delivery", type="boolean", example=false, description="Cesarean delivery"),
     *             @OA\Property(property="cried_at_birth", type="boolean", example=true, description="Cried at birth"),
     *             @OA\Property(property="birth_weight", type="string", example="3.2 kg", description="Birth weight"),
     *             @OA\Property(property="birth_length", type="string", example="50 cm", description="Birth length"),
     *             @OA\Property(property="head_circumference", type="string", example="35 cm", description="Head circumference"),
     *             @OA\Property(property="birth_complications", type="boolean", example=false, description="Complications at birth"),
     *             @OA\Property(property="complications_description", type="string", example="", description="Description of complications"),
     *             @OA\Property(property="hospitalization_time", type="string", example="2 days", description="Hospitalization time"),
     *             @OA\Property(property="incubator", type="boolean", example=false, description="Incubator use"),
     *             @OA\Property(property="oxygen_therapy", type="boolean", example=false, description="Oxygen therapy"),
     *             @OA\Property(property="mechanical_ventilation", type="boolean", example=false, description="Mechanical ventilation"),
     *             @OA\Property(property="breastfeeding_at_birth", type="boolean", example=true, description="Breastfeeding at birth"),
     *             @OA\Property(property="breastfeeding_duration", type="string", example="6 months", description="Breastfeeding duration"),
     *             @OA\Property(property="complementary_feeding_start", type="string", example="6 months", description="Complementary feeding start"),
     *             @OA\Property(property="iron_supplement", type="boolean", example=true, description="Iron supplement"),
     *             @OA\Property(property="vitamin_a_supplement", type="boolean", example=false, description="Vitamin A supplement"),
     *             @OA\Property(property="multivitamin_supplement", type="boolean", example=false, description="Multivitamin supplement"),
     *             @OA\Property(property="appetite_stimulants", type="boolean", example=false, description="Appetite stimulants"),
     *             @OA\Property(property="eats_independently", type="boolean", example=true, description="Eats independently"),
     *             @OA\Property(property="eating_frequency", type="string", example="3 times a day", description="Eating frequency"),
     *             @OA\Property(property="good_appetite", type="boolean", example=true, description="Good appetite"),
     *             @OA\Property(property="food_allergy", type="boolean", example=false, description="Food allergy"),
     *             @OA\Property(property="controls_sphincters", type="boolean", example=true, description="Controls sphincters"),
     *             @OA\Property(property="uses_diapers", type="boolean", example=false, description="Uses diapers"),
     *             @OA\Property(property="diagnosed_disability", type="boolean", example=false, description="Diagnosed disability"),
     *             @OA\Property(property="disability_description", type="string", example="", description="Disability description"),
     *             @OA\Property(property="pathological_history", type="string", example="None", description="Pathological history"),
     *             @OA\Property(property="medication_allergy", type="boolean", example=false, description="Medication allergy"),
     *             @OA\Property(property="permanent_medication", type="string", example="", description="Permanent medication"),
     *             @OA\Property(property="previous_diseases", type="boolean", example=false, description="Previous diseases"),
     *             @OA\Property(property="disease_type", type="string", example="", description="Type of disease"),
     *             @OA\Property(property="hospitalized", type="boolean", example=false, description="Hospitalized"),
     *             @OA\Property(property="times_hospitalized", type="string", example="", description="Times hospitalized"),
     *             @OA\Property(property="anemia", type="boolean", example=false, description="Anemia"),
     *             @OA\Property(property="fever", type="boolean", example=false, description="Fever"),
     *             @OA\Property(property="previous_infections", type="boolean", example=false, description="Previous infections"),
     *             @OA\Property(property="dehydration", type="boolean", example=false, description="Dehydration"),
     *             @OA\Property(property="appears_malnourished", type="boolean", example=false, description="Appears malnourished"),
     *             @OA\Property(property="accidents", type="boolean", example=false, description="Accidents"),
     *             @OA\Property(property="surgeries", type="boolean", example=false, description="Surgeries"),
     *             @OA\Property(property="head", type="string", example="Normal", description="Head examination"),
     *             @OA\Property(property="fontanelles", type="string", example="Closed", description="Fontanelles"),
     *             @OA\Property(property="face", type="string", example="Normal", description="Face examination"),
     *             @OA\Property(property="eyes_ears_nose", type="string", example="Normal", description="Eyes, ears, nose examination"),
     *             @OA\Property(property="mouth", type="string", example="Normal", description="Mouth examination"),
     *             @OA\Property(property="neck", type="string", example="Normal", description="Neck examination"),
     *             @OA\Property(property="chest", type="string", example="Normal", description="Chest examination"),
     *             @OA\Property(property="abdomen", type="string", example="Normal", description="Abdomen examination"),
     *             @OA\Property(property="upper_lower_extremities", type="string", example="Normal", description="Upper and lower extremities examination"),
     *             @OA\Property(property="genitourinary", type="string", example="Normal", description="Genitourinary examination"),
     *             @OA\Property(property="skin", type="string", example="Normal", description="Skin examination"),
     *             @OA\Property(property="current_weight_lbs", type="string", example="25 lbs", description="Current weight in pounds"),
     *             @OA\Property(property="current_height_cms", type="string", example="100 cm", description="Current height in centimeters"),
     *             @OA\Property(property="arm_fold_measurement", type="string", example="Normal", description="Arm fold measurement"),
     *             @OA\Property(property="bmi", type="string", example="15.5", description="Body Mass Index"),
     *             @OA\Property(property="nutritional_status", type="string", example="Normal", description="Nutritional status"),
     *             @OA\Property(property="doctor_signature", type="string", example="Dr. María González", description="Doctor's signature"),
     *             @OA\Property(property="medical_license", type="string", example="12345", description="Medical license number"),
     *             @OA\Property(property="cmd", type="string", example="CMD-001", description="Medical college registration"),
     *             @OA\Property(property="growth_development_chart", type="string", example="According to WHO chart", description="Growth and development chart")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kid not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            // Validate that the kid exists
            if (!Kids::find($request->input('kid_id'))) {
                return response()->json(['message' => 'Kid not found'], 404);
            }

            // Check if medical record already exists for this kid
            $existingRecord = MedicalRecord::where('kid_id', $request->input('kid_id'))->first();
            if ($existingRecord) {
                return response()->json(['message' => 'Medical record already exists for this kid'], 409);
            }

            $medicalRecord = MedicalRecord::create($request->all());
            $medicalRecord->load('kid');

            return response()->json([
                'message' => 'Medical record created successfully',
                'data' => $medicalRecord
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating medical record'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/medical-records/{id}",
     *     operationId="update_medical_record",
     *     tags={"Medical Records"},
     *     summary="Update medical record",
     *     description="Update an existing medical record",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Medical record ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="low_risk", type="boolean", example=true, description="Low risk pregnancy"),
     *             @OA\Property(property="high_risk", type="boolean", example=false, description="High risk pregnancy"),
     *             @OA\Property(property="preeclampsia", type="boolean", example=false, description="Preeclampsia during pregnancy"),
     *             @OA\Property(property="bleeding", type="boolean", example=false, description="Bleeding during pregnancy"),
     *             @OA\Property(property="infections", type="boolean", example=false, description="Infections during pregnancy"),
     *             @OA\Property(property="teenage_mother", type="boolean", example=false, description="Teenage mother"),
     *             @OA\Property(property="full_term_newborn", type="boolean", example=true, description="Full-term newborn"),
     *             @OA\Property(property="premature_newborn", type="boolean", example=false, description="Premature newborn"),
     *             @OA\Property(property="weeks_at_birth", type="string", example="40", description="Weeks at birth"),
     *             @OA\Property(property="vaginal_delivery", type="boolean", example=true, description="Vaginal delivery"),
     *             @OA\Property(property="cesarean_delivery", type="boolean", example=false, description="Cesarean delivery"),
     *             @OA\Property(property="cried_at_birth", type="boolean", example=true, description="Cried at birth"),
     *             @OA\Property(property="birth_weight", type="string", example="3.2 kg", description="Birth weight"),
     *             @OA\Property(property="birth_length", type="string", example="50 cm", description="Birth length"),
     *             @OA\Property(property="head_circumference", type="string", example="35 cm", description="Head circumference"),
     *             @OA\Property(property="birth_complications", type="boolean", example=false, description="Complications at birth"),
     *             @OA\Property(property="complications_description", type="string", example="", description="Description of complications"),
     *             @OA\Property(property="hospitalization_time", type="string", example="2 days", description="Hospitalization time"),
     *             @OA\Property(property="incubator", type="boolean", example=false, description="Incubator use"),
     *             @OA\Property(property="oxygen_therapy", type="boolean", example=false, description="Oxygen therapy"),
     *             @OA\Property(property="mechanical_ventilation", type="boolean", example=false, description="Mechanical ventilation"),
     *             @OA\Property(property="breastfeeding_at_birth", type="boolean", example=true, description="Breastfeeding at birth"),
     *             @OA\Property(property="breastfeeding_duration", type="string", example="6 months", description="Breastfeeding duration"),
     *             @OA\Property(property="complementary_feeding_start", type="string", example="6 months", description="Complementary feeding start"),
     *             @OA\Property(property="iron_supplement", type="boolean", example=true, description="Iron supplement"),
     *             @OA\Property(property="vitamin_a_supplement", type="boolean", example=false, description="Vitamin A supplement"),
     *             @OA\Property(property="multivitamin_supplement", type="boolean", example=false, description="Multivitamin supplement"),
     *             @OA\Property(property="appetite_stimulants", type="boolean", example=false, description="Appetite stimulants"),
     *             @OA\Property(property="eats_independently", type="boolean", example=true, description="Eats independently"),
     *             @OA\Property(property="eating_frequency", type="string", example="3 times a day", description="Eating frequency"),
     *             @OA\Property(property="good_appetite", type="boolean", example=true, description="Good appetite"),
     *             @OA\Property(property="food_allergy", type="boolean", example=false, description="Food allergy"),
     *             @OA\Property(property="controls_sphincters", type="boolean", example=true, description="Controls sphincters"),
     *             @OA\Property(property="uses_diapers", type="boolean", example=false, description="Uses diapers"),
     *             @OA\Property(property="diagnosed_disability", type="boolean", example=false, description="Diagnosed disability"),
     *             @OA\Property(property="disability_description", type="string", example="", description="Disability description"),
     *             @OA\Property(property="pathological_history", type="string", example="None", description="Pathological history"),
     *             @OA\Property(property="medication_allergy", type="boolean", example=false, description="Medication allergy"),
     *             @OA\Property(property="permanent_medication", type="string", example="", description="Permanent medication"),
     *             @OA\Property(property="previous_diseases", type="boolean", example=false, description="Previous diseases"),
     *             @OA\Property(property="disease_type", type="string", example="", description="Type of disease"),
     *             @OA\Property(property="hospitalized", type="boolean", example=false, description="Hospitalized"),
     *             @OA\Property(property="times_hospitalized", type="string", example="", description="Times hospitalized"),
     *             @OA\Property(property="anemia", type="boolean", example=false, description="Anemia"),
     *             @OA\Property(property="fever", type="boolean", example=false, description="Fever"),
     *             @OA\Property(property="previous_infections", type="boolean", example=false, description="Previous infections"),
     *             @OA\Property(property="dehydration", type="boolean", example=false, description="Dehydration"),
     *             @OA\Property(property="appears_malnourished", type="boolean", example=false, description="Appears malnourished"),
     *             @OA\Property(property="accidents", type="boolean", example=false, description="Accidents"),
     *             @OA\Property(property="surgeries", type="boolean", example=false, description="Surgeries"),
     *             @OA\Property(property="head", type="string", example="Normal", description="Head examination"),
     *             @OA\Property(property="fontanelles", type="string", example="Closed", description="Fontanelles"),
     *             @OA\Property(property="face", type="string", example="Normal", description="Face examination"),
     *             @OA\Property(property="eyes_ears_nose", type="string", example="Normal", description="Eyes, ears, nose examination"),
     *             @OA\Property(property="mouth", type="string", example="Normal", description="Mouth examination"),
     *             @OA\Property(property="neck", type="string", example="Normal", description="Neck examination"),
     *             @OA\Property(property="chest", type="string", example="Normal", description="Chest examination"),
     *             @OA\Property(property="abdomen", type="string", example="Normal", description="Abdomen examination"),
     *             @OA\Property(property="upper_lower_extremities", type="string", example="Normal", description="Upper and lower extremities examination"),
     *             @OA\Property(property="genitourinary", type="string", example="Normal", description="Genitourinary examination"),
     *             @OA\Property(property="skin", type="string", example="Normal", description="Skin examination"),
     *             @OA\Property(property="current_weight_lbs", type="string", example="25 lbs", description="Current weight in pounds"),
     *             @OA\Property(property="current_height_cms", type="string", example="100 cm", description="Current height in centimeters"),
     *             @OA\Property(property="arm_fold_measurement", type="string", example="Normal", description="Arm fold measurement"),
     *             @OA\Property(property="bmi", type="string", example="15.5", description="Body Mass Index"),
     *             @OA\Property(property="nutritional_status", type="string", example="Normal", description="Nutritional status"),
     *             @OA\Property(property="doctor_signature", type="string", example="Dr. María González", description="Doctor's signature"),
     *             @OA\Property(property="medical_license", type="string", example="12345", description="Medical license number"),
     *             @OA\Property(property="cmd", type="string", example="CMD-001", description="Medical college registration"),
     *             @OA\Property(property="growth_development_chart", type="string", example="According to WHO chart", description="Growth and development chart")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $medicalRecord = MedicalRecord::find($id);
            
            if (!$medicalRecord) {
                return response()->json(['message' => 'Medical record not found'], 404);
            }

            $medicalRecord->update($request->all());
            $medicalRecord->load('kid');

            return response()->json([
                'message' => 'Medical record updated successfully',
                'data' => $medicalRecord
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating medical record'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/medical-records/{id}",
     *     operationId="delete_medical_record",
     *     tags={"Medical Records"},
     *     summary="Delete medical record",
     *     description="Delete a medical record",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Medical record ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $medicalRecord = MedicalRecord::find($id);
            
            if (!$medicalRecord) {
                return response()->json(['message' => 'Medical record not found'], 404);
            }

            $medicalRecord->delete();

            return response()->json(['message' => 'Medical record deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting medical record'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/kids/{kid_id}/medical-record",
     *     operationId="get_kid_medical_record",
     *     tags={"Medical Records"},
     *     summary="Get medical record by kid ID",
     *     description="Retrieve the medical record for a specific kid",
     *     @OA\Parameter(
     *         in="path",
     *         name="kid_id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Kid ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medical record not found for this kid")
     *         )
     *     )
     * )
     */
    public function getByKidId($kid_id)
    {
        try {
            $medicalRecord = MedicalRecord::with('kid')->where('kid_id', $kid_id)->first();
            
            if (!$medicalRecord) {
                return response()->json(['message' => 'Medical record not found for this kid'], 404);
            }

            return response()->json(["data" => $medicalRecord], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "Error retrieving medical record"], 500);
        }
    }
}