<?php

namespace App\Http\Requests;

class ListCitaRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'doctor_id' => ['nullable', 'integer', 'exists:doctores,id'],
            'paciente_id' => ['nullable', 'integer', 'exists:pacientes,id'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ];
    }
}
