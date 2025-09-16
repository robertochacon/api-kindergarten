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
     *             @OA\Property(property="bajo_riesgo", type="boolean", example=true),
     *             @OA\Property(property="alto_riesgo", type="boolean", example=false),
     *             @OA\Property(property="preeclampsia", type="boolean", example=false),
     *             @OA\Property(property="sangrados", type="boolean", example=false),
     *             @OA\Property(property="infecciones", type="boolean", example=false),
     *             @OA\Property(property="madre_adolescente", type="boolean", example=false),
     *             @OA\Property(property="rn_a_termino", type="boolean", example=true),
     *             @OA\Property(property="rn_prematuro", type="boolean", example=false),
     *             @OA\Property(property="semanas_al_nacer", type="string", example="40"),
     *             @OA\Property(property="parto", type="boolean", example=true),
     *             @OA\Property(property="cesarea", type="boolean", example=false),
     *             @OA\Property(property="lloro_al_nacer", type="boolean", example=true),
     *             @OA\Property(property="peso_al_nacer", type="string", example="3.2 kg"),
     *             @OA\Property(property="talla", type="string", example="50 cm"),
     *             @OA\Property(property="perimetro_cefalico", type="string", example="35 cm"),
     *             @OA\Property(property="complicacion_al_nacer", type="boolean", example=false),
     *             @OA\Property(property="descripcion_complicacion", type="string", example=""),
     *             @OA\Property(property="tiempo_hospitalizado", type="string", example="2 días"),
     *             @OA\Property(property="incubadora", type="boolean", example=false),
     *             @OA\Property(property="oxigeno", type="boolean", example=false),
     *             @OA\Property(property="ventilacion_mecanica", type="boolean", example=false),
     *             @OA\Property(property="lactancia_materna_al_nacer", type="boolean", example=true),
     *             @OA\Property(property="duracion_lactancia", type="string", example="6 meses"),
     *             @OA\Property(property="inicio_alimentacion_complementaria", type="string", example="6 meses"),
     *             @OA\Property(property="suplemento_hierro", type="boolean", example=true),
     *             @OA\Property(property="suplemento_vitamina_a", type="boolean", example=false),
     *             @OA\Property(property="suplemento_multivitaminas", type="boolean", example=false),
     *             @OA\Property(property="estimulantes_apetito", type="boolean", example=false),
     *             @OA\Property(property="come_solo", type="boolean", example=true),
     *             @OA\Property(property="cantidad_veces_come", type="string", example="3 veces al día"),
     *             @OA\Property(property="buen_apetito", type="boolean", example=true),
     *             @OA\Property(property="alergia_alimento", type="boolean", example=false),
     *             @OA\Property(property="controla_esfinteres", type="boolean", example=true),
     *             @OA\Property(property="utiliza_panal", type="boolean", example=false),
     *             @OA\Property(property="discapacidad_diagnosticada", type="boolean", example=false),
     *             @OA\Property(property="descripcion_discapacidad", type="string", example=""),
     *             @OA\Property(property="antecedentes_patologicos", type="string", example="Ninguno"),
     *             @OA\Property(property="alergico_medicamento", type="boolean", example=false),
     *             @OA\Property(property="medicamento_permanente", type="string", example=""),
     *             @OA\Property(property="enfermedades_previas", type="boolean", example=false),
     *             @OA\Property(property="tipo_enfermedad", type="string", example=""),
     *             @OA\Property(property="estado_ingresado", type="boolean", example=false),
     *             @OA\Property(property="veces_ingresado", type="string", example=""),
     *             @OA\Property(property="anemia", type="boolean", example=false),
     *             @OA\Property(property="fiebre", type="boolean", example=false),
     *             @OA\Property(property="infecciones_previas", type="boolean", example=false),
     *             @OA\Property(property="deshidratacion", type="boolean", example=false),
     *             @OA\Property(property="luce_desnutrido", type="boolean", example=false),
     *             @OA\Property(property="accidentes", type="boolean", example=false),
     *             @OA\Property(property="cirugias", type="boolean", example=false),
     *             @OA\Property(property="cabeza", type="string", example="Normal"),
     *             @OA\Property(property="fontanelas", type="string", example="Cerradas"),
     *             @OA\Property(property="cara", type="string", example="Normal"),
     *             @OA\Property(property="ojos_oidos_nariz", type="string", example="Normal"),
     *             @OA\Property(property="boca", type="string", example="Normal"),
     *             @OA\Property(property="cuello", type="string", example="Normal"),
     *             @OA\Property(property="torax", type="string", example="Normal"),
     *             @OA\Property(property="abdomen", type="string", example="Normal"),
     *             @OA\Property(property="extremidades_superiores_inferiores", type="string", example="Normal"),
     *             @OA\Property(property="genitourinario", type="string", example="Normal"),
     *             @OA\Property(property="piel", type="string", example="Normal"),
     *             @OA\Property(property="peso_lbs", type="string", example="25 lbs"),
     *             @OA\Property(property="talla_cms", type="string", example="100 cm"),
     *             @OA\Property(property="pliegue_brazo", type="string", example="Normal"),
     *             @OA\Property(property="imc", type="string", example="15.5"),
     *             @OA\Property(property="estado_nutricional", type="string", example="Normal"),
     *             @OA\Property(property="firma_medico", type="string", example="Dr. María González"),
     *             @OA\Property(property="exequatur", type="string", example="12345"),
     *             @OA\Property(property="cmd", type="string", example="CMD-001"),
     *             @OA\Property(property="tabla_crecimiento_desarrollo", type="string", example="Según tabla OMS"),
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
     *             @OA\Property(property="bajo_riesgo", type="boolean", example=true, description="Low risk pregnancy"),
     *             @OA\Property(property="alto_riesgo", type="boolean", example=false, description="High risk pregnancy"),
     *             @OA\Property(property="preeclampsia", type="boolean", example=false, description="Preeclampsia during pregnancy"),
     *             @OA\Property(property="sangrados", type="boolean", example=false, description="Bleeding during pregnancy"),
     *             @OA\Property(property="infecciones", type="boolean", example=false, description="Infections during pregnancy"),
     *             @OA\Property(property="madre_adolescente", type="boolean", example=false, description="Teenage mother"),
     *             @OA\Property(property="rn_a_termino", type="boolean", example=true, description="Full-term newborn"),
     *             @OA\Property(property="rn_prematuro", type="boolean", example=false, description="Premature newborn"),
     *             @OA\Property(property="semanas_al_nacer", type="string", example="40", description="Weeks at birth"),
     *             @OA\Property(property="parto", type="boolean", example=true, description="Vaginal delivery"),
     *             @OA\Property(property="cesarea", type="boolean", example=false, description="Cesarean delivery"),
     *             @OA\Property(property="lloro_al_nacer", type="boolean", example=true, description="Cried at birth"),
     *             @OA\Property(property="peso_al_nacer", type="string", example="3.2 kg", description="Birth weight"),
     *             @OA\Property(property="talla", type="string", example="50 cm", description="Birth length"),
     *             @OA\Property(property="perimetro_cefalico", type="string", example="35 cm", description="Head circumference"),
     *             @OA\Property(property="complicacion_al_nacer", type="boolean", example=false, description="Complications at birth"),
     *             @OA\Property(property="descripcion_complicacion", type="string", example="", description="Description of complications"),
     *             @OA\Property(property="tiempo_hospitalizado", type="string", example="2 días", description="Hospitalization time"),
     *             @OA\Property(property="incubadora", type="boolean", example=false, description="Incubator use"),
     *             @OA\Property(property="oxigeno", type="boolean", example=false, description="Oxygen therapy"),
     *             @OA\Property(property="ventilacion_mecanica", type="boolean", example=false, description="Mechanical ventilation"),
     *             @OA\Property(property="lactancia_materna_al_nacer", type="boolean", example=true, description="Breastfeeding at birth"),
     *             @OA\Property(property="duracion_lactancia", type="string", example="6 meses", description="Breastfeeding duration"),
     *             @OA\Property(property="inicio_alimentacion_complementaria", type="string", example="6 meses", description="Complementary feeding start"),
     *             @OA\Property(property="suplemento_hierro", type="boolean", example=true, description="Iron supplement"),
     *             @OA\Property(property="suplemento_vitamina_a", type="boolean", example=false, description="Vitamin A supplement"),
     *             @OA\Property(property="suplemento_multivitaminas", type="boolean", example=false, description="Multivitamin supplement"),
     *             @OA\Property(property="estimulantes_apetito", type="boolean", example=false, description="Appetite stimulants"),
     *             @OA\Property(property="come_solo", type="boolean", example=true, description="Eats independently"),
     *             @OA\Property(property="cantidad_veces_come", type="string", example="3 veces al día", description="Eating frequency"),
     *             @OA\Property(property="buen_apetito", type="boolean", example=true, description="Good appetite"),
     *             @OA\Property(property="alergia_alimento", type="boolean", example=false, description="Food allergy"),
     *             @OA\Property(property="controla_esfinteres", type="boolean", example=true, description="Controls sphincters"),
     *             @OA\Property(property="utiliza_panal", type="boolean", example=false, description="Uses diapers"),
     *             @OA\Property(property="discapacidad_diagnosticada", type="boolean", example=false, description="Diagnosed disability"),
     *             @OA\Property(property="descripcion_discapacidad", type="string", example="", description="Disability description"),
     *             @OA\Property(property="antecedentes_patologicos", type="string", example="Ninguno", description="Pathological history"),
     *             @OA\Property(property="alergico_medicamento", type="boolean", example=false, description="Medication allergy"),
     *             @OA\Property(property="medicamento_permanente", type="string", example="", description="Permanent medication"),
     *             @OA\Property(property="enfermedades_previas", type="boolean", example=false, description="Previous diseases"),
     *             @OA\Property(property="tipo_enfermedad", type="string", example="", description="Type of disease"),
     *             @OA\Property(property="estado_ingresado", type="boolean", example=false, description="Hospitalized"),
     *             @OA\Property(property="veces_ingresado", type="string", example="", description="Times hospitalized"),
     *             @OA\Property(property="anemia", type="boolean", example=false, description="Anemia"),
     *             @OA\Property(property="fiebre", type="boolean", example=false, description="Fever"),
     *             @OA\Property(property="infecciones_previas", type="boolean", example=false, description="Previous infections"),
     *             @OA\Property(property="deshidratacion", type="boolean", example=false, description="Dehydration"),
     *             @OA\Property(property="luce_desnutrido", type="boolean", example=false, description="Appears malnourished"),
     *             @OA\Property(property="accidentes", type="boolean", example=false, description="Accidents"),
     *             @OA\Property(property="cirugias", type="boolean", example=false, description="Surgeries"),
     *             @OA\Property(property="cabeza", type="string", example="Normal", description="Head examination"),
     *             @OA\Property(property="fontanelas", type="string", example="Cerradas", description="Fontanelles"),
     *             @OA\Property(property="cara", type="string", example="Normal", description="Face examination"),
     *             @OA\Property(property="ojos_oidos_nariz", type="string", example="Normal", description="Eyes, ears, nose examination"),
     *             @OA\Property(property="boca", type="string", example="Normal", description="Mouth examination"),
     *             @OA\Property(property="cuello", type="string", example="Normal", description="Neck examination"),
     *             @OA\Property(property="torax", type="string", example="Normal", description="Chest examination"),
     *             @OA\Property(property="abdomen", type="string", example="Normal", description="Abdomen examination"),
     *             @OA\Property(property="extremidades_superiores_inferiores", type="string", example="Normal", description="Upper and lower extremities examination"),
     *             @OA\Property(property="genitourinario", type="string", example="Normal", description="Genitourinary examination"),
     *             @OA\Property(property="piel", type="string", example="Normal", description="Skin examination"),
     *             @OA\Property(property="peso_lbs", type="string", example="25 lbs", description="Current weight in pounds"),
     *             @OA\Property(property="talla_cms", type="string", example="100 cm", description="Current height in centimeters"),
     *             @OA\Property(property="pliegue_brazo", type="string", example="Normal", description="Arm fold measurement"),
     *             @OA\Property(property="imc", type="string", example="15.5", description="Body Mass Index"),
     *             @OA\Property(property="estado_nutricional", type="string", example="Normal", description="Nutritional status"),
     *             @OA\Property(property="firma_medico", type="string", example="Dr. María González", description="Doctor's signature"),
     *             @OA\Property(property="exequatur", type="string", example="12345", description="Medical license number"),
     *             @OA\Property(property="cmd", type="string", example="CMD-001", description="Medical college registration"),
     *             @OA\Property(property="tabla_crecimiento_desarrollo", type="string", example="Según tabla OMS", description="Growth and development chart")
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
     *             @OA\Property(property="bajo_riesgo", type="boolean", example=true, description="Low risk pregnancy"),
     *             @OA\Property(property="alto_riesgo", type="boolean", example=false, description="High risk pregnancy"),
     *             @OA\Property(property="preeclampsia", type="boolean", example=false, description="Preeclampsia during pregnancy"),
     *             @OA\Property(property="sangrados", type="boolean", example=false, description="Bleeding during pregnancy"),
     *             @OA\Property(property="infecciones", type="boolean", example=false, description="Infections during pregnancy"),
     *             @OA\Property(property="madre_adolescente", type="boolean", example=false, description="Teenage mother"),
     *             @OA\Property(property="rn_a_termino", type="boolean", example=true, description="Full-term newborn"),
     *             @OA\Property(property="rn_prematuro", type="boolean", example=false, description="Premature newborn"),
     *             @OA\Property(property="semanas_al_nacer", type="string", example="40", description="Weeks at birth"),
     *             @OA\Property(property="parto", type="boolean", example=true, description="Vaginal delivery"),
     *             @OA\Property(property="cesarea", type="boolean", example=false, description="Cesarean delivery"),
     *             @OA\Property(property="lloro_al_nacer", type="boolean", example=true, description="Cried at birth"),
     *             @OA\Property(property="peso_al_nacer", type="string", example="3.2 kg", description="Birth weight"),
     *             @OA\Property(property="talla", type="string", example="50 cm", description="Birth length"),
     *             @OA\Property(property="perimetro_cefalico", type="string", example="35 cm", description="Head circumference"),
     *             @OA\Property(property="complicacion_al_nacer", type="boolean", example=false, description="Complications at birth"),
     *             @OA\Property(property="descripcion_complicacion", type="string", example="", description="Description of complications"),
     *             @OA\Property(property="tiempo_hospitalizado", type="string", example="2 días", description="Hospitalization time"),
     *             @OA\Property(property="incubadora", type="boolean", example=false, description="Incubator use"),
     *             @OA\Property(property="oxigeno", type="boolean", example=false, description="Oxygen therapy"),
     *             @OA\Property(property="ventilacion_mecanica", type="boolean", example=false, description="Mechanical ventilation"),
     *             @OA\Property(property="lactancia_materna_al_nacer", type="boolean", example=true, description="Breastfeeding at birth"),
     *             @OA\Property(property="duracion_lactancia", type="string", example="6 meses", description="Breastfeeding duration"),
     *             @OA\Property(property="inicio_alimentacion_complementaria", type="string", example="6 meses", description="Complementary feeding start"),
     *             @OA\Property(property="suplemento_hierro", type="boolean", example=true, description="Iron supplement"),
     *             @OA\Property(property="suplemento_vitamina_a", type="boolean", example=false, description="Vitamin A supplement"),
     *             @OA\Property(property="suplemento_multivitaminas", type="boolean", example=false, description="Multivitamin supplement"),
     *             @OA\Property(property="estimulantes_apetito", type="boolean", example=false, description="Appetite stimulants"),
     *             @OA\Property(property="come_solo", type="boolean", example=true, description="Eats independently"),
     *             @OA\Property(property="cantidad_veces_come", type="string", example="3 veces al día", description="Eating frequency"),
     *             @OA\Property(property="buen_apetito", type="boolean", example=true, description="Good appetite"),
     *             @OA\Property(property="alergia_alimento", type="boolean", example=false, description="Food allergy"),
     *             @OA\Property(property="controla_esfinteres", type="boolean", example=true, description="Controls sphincters"),
     *             @OA\Property(property="utiliza_panal", type="boolean", example=false, description="Uses diapers"),
     *             @OA\Property(property="discapacidad_diagnosticada", type="boolean", example=false, description="Diagnosed disability"),
     *             @OA\Property(property="descripcion_discapacidad", type="string", example="", description="Disability description"),
     *             @OA\Property(property="antecedentes_patologicos", type="string", example="Ninguno", description="Pathological history"),
     *             @OA\Property(property="alergico_medicamento", type="boolean", example=false, description="Medication allergy"),
     *             @OA\Property(property="medicamento_permanente", type="string", example="", description="Permanent medication"),
     *             @OA\Property(property="enfermedades_previas", type="boolean", example=false, description="Previous diseases"),
     *             @OA\Property(property="tipo_enfermedad", type="string", example="", description="Type of disease"),
     *             @OA\Property(property="estado_ingresado", type="boolean", example=false, description="Hospitalized"),
     *             @OA\Property(property="veces_ingresado", type="string", example="", description="Times hospitalized"),
     *             @OA\Property(property="anemia", type="boolean", example=false, description="Anemia"),
     *             @OA\Property(property="fiebre", type="boolean", example=false, description="Fever"),
     *             @OA\Property(property="infecciones_previas", type="boolean", example=false, description="Previous infections"),
     *             @OA\Property(property="deshidratacion", type="boolean", example=false, description="Dehydration"),
     *             @OA\Property(property="luce_desnutrido", type="boolean", example=false, description="Appears malnourished"),
     *             @OA\Property(property="accidentes", type="boolean", example=false, description="Accidents"),
     *             @OA\Property(property="cirugias", type="boolean", example=false, description="Surgeries"),
     *             @OA\Property(property="cabeza", type="string", example="Normal", description="Head examination"),
     *             @OA\Property(property="fontanelas", type="string", example="Cerradas", description="Fontanelles"),
     *             @OA\Property(property="cara", type="string", example="Normal", description="Face examination"),
     *             @OA\Property(property="ojos_oidos_nariz", type="string", example="Normal", description="Eyes, ears, nose examination"),
     *             @OA\Property(property="boca", type="string", example="Normal", description="Mouth examination"),
     *             @OA\Property(property="cuello", type="string", example="Normal", description="Neck examination"),
     *             @OA\Property(property="torax", type="string", example="Normal", description="Chest examination"),
     *             @OA\Property(property="abdomen", type="string", example="Normal", description="Abdomen examination"),
     *             @OA\Property(property="extremidades_superiores_inferiores", type="string", example="Normal", description="Upper and lower extremities examination"),
     *             @OA\Property(property="genitourinario", type="string", example="Normal", description="Genitourinary examination"),
     *             @OA\Property(property="piel", type="string", example="Normal", description="Skin examination"),
     *             @OA\Property(property="peso_lbs", type="string", example="25 lbs", description="Current weight in pounds"),
     *             @OA\Property(property="talla_cms", type="string", example="100 cm", description="Current height in centimeters"),
     *             @OA\Property(property="pliegue_brazo", type="string", example="Normal", description="Arm fold measurement"),
     *             @OA\Property(property="imc", type="string", example="15.5", description="Body Mass Index"),
     *             @OA\Property(property="estado_nutricional", type="string", example="Normal", description="Nutritional status"),
     *             @OA\Property(property="firma_medico", type="string", example="Dr. María González", description="Doctor's signature"),
     *             @OA\Property(property="exequatur", type="string", example="12345", description="Medical license number"),
     *             @OA\Property(property="cmd", type="string", example="CMD-001", description="Medical college registration"),
     *             @OA\Property(property="tabla_crecimiento_desarrollo", type="string", example="Según tabla OMS", description="Growth and development chart")
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