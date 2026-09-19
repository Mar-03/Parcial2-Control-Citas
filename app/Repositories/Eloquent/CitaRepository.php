<?php

namespace App\Repositories\Eloquent;

use App\Models\Cita;
use App\Repositories\Contracts\CitaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CitaRepository implements CitaRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        return Cita::query()
            ->with(['paciente', 'doctor'])
            ->when($filters['doctor_id'] ?? null, fn ($query, $doctorId) => $query->where('doctor_id', $doctorId))
            ->when($filters['paciente_id'] ?? null, fn ($query, $pacienteId) => $query->where('paciente_id', $pacienteId))
            ->when($filters['desde'] ?? null, fn ($query, $desde) => $query->where('inicio', '>=', $desde))
            ->when($filters['hasta'] ?? null, fn ($query, $hasta) => $query->where('fin', '<=', $hasta))
            ->orderBy('inicio')
            ->get();
    }

    public function findOrFail(int $id): Cita
    {
        return Cita::query()->with(['paciente', 'doctor'])->findOrFail($id);
    }

    public function create(array $data): Cita
    {
        return Cita::query()->create($data)->load(['paciente', 'doctor']);
    }

    public function update(Cita $cita, array $data): Cita
    {
        $cita->update($data);

        return $cita->fresh(['paciente', 'doctor']);
    }
}
