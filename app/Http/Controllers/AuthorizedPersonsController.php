<?php

namespace App\Http\Controllers;

use App\Models\Authorizations;
use Illuminate\Http\Request;

class AuthorizedPersonsController extends Controller
{

    /**
     * @OA\Get (
     *     path="/api/authorizations",
     *      operationId="all_authorization",
     *     tags={"Authorized persons"},
     *     security={{ "apiAuth": {} }},
     *     summary="All authorizations",
     *     description="All authorizations",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example=""),
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
        $authorizations = Authorizations::with('kids')->paginate(10);
        return response()->json(["data"=>$authorizations],200);
    }


     /**
     * @OA\Get (
     *     path="/api/authorizations/{id}",
     *     operationId="watch_authorization",
     *     tags={"Authorized persons"},
     *     security={{ "apiAuth": {} }},
     *     summary="See authorization",
     *     description="See authorization",
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
            $authorization = Authorizations::with('kids')->find($id);
            return response()->json(["data"=>$authorization],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/authorizations",
     *      operationId="store_authorization",
     *      tags={"Authorized persons"},
     *     security={{ "apiAuth": {} }},
     *      summary="Store authorization",
     *      description="Store authorization",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example=""),
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
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
        $authorization = new authorizations(request()->all());
        $authorization->save();
        return response()->json(["data"=>$authorization],200);
    }

    /**
     * @OA\Put(
     *     path="/api/authorizations/{id}",
     *     operationId="update_authorization",
     *     tags={"Authorized persons"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update authorization",
     *     description="Update authorization",
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
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
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
            $authorization = Authorizations::where('id',$id)->first();
            $authorization->update($request->all());
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/authorizations/{id}",
     *      operationId="delete_authorization",
     *      tags={"Authorized persons"},
     *     security={{ "apiAuth": {} }},
     *      summary="Delete authorization",
     *      description="Delete authorization",
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
            Authorizations::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

}
