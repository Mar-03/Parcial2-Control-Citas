<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarEstadoCitaRequest;
use App\Http\Requests\ListCitaRequest;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use App\Services\CitaService;
use Illuminate\Http\JsonResponse;

class CitaController extends Controller
{
    public function __construct(private CitaService $citas)
    {
    }

    public function index(ListCitaRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->citas->listar($request->validated())]);
    }

    public function store(StoreCitaRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Cita creada correctamente.',
            'data' => $this->citas->crear($request->validated()),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(['data' => $this->citas->detalle($id)]);
    }

    public function update(UpdateCitaRequest $request, int $id): JsonResponse
    {
        return response()->json([
            'message' => 'Cita actualizada correctamente.',
            'data' => $this->citas->actualizar($id, $request->validated()),
        ]);
    }

    public function cambiarEstado(CambiarEstadoCitaRequest $request, int $id): JsonResponse
    {
        return response()->json([
            'message' => 'Estado de la cita actualizado correctamente.',
            'data' => $this->citas->cambiarEstado($id, $request->validated()),
        ]);
    }
}
