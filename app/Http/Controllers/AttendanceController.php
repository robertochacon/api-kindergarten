<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Kids;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/attendances",
     *      operationId="all_attendances",
     *     tags={"Attendances of kids"},
     *     security={{ "apiAuth": {} }},
     *     summary="All attendances",
     *     description="All attendances",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="kid_id", type="number", example=1),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
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
        $attendances = Attendance::with('kid')->with('user')->paginate(10);
        return response()->json(["data"=>$attendances],200);
    }


     /**
     * @OA\Get (
     *     path="/api/attendances/{id}",
     *     operationId="watch_attendance",
     *     tags={"Attendances of kids"},
     *     security={{ "apiAuth": {} }},
     *     summary="See attendance",
     *     description="See attendance",
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
     *              @OA\Property(property="kid_id", type="number", example=1),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
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
            $attendance = Attendance::with('kid')->with('user')->find($id);
            return response()->json(["data"=>$attendance],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/attendances",
     *      operationId="store_attendance",
     *      tags={"Attendances of kids"},
     *     security={{ "apiAuth": {} }},
     *      summary="Store attendance",
     *      description="Store attendance",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *              @OA\Property(property="kid_id", type="number", example=1),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
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
        try {
            $attendance = new Attendance($request->all());
            $attendance->save();
            return response()->json(["data" => $attendance], 200);

        } catch (Exception $e) {
            return response()->json(["data"=>"Problemas tecnicos"],500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/attendances/{id}",
     *     operationId="update_attendance",
     *     tags={"Attendances of kids"},
     *     security={{ "apiAuth": {} }},
     *     summary="Update attendance",
     *     description="Update attendance",
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
     *              @OA\Property(property="kid_id", type="number", example=1),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
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
            $attendance = Attendance::where('id',$id)->first();
            $attendance->update($request->all());
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"Problemas tecnicos"],500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/attendances/{id}",
     *      operationId="delete_attendance",
     *      tags={"Attendances of kids"},
     *     security={{ "apiAuth": {} }},
     *      summary="Delete attendance",
     *      description="Delete attendance",
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
            Attendance::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"Problemas tecnicos"],500);
        }
    }

}
