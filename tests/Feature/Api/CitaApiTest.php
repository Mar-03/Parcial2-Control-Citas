<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CitaApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_crea_y_filtra_citas_por_doctor(): void
    {
        $response = $this->postJson('/api/citas', [
            'paciente_id' => 1,
            'doctor_id' => 1,
            'inicio' => now()->addDays(2)->setTime(11, 0)->toDateTimeString(),
            'fin' => now()->addDays(2)->setTime(12, 0)->toDateTimeString(),
            'motivo' => 'Control de seguimiento',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Cita creada correctamente.')
            ->assertJsonPath('data.doctor_id', 1);

        $this->getJson('/api/citas?doctor_id=1')
            ->assertOk()
            ->assertJsonPath('data.0.doctor_id', 1);
    }

    public function test_rechaza_fechas_invalidas(): void
    {
        $this->postJson('/api/citas', [
            'paciente_id' => 1,
            'doctor_id' => 1,
            'inicio' => '2026-09-20 10:00:00',
            'fin' => '2026-09-20 09:00:00',
            'motivo' => 'Fecha inválida',
        ])
            ->assertStatus(400)
            ->assertJsonPath('message', 'Los datos proporcionados no son válidos.')
            ->assertJsonStructure(['errors' => ['fin']]);
    }
}
