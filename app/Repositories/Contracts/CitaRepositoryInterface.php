<?php

namespace App\Repositories\Contracts;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Collection;

interface CitaRepositoryInterface
{
    public function all(): Collection;

    public function findOrFail(int $id): Cita;

    public function create(array $data): Cita;

    public function update(Cita $cita, array $data): Cita;
}
