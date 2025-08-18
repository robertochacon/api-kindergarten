<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedPersons;
use Exception;
use Illuminate\Http\Request;

class AuthorizedPersonsController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/authorizations",
     *      operationId="all_authorization",
     *     tags={"Authorized persons"},
     *     summary="All authorizations",
     *     description="All authorizations",
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
     *              @OA\Property(property="military", type="integer", example=1),
     *              @OA\Property(property="file", type="string", format="string", example=""),
     *              @OA\Property(property="other", type="string", example="Observaciones adicionales"),
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
        $authorizedPersons = AuthorizedPersons::with('applicant')->paginate(10);
        return response()->json(["data"=>$authorizedPersons],200);
    }


     /**
     * @OA\Get (
     *     path="/api/authorizations/{id}",
     *     operationId="watch_authorization",
     *     tags={"Authorized persons"},
     *     summary="See authorization",
     *     description="See authorization",
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
     *              @OA\Property(property="military", type="integer", example=1),
     *              @OA\Property(property="file", type="string", format="string", example=""),
     *              @OA\Property(property="other", type="string", example="Observaciones adicionales"),
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
            $authorizedPerson = AuthorizedPersons::with('applicant')->find($id);
            return response()->json(["data"=>$authorizedPerson],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/authorizations",
     *      operationId="store_authorization",
     *      tags={"Authorized persons"},
     *      summary="Store authorization",
     *      description="Store authorization",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example="Juan"),
     *            @OA\Property(property="last_name", type="string", format="string", example="Santos"),
     *            @OA\Property(property="identification", type="string", format="string", example="00123492349"),
     *            @OA\Property(property="parent", type="string", format="string", example="Tio"),
     *            @OA\Property(property="phone", type="string", format="string", example="8293242233"),
     *            @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example="Santo Domingo"),
     *            @OA\Property(property="military", type="integer", example=1),
     *            @OA\Property(property="kid_id", type="number", format="number", example="1"),
     *            @OA\Property(property="other", type="string", format="string", example="Observaciones adicionales"),
     *            @OA\Property(
     *                property="file",
     *                type="string",
     *                format="binary",
     *                description="Upload a document related to the kid (PDF, JPG, etc.)"
     *            ),
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
        $authorizedPerson = new AuthorizedPersons(request()->all());
        $authorizedPerson->save();
        return response()->json(["data"=>$authorizedPerson],200);
    }

    /**
     * @OA\Put(
     *     path="/api/authorizations/{id}",
     *     operationId="update_authorization",
     *     tags={"Authorized persons"},
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
     *            @OA\Property(property="last_name", type="string", format="string", example=""),
     *            @OA\Property(property="identification", type="string", format="string", example=""),
     *            @OA\Property(property="parent", type="string", format="string", example=""),
     *            @OA\Property(property="phone", type="string", format="string", example=""),
     *            @OA\Property(property="residence_phone", type="string", format="string", example=""),
     *            @OA\Property(property="address", type="string", format="string", example=""),
     *            @OA\Property(property="military", type="integer", example=1),
     *            @OA\Property(property="kid_id", type="number", format="number", example="1"),
     *            @OA\Property(property="other", type="string", format="string", example="Observaciones adicionales"),
     *            @OA\Property(
     *                property="file",
     *                type="string",
     *                format="binary",
     *                description="Upload a document related to the kid (PDF, JPG, etc.)"
     *            ),
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
            $authorizedPerson = AuthorizedPersons::where('id',$id)->first();
            $authorizedPerson->update($request->all());
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
            AuthorizedPersons::where('id', $id)->delete();
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

}
