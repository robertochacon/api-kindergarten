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
     *              @OA\Property(property="email", type="string", format="string", example=""),
     *              @OA\Property(property="work_reference", type="string", format="string", example=""),
     *              @OA\Property(property="personal_reference_1", type="number", format="number", example=""),
     *              @OA\Property(property="personal_reference_2", type="number", format="number", example=""),
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
        $patents = Applicants::with('kids')->paginate(10);
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
     *              @OA\Property(property="email", type="string", format="string", example=""),
     *              @OA\Property(property="work_reference", type="string", format="string", example=""),
     *              @OA\Property(property="personal_reference_1", type="number", format="number", example=""),
     *              @OA\Property(property="personal_reference_2", type="number", format="number", example=""),
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
            $applicant = Applicants::with('kids')->find($id);
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
     *      description="Store applicant",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example="Juan"),
     *            @OA\Property(property="last_name", type="string", format="string", example="Peralta"),
     *            @OA\Property(property="type_identification", type="string", example="Cedula"),
     *            @OA\Property(property="identification", type="string", format="string", example="40224522776"),
     *            @OA\Property(property="parent", type="string", format="string", example="Primo"),
     *            @OA\Property(property="marital_status", type="string", example="Soltero"),
     *            @OA\Property(property="phone", type="string", format="string", example="8099877765"),
     *            @OA\Property(property="residence_phone", type="string", format="string", example="8099886700"),
     *            @OA\Property(property="address", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="military", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="institution", type="string", format="string", example="ARD"),
     *            @OA\Property(property="email", type="string", format="string", example="juan@gmail.com"),
     *            @OA\Property(property="work_reference", type="string", format="string", example="Ramon Acosta - 8092344234"),
     *            @OA\Property(property="personal_reference_1", type="number", format="number", example="Pedro Reyez - 8292344255"),
     *            @OA\Property(property="personal_reference_2", type="number", format="number", example="Carlos Sanchez - 8092311231"),
     *         ),
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

    public function register(Request $request)
    {
        $applicant = new Applicants(request()->all());
        $applicant->save();
        if (isset($request->kid_id)) {
            $applicant->kids()->attach(['kid_id' => $request->kid_id]);
        }
        return response()->json(["data"=>$applicant],200);
    }

    /**
     * @OA\Put(
     *     path="/api/applicants/{id}",
     *     operationId="update_applicants",
     *     tags={"Applicants"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update applicant",
     *     description="Update applicant",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example="Juan"),
     *            @OA\Property(property="last_name", type="string", format="string", example="Peralta"),
     *            @OA\Property(property="type_identification", type="string", example="Cedula"),
     *            @OA\Property(property="identification", type="string", format="string", example="40224522776"),
     *            @OA\Property(property="parent", type="string", format="string", example="Primo"),
     *            @OA\Property(property="marital_status", type="string", example="Soltero"),
     *            @OA\Property(property="phone", type="string", format="string", example="8099877765"),
     *            @OA\Property(property="residence_phone", type="string", format="string", example="8099886700"),
     *            @OA\Property(property="address", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="military", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="institution", type="string", format="string", example="ARD"),
     *            @OA\Property(property="email", type="string", format="string", example="juan@gmail.com"),
     *            @OA\Property(property="kid_id", type="number", format="number", example="1"),
     *            @OA\Property(property="work_reference", type="string", format="string", example="Ramon Acosta - 8092344234"),
     *            @OA\Property(property="personal_reference_1", type="number", format="number", example="Pedro Reyez - 8292344255"),
     *            @OA\Property(property="personal_reference_2", type="number", format="number", example="Carlos Sanchez - 8092311231"),
     *         ),
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

    public function update(Request $request, $id){
        try{
            $applicant = Applicants::where('id',$id)->first();
            $applicant->update($request->all());
            if (isset($request->kid_id)) {
                $applicant->kids()->attach(['kid_id' => $request->kid_id]);
            }
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
