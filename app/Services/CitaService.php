<?php

namespace App\Services;

use App\Exceptions\ConflictoHorarioException;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Repositories\Contracts\CitaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CitaService
{
    public function __construct(private CitaRepositoryInterface $citas)
    {
    }

    public function listar(array $filters = []): Collection
    {
        return $this->citas->all($filters);
    }

    public function detalle(int $id): Cita
    {
        return $this->citas->findOrFail($id);
    }

    public function crear(array $data): Cita
    {
        $this->validarDisponibilidad($data['doctor_id'], $data['inicio'], $data['fin']);

        return $this->citas->create($data);
    }

    public function actualizar(int $id, array $data): Cita
    {
        $this->validarDisponibilidad($data['doctor_id'], $data['inicio'], $data['fin'], $id);

        return $this->citas->update($this->detalle($id), $data);
    }

    public function cambiarEstado(int $id, array $data): Cita
    {
        return $this->citas->update($this->detalle($id), $data);
    }

    private function validarDisponibilidad(int $doctorId, $inicio, $fin, ?int $exceptId = null): void
    {
        if ($this->citas->existeConflicto($doctorId, $inicio, $fin, $exceptId)) {
            throw new ConflictoHorarioException();
        }
    }

    public function doctores(): Collection
    {
        return Doctor::query()->orderBy('nombre')->get();
    }

    public function pacientes(): Collection
    {
        return Paciente::query()->orderBy('nombre')->orderBy('apellido')->get();
    }
}
