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
     *     @OA\Parameter(
     *          in="query",
     *          name="code",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="name",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="last_name",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="start_date",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="end_date",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="kid_id", type="number", example=1),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
     *              @OA\Property(property="entry_time", type="string", example="2023-02-23T08:00:00.000000Z"),
     *              @OA\Property(property="exit_time", type="string", example="2023-02-23T16:00:00.000000Z"),
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
        $code = $request->code;
        $name = $request->name;
        $lastName = $request->last_name;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $attendances = Attendance::with(['kid', 'user']);

        if (!empty($code) || !empty($name) || !empty($lastName) || !empty($startDate) || !empty($endDate)) {
            $attendances->whereHas('kid', function ($query) use ($code, $name, $lastName) {
                if (!empty($code)) {
                    $query->where('code', 'like', '%' . $code . '%');
                }
                if (!empty($name)) {
                    $query->where('name', 'like', '%' . $name . '%');
                }
                if (!empty($lastName)) {
                    $query->where('last_name', 'like', '%' . $lastName . '%');
                }
            });

            if (!empty($startDate) && !empty($endDate)) {
                $attendances->whereBetween('created_at', [$startDate, $endDate]);
            } elseif (!empty($startDate)) {
                $attendances->where('created_at', '>=', $startDate);
            } elseif (!empty($endDate)) {
                $attendances->where('created_at', '<=', $endDate);
            }
        }

        $attendances = $attendances->paginate(10);

        return response()->json(["data" => $attendances], 200);

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
     *         name="code",
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
     *              @OA\Property(property="entry_time", type="string", example="2023-02-23T08:00:00.000000Z"),
     *              @OA\Property(property="exit_time", type="string", example="2023-02-23T16:00:00.000000Z"),
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

    public function watch($code){
        try{
            $attendance = Attendance::with('kid')->with('user')->where("code",$code)->first();
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
     *              @OA\Property(property="code", type="number", example="DV24-0001"),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
     *              @OA\Property(property="entry_time", type="string", example="2023-02-23T08:00:00.000000Z"),
     *              @OA\Property(property="exit_time", type="string", example="2023-02-23T16:00:00.000000Z"),
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
             $kid = Kids::where('code', $request->code)->firstOrFail();

             $existingAttendance = Attendance::where('kid_id', $kid->id)
                 ->whereNull('exit_time')
                 ->first();

             if ($existingAttendance) {
                 $existingAttendance->exit_time = now();
                 $existingAttendance->save();
                 return response()->json(["message" => "Salida registrada exitosamente", "data" => $existingAttendance], 200);
             } else {
                 $attendance = new Attendance();
                 $attendance->kid_id = $kid->id;
                 $attendance->user_id = $request->user_id;
                 $attendance->entry_time = now();
                 $attendance->save();
                 return response()->json(["message" => "Entrada registrada exitosamente", "data" => $attendance], 200);
             }

         } catch (Exception $e) {
             return response()->json(["message" => "Error al registrar asistencia", "error" => $e->getMessage()], 500);
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
     *              @OA\Property(property="code", type="number", example="DV24-0001"),
     *              @OA\Property(property="user_id", type="number", example=1),
     *              @OA\Property(property="attendance", type="boolean", example=true),
     *              @OA\Property(property="entry_time", type="string", example="2023-02-23T08:00:00.000000Z"),
     *              @OA\Property(property="exit_time", type="string", example="2023-02-23T16:00:00.000000Z"),
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
            $kid = Kids::where('code',$request->code)->first();
            $attendance->kid_id = $kid->id;
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
