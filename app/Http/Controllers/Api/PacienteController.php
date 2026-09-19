<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CitaService;
use Illuminate\Http\JsonResponse;

class PacienteController extends Controller
{
    public function __construct(private CitaService $citas)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->citas->pacientes()]);
    }
}
