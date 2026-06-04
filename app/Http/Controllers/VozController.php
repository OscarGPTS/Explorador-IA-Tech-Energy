<?php

namespace App\Http\Controllers;

use App\Services\VozService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class VozController extends Controller
{
    private VozService $vozService;

    public function __construct(VozService $vozService)
    {
        $this->vozService = $vozService;
    }

    /**
     * Vista de prueba para validar el servicio de voz.
     */
    public function index(): View
    {
        return view('voz.index');
    }

    /**
     * Health del servicio de voz.
     */
    public function health(): JsonResponse
    {
        return response()->json($this->vozService->health());
    }

    /**
     * Recibe el audio grabado en el navegador y lo reenvía al servicio de voz.
     */
    public function consulta(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // 25 MB = 25600 KB, según el límite del servicio.
            'file' => 'required|file|max:25600',
            'formato_respuesta' => 'nullable|in:texto,ambos',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->vozService->consulta(
            $request->file('file'),
            $request->input('formato_respuesta', 'ambos')
        );

        return response()->json($result);
    }
}
