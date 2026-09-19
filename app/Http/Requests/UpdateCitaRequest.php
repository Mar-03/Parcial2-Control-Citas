<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateCitaRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'integer', 'exists:doctores,id'],
            'inicio' => ['required', 'date'],
            'fin' => ['required', 'date', 'after:inicio'],
            'motivo' => ['required', 'string', 'max:255'],
            'estado' => ['nullable', Rule::in(['pendiente', 'confirmada', 'cancelada', 'atendida'])],
        ];
    }
}
