<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CambiarEstadoCitaRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'estado' => ['required', Rule::in(['pendiente', 'confirmada', 'cancelada', 'atendida'])],
        ];
    }
}
