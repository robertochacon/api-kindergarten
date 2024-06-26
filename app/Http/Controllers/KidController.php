<?php

namespace App\Http\Controllers;

use App\Models\Kids;
use Illuminate\Http\Request;

class KidController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/kids",
     *      operationId="all_kids",
     *     tags={"Kids"},
     *     security={{ "apiAuth": {} }},
     *     summary="All kids",
     *     description="All kids",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="gender", type="string", example=""),
     *              @OA\Property(property="born_date", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="classroom", type="string", example=""),
     *              @OA\Property(property="insurance", type="string", example=""),
     *              @OA\Property(property="insurance_number", type="string", example=""),
     *              @OA\Property(property="allergies", type="string", example=""),
     *              @OA\Property(property="medical_conditions", type="string", example=""),
     *              @OA\Property(property="medications", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      )
     * )
     */
    public function index()
    {
        $kids = Kids::with('tutors')->with('parents')->with('authorizations')->paginate(10);
        return response()->json(["data"=>$kids],200);
    }


     /**
     * @OA\Get (
     *     path="/api/kids/{id}",
     *     operationId="watch_kid",
     *     tags={"Kids"},
     *     security={{ "apiAuth": {} }},
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
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="gender", type="string", example=""),
     *              @OA\Property(property="born_date", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="classroom", type="string", example=""),
     *              @OA\Property(property="insurance", type="string", example=""),
     *              @OA\Property(property="insurance_number", type="string", example=""),
     *              @OA\Property(property="allergies", type="string", example=""),
     *              @OA\Property(property="medical_conditions", type="string", example=""),
     *              @OA\Property(property="medications", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      )
     * )
     */

    public function watch($id){
        try{
            $kids = Kids::with('tutors')->with('parents')->with('authorizations')->find($id);
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
     *     security={{ "apiAuth": {} }},
     *      summary="Store kid",
     *      description="Store kid",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example="Daniel"),
     *            @OA\Property(property="last_name", type="string", format="string", example="Chacon"),
     *            @OA\Property(property="gender", type="string", format="string", example="Masculino"),
     *            @OA\Property(property="born_date", type="string", format="string", example="12/03/2024"),
     *            @OA\Property(property="address", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="classroom", type="string", format="string", example="2do año"),
     *            @OA\Property(property="insurance", type="string", format="string", example="Senasa"),
     *            @OA\Property(property="insurance_number", type="string", format="string", example="0909121"),
     *            @OA\Property(property="allergies", type="string", format="string", example="Ninguna"),
     *            @OA\Property(property="medical_conditions", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="medications", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="tutor_id", type="number", format="number", example="1"),
     *            @OA\Property(property="parent_id", type="number", format="number", example="1"),
     *            @OA\Property(property="authorization_id", type="number", format="number", example="1"),
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
        $kids = new Kids(request()->all());
        $kids->save();
        if (isset($request->tutor_id)) {
            $kids->tutors()->attach(['tutor_id' => $request->tutor_id]);
        }
        if (isset($request->parent_id)) {
            $kids->parents()->attach(['parent_id' => $request->parent_id]);
        }
        if (isset($request->authorization_id)) {
            $kids->authorizations()->attach(['authorization_id' => $request->authorization_id]);
        }
        return response()->json(["data"=>$kids],200);
    }

    /**
     * @OA\Put(
     *     path="/api/kids/{id}",
     *     operationId="update_kid",
     *     tags={"Kids"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update kid",
     *     description="Update kid",
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
     *            @OA\Property(property="name", type="string", format="string", example="Daniel"),
     *            @OA\Property(property="last_name", type="string", format="string", example="Chacon"),
     *            @OA\Property(property="gender", type="string", format="string", example="M"),
     *            @OA\Property(property="born_date", type="string", format="string", example="12/03/2024"),
     *            @OA\Property(property="address", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="classroom", type="string", format="string", example="2do año"),
     *            @OA\Property(property="insurance", type="string", format="string", example="Senasa"),
     *            @OA\Property(property="insurance_number", type="string", format="string", example="0909121"),
     *            @OA\Property(property="allergies", type="string", format="string", example="Ninguna"),
     *            @OA\Property(property="medical_conditions", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="medications", type="string", format="string", example="Santo Domingo"),
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
            $kids = Kids::where('id',$id)->first();
            $kids->update($request->all());
            if (isset($request->tutor_id)) {
                $kids->tutors()->attach(['tutor_id' => $request->tutor_id]);
            }
            if (isset($request->parent_id)) {
                $kids->parents()->attach(['parent_id' => $request->parent_id]);
            }
            if (isset($request->authorization_id)) {
                $kids->authorizations()->attach(['authorization_id' => $request->authorization_id]);
            }
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
     *     security={{ "apiAuth": {} }},
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
            Kids::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }
}
