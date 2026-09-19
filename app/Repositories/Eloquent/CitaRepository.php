<?php

namespace App\Repositories\Eloquent;

use App\Models\Cita;
use App\Repositories\Contracts\CitaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CitaRepository implements CitaRepositoryInterface
{
    public function all(): Collection
    {
        return Cita::query()->with(['paciente', 'doctor'])->orderBy('inicio')->get();
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
