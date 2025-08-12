<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

/**
 * @OA\Tag(
 *     name="Maintenance",
 *     description="Endpoints para ejecutar tareas de mantenimiento (protegidos por token)"
 * )
 */
class MaintenanceController extends Controller
{
    private function authorizeRequest(Request $request): ?JsonResponse
    {
        $token = $request->header('X-Maintenance-Token');
        $expected = env('MAINTENANCE_TOKEN');
        if (empty($expected) || $token !== $expected) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized maintenance request'
            ], 403);
        }
        return null;
    }

    /**
     * @OA\Post(
     *     path="/api/maintenance/migrate-fresh-seed",
     *     summary="Ejecuta php artisan migrate:fresh --seed",
     *     tags={"Maintenance"},
     *     @OA\Parameter(
     *         name="X-Maintenance-Token",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function migrateFreshSeed(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        return response()->json([
            'success' => true,
            'message' => 'migrate:fresh --seed executed',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/maintenance/cache-clear",
     *     summary="Ejecuta php artisan cache:clear",
     *     tags={"Maintenance"},
     *     @OA\Parameter(name="X-Maintenance-Token", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function cacheClear(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }
        Artisan::call('cache:clear');
        return response()->json(['success' => true, 'message' => 'cache:clear executed']);
    }

    /**
     * @OA\Post(
     *     path="/api/maintenance/config-clear",
     *     summary="Ejecuta php artisan config:clear",
     *     tags={"Maintenance"},
     *     @OA\Parameter(name="X-Maintenance-Token", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function configClear(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }
        Artisan::call('config:clear');
        return response()->json(['success' => true, 'message' => 'config:clear executed']);
    }

    /**
     * @OA\Post(
     *     path="/api/maintenance/route-clear",
     *     summary="Ejecuta php artisan route:clear",
     *     tags={"Maintenance"},
     *     @OA\Parameter(name="X-Maintenance-Token", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function routeClear(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }
        Artisan::call('route:clear');
        return response()->json(['success' => true, 'message' => 'route:clear executed']);
    }

    /**
     * @OA\Post(
     *     path="/api/maintenance/optimize-clear",
     *     summary="Ejecuta php artisan optimize:clear",
     *     tags={"Maintenance"},
     *     @OA\Parameter(name="X-Maintenance-Token", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function optimizeClear(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }
        Artisan::call('optimize:clear');
        return response()->json(['success' => true, 'message' => 'optimize:clear executed']);
    }

    /**
     * @OA\Post(
     *     path="/api/maintenance/swagger-generate",
     *     summary="Ejecuta php artisan l5-swagger:generate",
     *     tags={"Maintenance"},
     *     @OA\Parameter(name="X-Maintenance-Token", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function swaggerGenerate(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }
        Artisan::call('l5-swagger:generate');
        return response()->json(['success' => true, 'message' => 'l5-swagger:generate executed']);
    }
} 