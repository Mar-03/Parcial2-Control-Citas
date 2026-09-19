<?php

namespace Database\Seeders;

use App\Models\Cita;
use Illuminate\Database\Seeder;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        Cita::query()->delete();

        Cita::create([
            'paciente_id' => 1,
            'doctor_id' => 1,
            'inicio' => now()->addDay()->setTime(9, 0),
            'fin' => now()->addDay()->setTime(10, 0),
            'motivo' => 'Consulta general',
            'estado' => 'pendiente',
        ]);
    }
}