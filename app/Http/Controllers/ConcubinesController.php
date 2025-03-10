<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use App\Models\Concubines;
use Exception;
use Illuminate\Http\Request;

class ConcubinesController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/concubines",
     *     operationId="all_concubines",
     *     tags={"Concubines"},
     *     summary="All concubines",
     *     description="All concubines",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="type_identification", type="string", example=""),
     *              @OA\Property(property="identification", type="string", example=""),
     *              @OA\Property(property="parent", type="string", example=""),
     *              @OA\Property(property="marital_status", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="military", type="integer", example=1),
     *              @OA\Property(property="institution", type="string", format="string", example=""),
     *              @OA\Property(property="email", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_2", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_2", type="string", format="string", example=""),
     *              @OA\Property(property="concubine", type="string", format="string", example=""),
     *              @OA\Property(property="file", type="string", format="string", example=""),
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
    public function index()
    {
        $patents = Concubines::with('kid')->paginate(10);
        return response()->json(["data"=>$patents],200);
    }


     /**
     * @OA\Get (
     *     path="/api/concubines/{id}",
     *     operationId="watch_concubines",
     *     tags={"Concubines"},
     *     summary="See concubine",
     *     description="See concubine",
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
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="type_identification", type="string", example=""),
     *              @OA\Property(property="identification", type="string", example=""),
     *              @OA\Property(property="parent", type="string", example=""),
     *              @OA\Property(property="marital_status", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="military", type="integer", example=1),
     *              @OA\Property(property="institution", type="string", format="string", example=""),
     *              @OA\Property(property="email", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_2", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_2", type="string", format="string", example=""),
     *              @OA\Property(property="concubine", type="string", format="string", example=""),
     *              @OA\Property(property="file", type="string", format="string", example=""),
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
            $concubine = Concubines::with('kid')->find($id);
            return response()->json(["data"=>$concubine],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/concubines",
     *      operationId="store_concubines",
     *      tags={"Concubines"},
     *      summary="Store concubine",
     *      description="Store concubine and upload related documents",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="applicant_id", type="number", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan"),
     *                 @OA\Property(property="last_name", type="string", example="Peralta"),
     *                 @OA\Property(property="type_identification", type="string", example="Cedula"),
     *                 @OA\Property(property="identification", type="string", example="40224522776"),
     *                 @OA\Property(property="parent", type="string", example="Primo"),
     *                 @OA\Property(property="marital_status", type="string", example="Soltero"),
     *                 @OA\Property(property="phone", type="string", example="8099877765"),
     *                 @OA\Property(property="residence_phone", type="string", example="8099886700"),
     *                 @OA\Property(property="address", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="military", type="integer", example=1),
     *                 @OA\Property(property="institution", type="string", example="ARD"),
     *                 @OA\Property(property="email", type="string", example="juan@gmail.com"),
     *                 @OA\Property(property="name_work_reference_1", type="string", format="string", example="Pedro Sanchez"),
     *                 @OA\Property(property="phone_work_reference_1", type="string", format="string", example="8099886700"),
     *                 @OA\Property(property="name_work_reference_2", type="string", format="string", example="Manuel Acosta"),
     *                 @OA\Property(property="phone_work_reference_2", type="string", format="string", example="8099886700"),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Concubine's document file (PDF, JPG, etc.)"
     *                 )
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="data", type="object")
     *          )
     *     )
     * )
    */

    public function register(Request $request)
    {

        if (!Applicants::find($request->input('applicant_id'))) {
            return response()->json(['error' => 'El solicitante proporcionado no existe.'], 404);
        }

        $concubine = new Concubines($request->except('file'));
        $concubine->save();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'concubine_' . $concubine->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/files', $filename);
            $concubine->file = $path;
        }

        if (isset($request->kid_id)) {
            $concubine->kids()->attach(['kid_id' => $request->kid_id]);
        }

        $concubine->save();
        return response()->json(["data"=>$concubine],200);
    }

    /**
     * @OA\Put(
     *     path="/api/concubines/{id}",
     *     operationId="update_concubines",
     *     tags={"Concubines"},
     *     summary="Update concubine",
     *     description="Update concubine and upload related documents",
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
     *                 @OA\Property(property="name", type="string", example="Juan"),
     *                 @OA\Property(property="last_name", type="string", example="Peralta"),
     *                 @OA\Property(property="type_identification", type="string", example="Cedula"),
     *                 @OA\Property(property="identification", type="string", example="40224522776"),
     *                 @OA\Property(property="parent", type="string", example="Primo"),
     *                 @OA\Property(property="marital_status", type="string", example="Soltero"),
     *                 @OA\Property(property="phone", type="string", example="8099877765"),
     *                 @OA\Property(property="residence_phone", type="string", example="8099886700"),
     *                 @OA\Property(property="address", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="military", type="integer", example=1),
     *                 @OA\Property(property="institution", type="string", example="ARD"),
     *                 @OA\Property(property="email", type="string", example="juan@gmail.com"),
     *                 @OA\Property(property="kid_id", type="number", example=1),
     *                 @OA\Property(property="name_work_reference_1", type="string", format="string", example="Pedro Sanchez"),
     *                 @OA\Property(property="phone_work_reference_1", type="string", format="string", example="8099886700"),
     *                 @OA\Property(property="name_work_reference_2", type="string", format="string", example="Manuel Acosta"),
     *                 @OA\Property(property="phone_work_reference_2", type="string", format="string", example="8099886700"),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Concubine's document file (PDF, JPG, etc.)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="data", type="object")
     *          )
     *     )
     * )
    */

     public function update(Request $request, $id){
        try{
            $concubine = Concubines::where('id', $id)->first();

            $concubine->update($request->except('file'));

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'concubine_' . $concubine->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $path = url('storage/files', $filename);
                $concubine->file = $path;
            }

            if (isset($request->kid_id)) {
                $concubine->kids()->attach(['kid_id' => $request->kid_id]);
            }

            $concubine->save();

            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/concubines/{id}",
     *      operationId="delete_concubines",
     *      tags={"Concubines"},
     *      summary="Delete concubine",
     *      description="Delete concubine",
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
            Concubines::where('id', $id)->delete();
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

}
