<?php

use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PacienteController;
use Illuminate\Support\Facades\Route;

Route::get('citas', [CitaController::class, 'index']);
Route::post('citas', [CitaController::class, 'store']);
Route::get('citas/{id}', [CitaController::class, 'show']);
Route::put('citas/{id}', [CitaController::class, 'update']);
Route::patch('citas/{id}/estado', [CitaController::class, 'cambiarEstado']);

Route::get('doctores', [DoctorController::class, 'index']);
Route::get('pacientes', [PacienteController::class, 'index']);
