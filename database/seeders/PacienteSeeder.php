<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        Paciente::query()->delete();

        Paciente::create([
            'nombre' => 'Ana',
            'apellido' => 'López',
            'email' => 'ana@example.com',
            'telefono' => '5555-1111',
        ]);

        Paciente::create([
            'nombre' => 'Carlos',
            'apellido' => 'Pérez',
            'email' => 'carlos@example.com',
            'telefono' => '5555-2222',
        ]);

        Paciente::create([
            'nombre' => 'María',
            'apellido' => 'García',
            'email' => 'maria@example.com',
            'telefono' => '5555-3333',
        ]);
    }
}