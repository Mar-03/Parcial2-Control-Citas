<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ConflictoCitaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_crea_cita_sin_conflicto(): void
    {
        $this->postJson('/api/citas', $this->payload())
            ->assertCreated();
    }

    public function test_doble_reserva_devuelve_409(): void
    {
        $this->postJson('/api/citas', $this->payload())->assertCreated();

        $this->postJson('/api/citas', $this->payload())
            ->assertStatus(409)
            ->assertJsonPath('message', 'El doctor ya posee una cita activa en ese horario.');
    }

    public function test_cita_cancelada_no_bloquea(): void
    {
        $creada = $this->postJson('/api/citas', $this->payload())->assertCreated();
        $id = $creada->json('data.id');

        $this->patchJson("/api/citas/{$id}/estado", ['estado' => 'cancelada'])->assertOk();

        $this->postJson('/api/citas', $this->payload())
            ->assertCreated();
    }

    public function test_reprogramacion_conflictiva_devuelve_409(): void
    {
        $creada = $this->postJson('/api/citas', $this->payload())->assertCreated();
        $id = $creada->json('data.id');

        $this->postJson('/api/citas', [
            'paciente_id' => 1,
            'doctor_id' => 1,
            'inicio' => now()->addDays(3)->setTime(14, 0)->toDateTimeString(),
            'fin' => now()->addDays(3)->setTime(15, 0)->toDateTimeString(),
            'motivo' => 'Cita alternativa',
        ])->assertCreated();

        $this->putJson("/api/citas/{$id}", [
            'paciente_id' => 1,
            'doctor_id' => 1,
            'inicio' => now()->addDays(3)->setTime(14, 0)->toDateTimeString(),
            'fin' => now()->addDays(3)->setTime(15, 0)->toDateTimeString(),
            'motivo' => 'Consulta reprogramada',
        ])
            ->assertStatus(409)
            ->assertJsonPath('message', 'El doctor ya posee una cita activa en ese horario.');
    }

    public function test_patch_estado_persiste(): void
    {
        $creada = $this->postJson('/api/citas', $this->payload())->assertCreated();
        $id = $creada->json('data.id');

        $this->patchJson("/api/citas/{$id}/estado", ['estado' => 'confirmada'])
            ->assertOk()
            ->assertJsonPath('data.estado', 'confirmada');

        $this->assertDatabaseHas('citas', ['id' => $id, 'estado' => 'confirmada']);
    }

    private function payload(): array
    {
        return [
            'paciente_id' => 1,
            'doctor_id' => 1,
            'inicio' => now()->addDays(5)->setTime(9, 0)->toDateTimeString(),
            'fin' => now()->addDays(5)->setTime(10, 0)->toDateTimeString(),
            'motivo' => 'Consulta general',
        ];
    }
}