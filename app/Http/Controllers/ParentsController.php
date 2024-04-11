<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/parents",
     *      operationId="all_parents",
     *     tags={"Parents"},
     *     security={{ "apiAuth": {} }},
     *     summary="All parents",
     *     description="All parents",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="lastname", type="string", example=""),
     *              @OA\Property(property="identification", type="string", example=""),
     *              @OA\Property(property="parent", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
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
        $tutors = Parents::with('kids')->paginate(10);
        return response()->json(["data"=>$tutors],200);
    }


     /**
     * @OA\Get (
     *     path="/api/parents/{id}",
     *     operationId="watch_parents",
     *     tags={"Parents"},
     *     security={{ "apiAuth": {} }},
     *     summary="See parent",
     *     description="See parent",
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
     *              @OA\Property(property="lastname", type="string", example=""),
     *              @OA\Property(property="identification", type="string", example=""),
     *              @OA\Property(property="parent", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
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
            $tutor = Parents::with('kids')->find($id);
            return response()->json(["data"=>$tutor],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/parents",
     *      operationId="store_parents",
     *      tags={"Parents"},
     *     security={{ "apiAuth": {} }},
     *      summary="Store parent",
     *      description="Store parent",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example=""),
     *            @OA\Property(property="lastname", type="string", format="string", example="Chacon"),
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
     *            @OA\Property(property="military", type="boolean", format="boolean", example="true"),
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
        $tutor = new Parents(request()->all());
        $tutor->save();
        return response()->json(["data"=>$tutor],200);
    }

    /**
     * @OA\Put(
     *     path="/api/parents/{id}",
     *     operationId="update_parents",
     *     tags={"Parents"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update parent",
     *     description="Update parent",
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
     *            @OA\Property(property="lastname", type="string", format="string", example="Chacon"),
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
     *            @OA\Property(property="military", type="boolean", format="boolean", example="true"),
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
            $tutor = Parents::where('id',$id)->first();
            $tutor->update($request->all());
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/parents/{id}",
     *      operationId="delete_parents",
     *      tags={"Parents"},
     *     security={{ "apiAuth": {} }},
     *      summary="Delete parent",
     *      description="Delete parent",
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
            Parents::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

}
