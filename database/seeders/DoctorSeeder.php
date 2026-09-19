<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        Doctor::query()->delete();

        Doctor::create([
            'nombre' => 'Dr. Juan Morales',
            'especialidad' => 'Medicina General',
            'email' => 'juan@hospital.test',
        ]);

        Doctor::create([
            'nombre' => 'Dra. Laura Castillo',
            'especialidad' => 'Pediatría',
            'email' => 'laura@hospital.test',
        ]);

        Doctor::create([
            'nombre' => 'Dr. Pedro Gómez',
            'especialidad' => 'Cardiología',
            'email' => 'pedro@hospital.test',
        ]);
    }
}