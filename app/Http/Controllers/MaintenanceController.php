<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

/**
 * @OA\Tag(
 *     name="Maintenance",
 *     description="Endpoint para ejecutar comandos de mantenimiento (protegido por token)"
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
     *     path="/api/maintenance/run",
     *     summary="Ejecuta un comando de Artisan permitido",
     *     tags={"Maintenance"},
     *     @OA\Parameter(name="X-Maintenance-Token", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"command"},
     *             @OA\Property(property="command", type="string", example="migrate:fresh"),
     *             @OA\Property(
     *                 property="options",
     *                 type="object",
     *                 example={"--seed": true, "--force": true}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=400, description="Bad Request")
     * )
     */
    public function run(Request $request): JsonResponse
    {
        if ($resp = $this->authorizeRequest($request)) {
            return $resp;
        }

        $command = (string) $request->input('command');
        $options = (array) ($request->input('options') ?? []);

        // Whitelist de comandos permitidos
        $allowed = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'optimize:clear',
            'migrate',
            'migrate:fresh',
            'db:seed',
            'l5-swagger:generate',
        ];

        if (!in_array($command, $allowed, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Command not allowed',
                'allowed' => $allowed,
            ], 400);
        }

        // Forzar --force en comandos de migración en producción
        if (str_starts_with($command, 'migrate')) {
            $options['--force'] = true;
        }

        $exitCode = Artisan::call($command, $options);
        $output = Artisan::output();

        return response()->json([
            'success' => $exitCode === 0,
            'command' => $command,
            'options' => $options,
            'exit_code' => $exitCode,
            'output' => $output,
        ]);
    }
} 