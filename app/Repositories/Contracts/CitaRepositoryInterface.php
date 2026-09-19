<?php

namespace App\Repositories\Contracts;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Collection;

interface CitaRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function findOrFail(int $id): Cita;

    public function existeConflicto(int $doctorId, $inicio, $fin, ?int $exceptId = null): bool;

    public function create(array $data): Cita;

    public function update(Cita $cita, array $data): Cita;
}
