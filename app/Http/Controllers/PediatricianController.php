<?php

namespace App\Http\Controllers;

use App\Models\Pediatrician;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Pediatricians",
 *     description="API Endpoints para gestión de pediatras"
 * )
 */
class PediatricianController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pediatricians/",
     *     summary="Listar todos los pediatras",
     *     description="Obtiene una lista de todos los pediatras registrados",
     *     operationId="getPediatricians",
     *     tags={"Pediatricians"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pediatras obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="range", type="string", example="Pediatra General"),
     *                     @OA\Property(property="name", type="string", example="Dr. María González"),
     *                     @OA\Property(property="identification", type="string", example="12345678"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $pediatricians = Pediatrician::all();
        return response()->json([
            'success' => true,
            'data' => $pediatricians
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/pediatricians/",
     *     summary="Crear un nuevo pediatra",
     *     description="Crea un nuevo pediatra con los datos proporcionados",
     *     operationId="createPediatrician",
     *     tags={"Pediatricians"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"range","name","identification"},
     *             @OA\Property(property="range", type="string", example="Pediatra General", description="Rango o especialidad del pediatra"),
     *             @OA\Property(property="name", type="string", example="Dr. María González", description="Nombre completo del pediatra"),
     *             @OA\Property(property="identification", type="string", example="12345678", description="Número de identificación único")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pediatra creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pediatrician created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="range", type="string", example="Pediatra General"),
     *                 @OA\Property(property="name", type="string", example="Dr. María González"),
     *                 @OA\Property(property="identification", type="string", example="12345678"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="range", type="array", @OA\Items(type="string", example="The range field is required.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     *                 @OA\Property(property="identification", type="array", @OA\Items(type="string", example="The identification field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'range' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'identification' => 'required|string|max:255|unique:pediatricians,identification'
            ]);

            $pediatrician = Pediatrician::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pediatrician created successfully',
                'data' => $pediatrician
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/pediatricians/{id}/",
     *     summary="Obtener un pediatra específico",
     *     description="Obtiene los datos de un pediatra por su ID",
     *     operationId="getPediatrician",
     *     tags={"Pediatricians"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del pediatra",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pediatra encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="range", type="string", example="Pediatra General"),
     *                 @OA\Property(property="name", type="string", example="Dr. María González"),
     *                 @OA\Property(property="identification", type="string", example="12345678"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pediatra no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pediatrician not found")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $pediatrician = Pediatrician::find($id);

        if (!$pediatrician) {
            return response()->json([
                'success' => false,
                'message' => 'Pediatrician not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pediatrician
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/pediatricians/{id}/",
     *     summary="Actualizar un pediatra",
     *     description="Actualiza los datos de un pediatra existente",
     *     operationId="updatePediatrician",
     *     tags={"Pediatricians"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del pediatra",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="range", type="string", example="Pediatra Especialista", description="Rango o especialidad del pediatra"),
     *             @OA\Property(property="name", type="string", example="Dr. María González", description="Nombre completo del pediatra"),
     *             @OA\Property(property="identification", type="string", example="12345678", description="Número de identificación único")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pediatra actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pediatrician updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="range", type="string", example="Pediatra Especialista"),
     *                 @OA\Property(property="name", type="string", example="Dr. María González"),
     *                 @OA\Property(property="identification", type="string", example="12345678"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-07T22:40:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-07T22:41:09.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pediatra no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pediatrician not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="identification", type="array", @OA\Items(type="string", example="The identification has already been taken."))
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $pediatrician = Pediatrician::find($id);

            if (!$pediatrician) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pediatrician not found'
                ], 404);
            }

            $validated = $request->validate([
                'range' => 'sometimes|required|string|max:255',
                'name' => 'sometimes|required|string|max:255',
                'identification' => 'sometimes|required|string|max:255|unique:pediatricians,identification,' . $id
            ]);

            $pediatrician->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pediatrician updated successfully',
                'data' => $pediatrician
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/pediatricians/{id}/",
     *     summary="Eliminar un pediatra",
     *     description="Elimina un pediatra de la base de datos",
     *     operationId="deletePediatrician",
     *     tags={"Pediatricians"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del pediatra",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pediatra eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pediatrician deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pediatra no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pediatrician not found")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $pediatrician = Pediatrician::find($id);

        if (!$pediatrician) {
            return response()->json([
                'success' => false,
                'message' => 'Pediatrician not found'
            ], 404);
        }

        $pediatrician->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pediatrician deleted successfully'
        ]);
    }
}
