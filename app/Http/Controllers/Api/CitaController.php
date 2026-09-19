<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CitaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function __construct(private CitaService $citas)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->citas->listar()]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Cita creada correctamente.',
            'data' => $this->citas->crear($request->all()),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(['data' => $this->citas->detalle($id)]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'message' => 'Cita actualizada correctamente.',
            'data' => $this->citas->actualizar($id, $request->all()),
        ]);
    }

    public function cambiarEstado(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'message' => 'Estado de la cita actualizado correctamente.',
            'data' => $this->citas->cambiarEstado($id, $request->all()),
        ]);
    }
}
