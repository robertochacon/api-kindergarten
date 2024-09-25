<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use Exception;
use Illuminate\Http\Request;

class ApplicantsController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/applicants",
     *      operationId="all_applicants",
     *     tags={"Applicants"},
     *     security={{ "apiAuth": {} }},
     *     summary="All applicants",
     *     description="All applicants",
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
     *              @OA\Property(property="military", type="boolean", format="boolean", example=""),
     *              @OA\Property(property="institution", type="string", format="string", example=""),
     *              @OA\Property(property="range", type="string", format="string", example=""),
     *              @OA\Property(property="email", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_2", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_2", type="string", format="string", example=""),
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
        $patents = Applicants::with('concubine')->with('kid')->paginate(10);
        return response()->json(["data"=>$patents],200);
    }


     /**
     * @OA\Get (
     *     path="/api/applicants/{id}",
     *     operationId="watch_applicants",
     *     tags={"Applicants"},
     *     security={{ "apiAuth": {} }},
     *     summary="See applicant",
     *     description="See applicant",
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
     *              @OA\Property(property="military", type="boolean", format="boolean", example="true"),
     *              @OA\Property(property="institution", type="string", format="string", example=""),
     *              @OA\Property(property="range", type="string", format="string", example=""),
     *              @OA\Property(property="email", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_1", type="string", format="string", example=""),
     *              @OA\Property(property="name_work_reference_2", type="string", format="string", example=""),
     *              @OA\Property(property="phone_work_reference_2", type="string", format="string", example=""),
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
            $applicant = Applicants::with('concubine')->with('kid')->find($id);
            return response()->json(["data"=>$applicant],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/applicants",
     *      operationId="store_applicants",
     *      tags={"Applicants"},
     *      security={{ "apiAuth": {} }},
     *      summary="Store applicant",
     *      description="Store applicant and upload documents",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "last_name", "type_identification", "identification"},
     *                 @OA\Property(property="name", type="string", example="Juan"),
     *                 @OA\Property(property="last_name", type="string", example="Peralta"),
     *                 @OA\Property(property="type_identification", type="string", example="Cedula"),
     *                 @OA\Property(property="identification", type="string", example="40224522776"),
     *                 @OA\Property(property="parent", type="string", example="Primo"),
     *                 @OA\Property(property="marital_status", type="string", example="Soltero"),
     *                 @OA\Property(property="phone", type="string", example="8099877765"),
     *                 @OA\Property(property="residence_phone", type="string", example="8099886700"),
     *                 @OA\Property(property="address", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="military", type="boolean", example=true),
     *                 @OA\Property(property="institution", type="string", example="ARD"),
     *                 @OA\Property(property="range", type="string", example="Teniente"),
     *                 @OA\Property(property="email", type="string", example="juan@gmail.com"),
     *                 @OA\Property(property="name_work_reference_1", type="string", format="string", example="Pedro Sanchez"),
     *                 @OA\Property(property="phone_work_reference_1", type="string", format="string", example="8099886700"),
     *                 @OA\Property(property="name_work_reference_2", type="string", format="string", example="Manuel Acosta"),
     *                 @OA\Property(property="phone_work_reference_2", type="string", format="string", example="8099886700"),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Applicant's document file (PDF, JPG, etc.)"
     *                 )
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Applicant created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="data", type="object", description="Applicant data")
     *          )
     *      )
     *  )
     */


    public function register(Request $request)
    {
        $applicant = new Applicants(request()->all());
        $applicant = new Applicants($request->except('file'));
        $applicant->save();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'applicant_' . $applicant->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/files', $filename);
            $applicant->file = $path;
        }

        if (isset($request->kid_id)) {
            $applicant->kids()->attach(['kid_id' => $request->kid_id]);
        }

        $applicant->save();

        return response()->json(["data"=>$applicant],200);
    }

    /**
     * @OA\Put(
     *     path="/api/applicants/{id}",
     *     operationId="update_applicants",
     *     tags={"Applicants"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update applicant and upload documents",
     *     description="Update applicant data and allow file upload",
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
     *                 required={"name", "last_name", "type_identification", "identification"},
     *                 @OA\Property(property="name", type="string", example="Juan"),
     *                 @OA\Property(property="last_name", type="string", example="Peralta"),
     *                 @OA\Property(property="type_identification", type="string", example="Cedula"),
     *                 @OA\Property(property="identification", type="string", example="40224522776"),
     *                 @OA\Property(property="parent", type="string", example="Primo"),
     *                 @OA\Property(property="marital_status", type="string", example="Soltero"),
     *                 @OA\Property(property="phone", type="string", example="8099877765"),
     *                 @OA\Property(property="residence_phone", type="string", example="8099886700"),
     *                 @OA\Property(property="address", type="string", example="Santo Domingo"),
     *                 @OA\Property(property="military", type="boolean", example=true),
     *                 @OA\Property(property="institution", type="string", example="ARD"),
     *                 @OA\Property(property="range", type="string", example="Teniente"),
     *                 @OA\Property(property="email", type="string", example="juan@gmail.com"),
     *                 @OA\Property(property="name_work_reference_1", type="string", format="string", example="Pedro Sanchez"),
     *                 @OA\Property(property="phone_work_reference_1", type="string", format="string", example="8099886700"),
     *                 @OA\Property(property="name_work_reference_2", type="string", format="string", example="Manuel Acosta"),
     *                 @OA\Property(property="phone_work_reference_2", type="string", format="string", example="8099886700"),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Applicant's updated document file (PDF, JPG, etc.)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Applicant updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="data", type="object", description="Updated applicant data")
     *          )
     *     )
     * )
     */

    public function update(Request $request, $id){
        try{
            $applicant = Applicants::findOrFail($id);

            $applicant->update($request->except('file'));

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'applicant_' . $applicant->id . '_' . now()->format('Ymd_His') . '.'  . $file->getClientOriginalExtension();
                $file->storeAs('public/files', $filename);
                $path = url('storage/files', $filename);
                $applicant->file = $path;
            }

            if (isset($request->kid_id)) {
                $applicant->kids()->sync(['kid_id' => $request->kid_id]); // sync en lugar de attach para evitar duplicados
            }

            $applicant->save();
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/applicants/{id}",
     *      operationId="delete_applicants",
     *      tags={"Applicants"},
     *     security={{ "apiAuth": {} }},
     *      summary="Delete applicant",
     *      description="Delete applicant",
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
            Applicants::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

}
