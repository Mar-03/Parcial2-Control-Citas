<?php

namespace App\Exceptions;

use RuntimeException;

class ConflictoHorarioException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('El doctor ya posee una cita activa en ese horario.');
    }
}