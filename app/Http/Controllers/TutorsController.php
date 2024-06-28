<?php

namespace App\Http\Controllers;

use App\Models\Tutors;
use Illuminate\Http\Request;

class TutorsController extends Controller
{
        /**
     * @OA\Get (
     *     path="/api/tutors",
     *      operationId="all_tutor",
     *     tags={"Tutors"},
     *     security={{ "apiAuth": {} }},
     *     summary="All tutors",
     *     description="All tutors",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="identification", type="string", example=""),
     *              @OA\Property(property="parent", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="military", type="boolean", format="boolean", example=""),
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
        $tutors = Tutors::with('kids')->paginate(10);
        return response()->json(["data"=>$tutors],200);
    }


     /**
     * @OA\Get (
     *     path="/api/tutors/{id}",
     *     operationId="watch_tutor",
     *     tags={"Tutors"},
     *     security={{ "apiAuth": {} }},
     *     summary="See tutor",
     *     description="See tutor",
     *     @OA\Parameter(
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
     *              @OA\Property(property="identification", type="string", example=""),
     *              @OA\Property(property="parent", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="military", type="boolean", format="boolean", example="true"),
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
            $tutor = Tutors::with('kids')->find($id);
            return response()->json(["data"=>$tutor],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/tutors",
     *      operationId="store_tutor",
     *      tags={"Tutors"},
     *     security={{ "apiAuth": {} }},
     *      summary="Store tutor",
     *      description="Store tutor",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example=""),
     *            @OA\Property(property="last_name", type="string", format="string", example=""),
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
     *            @OA\Property(property="military", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="kid_id", type="number", format="number", example="1"),
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
        $tutor = new Tutors(request()->all());
        $tutor->save();
        if (isset($request->kid_id)) {
            $tutor->kids()->attach(['kid_id' => $request->kid_id]);
        }
        return response()->json(["data"=>$tutor],200);
    }

    /**
     * @OA\Put(
     *     path="/api/tutors/{id}",
     *     operationId="update_tutor",
     *     tags={"Tutors"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update tutor",
     *     description="Update tutor",
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
     *            @OA\Property(property="name", type="string", format="string", example=""),
     *            @OA\Property(property="last_name", type="string", format="string", example=""),
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
     *            @OA\Property(property="military", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="kid_id", type="number", format="number", example="1"),
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
            $tutor = Tutors::where('id',$id)->first();
            $tutor->update($request->all());
            if (isset($request->kid_id)) {
                $tutor->kids()->attach(['kid_id' => $request->kid_id]);
            }
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/tutors/{id}",
     *      operationId="delete_tutor",
     *      tags={"Tutors"},
     *     security={{ "apiAuth": {} }},
     *      summary="Delete tutor",
     *      description="Delete tutor",
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
            Tutors::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

}
