<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use App\Models\Concubines;
use App\Models\Kids;
use App\Models\Pediatrician;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class KidController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/kids",
     *      operationId="all_kids",
     *     tags={"Kids"},
     *     summary="All kids",
     *     description="All kids",
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter by kid's name"
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="last_name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter by kid's last name"
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
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="code", type="string", example=""),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="gender", type="string", example=""),
     *              @OA\Property(property="born_date", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="region", type="string", example=""),
     *              @OA\Property(property="province", type="string", example=""),
     *              @OA\Property(property="municipality", type="string", example=""),
     *              @OA\Property(property="district", type="string", example=""),
     *              @OA\Property(property="sections", type="string", example=""),
     *              @OA\Property(property="neighborhood", type="string", example=""),
     *              @OA\Property(property="classroom", type="string", example=""),
     *              @OA\Property(property="insurance", type="string", example=""),
     *              @OA\Property(property="insurance_number", type="string", example=""),
     *              @OA\Property(property="allergies", type="string", example=""),
     *              @OA\Property(property="medical_conditions", type="string", example=""),
     *              @OA\Property(property="medications", type="string", example=""),
     *              @OA\Property(
     *                  property="pediatrician",
     *                  type="object",
     *                  nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="range", type="string", example="Pediatra General"),
     *                  @OA\Property(property="name", type="string", example="Dr. María González"),
     *                  @OA\Property(property="phone", type="string", example="+1234567890")
     *              ),
     *              @OA\Property(property="applicant", type="string", example=""),
     *              @OA\Property(property="concubine", type="string", example=""),
     *              @OA\Property(property="file", type="string", format="string", example=""),
     *              @OA\Property(property="insurance_file", type="string", format="string", example=""),
     *              @OA\Property(property="vaccines_file", type="string", format="string", example=""),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example=""),
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = Kids::with('applicant')->with('concubine')->with('authorized_persons')->with('pediatrician');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('last_name')) {
            $query->where('last_name', 'like', '%' . $request->input('last_name') . '%');
        }

        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $kids = $query->paginate($perPage);

        foreach ($kids as $kid) {
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . $kid->code . "&size=200x200";
            $kid->qr_code = $qrUrl;
        }

        return response()->json(["data"=>$kids],200);
    }

    /**
     * @OA\Get (
     *     path="/api/kids/totales",
     *     operationId="kids_totals",
     *     tags={"Kids"},
     *     summary="Totals of kids by gender, age, course and sector",
     *     description="Return totals grouped by gender (masculino/femenino), age (in years), course (classroom) and sector (neighborhood)",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="gender",
     *                  type="object",
     *                  @OA\Property(property="masculino", type="integer", example=10),
     *                  @OA\Property(property="femenino", type="integer", example=12)
     *              ),
     *              @OA\Property(
     *                  property="age",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="age", type="integer", example=4),
     *                      @OA\Property(property="total", type="integer", example=7)
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="course",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="course", type="string", example="2do año"),
     *                      @OA\Property(property="total", type="integer", example=8)
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="sector",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="sector", type="string", example="Ensanche La Fe"),
     *                      @OA\Property(property="total", type="integer", example=5)
     *                  )
     *              )
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example=""),
     *          )
     *      )
     * )
     */
    public function totales()
    {
        // Gender totals (explicit masculine/feminine keys)
        $genderCounts = Kids::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');
        $genderTotals = [
            'masculino' => (int)($genderCounts['Masculino'] ?? 0),
            'femenino' => (int)($genderCounts['Femenino'] ?? 0),
        ];

        // Age totals in years (compute from born_date)
        $ageCounts = Kids::whereNotNull('born_date')
            ->get()
            ->groupBy(function ($kid) {
                return Carbon::parse($kid->born_date)->age;
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->sortKeys();
        $ageTotals = [];
        foreach ($ageCounts as $age => $count) {
            $ageTotals[] = [
                'age' => (int)$age,
                'total' => (int)$count,
            ];
        }

        // Course totals (classroom)
        $courseTotals = Kids::select('classroom', DB::raw('count(*) as total'))
            ->groupBy('classroom')
            ->get()
            ->map(function ($row) {
                return [
                    'course' => $row->classroom,
                    'total' => (int)$row->total,
                ];
            });

        // Sector totals (use neighborhood as sector)
        $sectorTotals = Kids::select('neighborhood', DB::raw('count(*) as total'))
            ->groupBy('neighborhood')
            ->get()
            ->map(function ($row) {
                return [
                    'sector' => $row->neighborhood,
                    'total' => (int)$row->total,
                ];
            });

        return response()->json([
            'data' => [
                'gender' => $genderTotals,
                'age' => $ageTotals,
                'course' => $courseTotals,
                'sector' => $sectorTotals,
            ]
        ], 200);
    }

    /**
     * @OA\Get (
     *     path="/api/kids/{id}",
     *     operationId="watch_kid",
     *     tags={"Kids"},
     *     summary="See kid",
     *     description="See kid",
     *    @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="code", type="string", example=""),
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="gender", type="string", example=""),
     *              @OA\Property(property="born_date", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="region", type="string", example=""),
     *              @OA\Property(property="province", type="string", example=""),
     *              @OA\Property(property="municipality", type="string", example=""),
     *              @OA\Property(property="district", type="string", example=""),
     *              @OA\Property(property="sections", type="string", example=""),
     *              @OA\Property(property="neighborhood", type="string", example=""),
     *              @OA\Property(property="classroom", type="string", example=""),
     *              @OA\Property(property="insurance", type="string", example=""),
     *              @OA\Property(property="insurance_number", type="string", example=""),
     *              @OA\Property(property="allergies", type="string", example=""),
     *              @OA\Property(property="medical_conditions", type="string", example=""),
     *              @OA\Property(property="medications", type="string", example=""),
     *              @OA\Property(
     *                  property="pediatrician",
     *                  type="object",
     *                  nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="range", type="string", example="Pediatra General"),
     *                  @OA\Property(property="name", type="string", example="Dr. María González"),
     *                  @OA\Property(property="phone", type="string", example="+1234567890")
     *              ),
     *              @OA\Property(property="applicant", type="string", example=""),
     *              @OA\Property(property="concubine", type="string", example=""),
     *              @OA\Property(property="file", type="string", format="string", example=""),
     *              @OA\Property(property="insurance_file", type="string", format="string", example=""),
     *              @OA\Property(property="vaccines_file", type="string", format="string", example=""),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example=""),
     *          )
     *      )
     * )
    */

    public function watch($id){
        try{
            $kids = Kids::with('applicant')->with('concubine')->with('authorized_persons')->with('pediatrician')->find($id);
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . $kids->code . "&size=200x200";
            $kids->qr_code = $qrUrl;
            return response()->json(["data"=>$kids],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/kids",
     *      operationId="store_kid",
     *      tags={"Kids"},
     *      summary="Store kid",
     *      description="Store kid and upload related documents",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="applicant_id", type="number", example=1),
     *                 @OA\Property(property="concubine_id", type="number", example=1),
     *                 @OA\Property(property="code", type="string", example="DV24-0001"),
     *                 @OA\Property(property="name", type="string", example="Daniel"),
     *                 @OA\Property(property="last_name", type="string", example="Valdez"),
     *                 @OA\Property(property="gender", type="string", example="Masculino"),
     *                 @OA\Property(property="born_date", type="string", example="12/03/2024"),
     *                 @OA\Property(property="address", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="region", type="string", example="Ozama"),
     *                 @OA\Property(property="province", type="string", example="Distrito Nacional"),
     *                 @OA\Property(property="municipality", type="string", example="Santo Domingo de Guzmán"),
     *                 @OA\Property(property="district", type="string", example="Primera Circunscripción"),
     *                 @OA\Property(property="sections", type="string", example="Sección 1"),
     *                 @OA\Property(property="neighborhood", type="string", example="Ensanche La Fe"),
     *                 @OA\Property(property="classroom", type="string", example="2do año"),
     *                 @OA\Property(property="insurance", type="string", example="Senasa"),
     *                 @OA\Property(property="insurance_number", type="string", example="0909121"),
     *                 @OA\Property(property="allergies", type="string", example="Ninguna"),
     *                 @OA\Property(property="medical_conditions", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="medications", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="pediatrician_id", type="number", example=1),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Upload a document related to the kid (PDF, JPG, etc.)"
     *                 ),
     *                     @OA\Property(
     *                     property="insurance_file",
     *                     type="string",
     *                     format="binary",
     *                     description="Upload a document related to the kid (PDF, JPG, etc.)"
     *                 ),
     *                     @OA\Property(
     *                     property="vaccines_file",
     *                     type="string",
     *                     format="binary",
     *                     description="Upload a document related to the kid (PDF, JPG, etc.)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object")
     *          )
     *     )
     * )
    */

    public function register(Request $request)
    {
        if (!Applicants::find($request->input('applicant_id'))) {
            return response()->json(['error' => 'El solicitante proporcionado no existe.'], 404);
        }

        if (!Concubines::find($request->input('concubine_id'))) {
            return response()->json(['error' => 'El concubino proporcionado no existe.'], 404);
        }

        if ($request->filled('pediatrician_id') && !Pediatrician::find($request->input('pediatrician_id'))) {
            return response()->json(['error' => 'El pediatra proporcionado no existe.'], 404);
        }

        $kids = new Kids($request->except('file'));
        if ($request->has('born_date')) {
            $kids->born_date = Carbon::createFromFormat('d/m/Y', $request->input('born_date'))->format('Y-m-d');
        }
        $kids->save();

        $initials = strtoupper(substr($kids->name, 0, 1)) . strtoupper(substr($kids->last_name, 0, 1));
        $year = substr(\Carbon\Carbon::now()->year, -2);
        $id = str_pad($kids->id, 4, '0', STR_PAD_LEFT);

        $kids->code = $initials . $year . "-" . $id;
        $kids->save();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'kid_' . $kids->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/public/files', $filename);
            $kids->file = $path;
        }

        if ($request->hasFile('insurance_file')) {
            $file = $request->file('insurance_file');
            $filename = 'kid_insurance_file' . $kids->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/public/files', $filename);
            $kids->insurance_file = $path;
        }

        if ($request->hasFile('vaccines_file')) {
            $file = $request->file('vaccines_file');
            $filename = 'kid_vaccines_file' . $kids->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/public/files', $filename);
            $kids->vaccines_file = $path;
        }

        $kids->save();

        return response()->json(["data"=>$kids],200);
    }

    public function multipleRegister(Request $request)
    {
        $result = [];
    
        foreach ($request->input('kids', []) as $kidData) {
            // Validaciones
            if (!Applicants::find($kidData['applicant_id'] ?? null)) {
                return response()->json(['error' => 'El solicitante proporcionado no existe.'], 404);
            }
    
            if (!Concubines::find($kidData['concubine_id'] ?? null)) {
                return response()->json(['error' => 'El concubino proporcionado no existe.'], 404);
            }

            if (!empty($kidData['pediatrician_id']) && !Pediatrician::find($kidData['pediatrician_id'])) {
                return response()->json(['error' => 'El pediatra proporcionado no existe.'], 404);
            }
    
            // Crear nuevo niño
            $kid = new Kids(Arr::except($kidData, ['file', 'insurance_file', 'vaccines_file', 'born_date']));
            
            if (!empty($kidData['born_date'])) {
                $kid->born_date = Carbon::createFromFormat('d/m/Y', $kidData['born_date'])->format('Y-m-d');
            }
    
            $kid->save();
    
            // Generar código
            $initials = strtoupper(substr($kid->name, 0, 1)) . strtoupper(substr($kid->last_name, 0, 1));
            $year = substr(Carbon::now()->year, -2);
            $id = str_pad($kid->id, 4, '0', STR_PAD_LEFT);
            $kid->code = $initials . $year . "-" . $id;
    
            // Archivos (usa nombre de campo con key única si aplica)
            if ($request->hasFile("files.{$kidData['temp_key']}.file")) {
                $file = $request->file("files.{$kidData['temp_key']}.file");
                $filename = 'kid_' . $kid->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $kid->file = url('storage/files/' . $filename);
            }
    
            if ($request->hasFile("files.{$kidData['temp_key']}.insurance_file")) {
                $file = $request->file("files.{$kidData['temp_key']}.insurance_file");
                $filename = 'kid_insurance_' . $kid->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $kid->insurance_file = url('storage/files/' . $filename);
            }
    
            if ($request->hasFile("files.{$kidData['temp_key']}.vaccines_file")) {
                $file = $request->file("files.{$kidData['temp_key']}.vaccines_file");
                $filename = 'kid_vaccines_' . $kid->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $kid->vaccines_file = url('storage/files/' . $filename);
            }
    
            $kid->save();
            $result[] = $kid;
        }
    
        return response()->json(['data' => $result], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/kids/{id}",
     *     operationId="update_kid",
     *     tags={"Kids"},
     *     summary="Update kid",
     *     description="Update kid and upload related documents",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="applicant_id", type="number", example=1),
     *                 @OA\Property(property="concubine_id", type="number", example=1),
     *                 @OA\Property(property="tutor_id", type="number", example=1),
     *                 @OA\Property(property="name", type="string", example="Daniel"),
     *                 @OA\Property(property="last_name", type="string", example="Chacon"),
     *                 @OA\Property(property="gender", type="string", example="M"),
     *                 @OA\Property(property="born_date", type="string", example="12/03/2024"),
     *                 @OA\Property(property="address", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="region", type="string", example="Ozama"),
     *                 @OA\Property(property="province", type="string", example="Distrito Nacional"),
     *                 @OA\Property(property="municipality", type="string", example="Santo Domingo de Guzmán"),
     *                 @OA\Property(property="district", type="string", example="Primera Circunscripción"),
     *                 @OA\Property(property="sections", type="string", example="Sección 1"),
     *                 @OA\Property(property="neighborhood", type="string", example="Ensanche La Fe"),
     *                 @OA\Property(property="classroom", type="string", example="2do año"),
     *                 @OA\Property(property="insurance", type="string", example="Senasa"),
     *                 @OA\Property(property="insurance_number", type="string", example="0909121"),
     *                 @OA\Property(property="allergies", type="string", example="Ninguna"),
     *                 @OA\Property(property="medical_conditions", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="medications", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="pediatrician_id", type="number", example=1),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Upload a document related to the kid (PDF, JPG, etc.)"
     *                 ),
     *                     @OA\Property(
     *                     property="insurance_file",
     *                     type="string",
     *                     format="binary",
     *                     description="Upload a document related to the kid (PDF, JPG, etc.)"
     *                 ),
     *                     @OA\Property(
     *                     property="vaccines_file",
     *                     type="string",
     *                     format="binary",
     *                     description="Upload a document related to the kid (PDF, JPG, etc.)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object")
     *          )
     *     )
     * )
    */

    public function update(Request $request, $id){
        try{
            $kids = Kids::where('id',$id)->first();
            if ($request->has('born_date')) {
                $kids->born_date = Carbon::createFromFormat('d/m/Y', $request->input('born_date'))->format('Y-m-d');
            }
            $kids->update($request->except('file'));

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'kid_' . $kids->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $path = url('storage/public/files', $filename);
                $kids->file = $path;
            }

            if ($request->hasFile('insurance_file')) {
                $file = $request->file('insurance_file');
                $filename = 'kid_insurance_file' . $kids->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $path = url('storage/public/files', $filename);
                $kids->insurance_file = $path;
            }

            if ($request->hasFile('vaccines_file')) {
                $file = $request->file('vaccines_file');
                $filename = 'kid_vaccines_file' . $kids->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $path = url('storage/public/files', $filename);
                $kids->vaccines_file = $path;
            }

            $kids->save();

            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/kids/{id}",
     *      operationId="delete_kid",
     *      tags={"Kids"},
     *      summary="Delete kid",
     *      description="Delete kid",
     *    @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

    public function delete($id){
        try{
            Kids::where('id', $id)->delete();
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }
}
