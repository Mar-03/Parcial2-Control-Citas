<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CitaCalendarioTest extends TestCase
{
    use DatabaseTransactions;

    public function test_la_vista_del_calendario_usa_fullcalendar(): void
    {
        $this->get('/citas')
            ->assertOk()
            ->assertSee('FullCalendar')
            ->assertSee('fullcalendar')
            ->assertSee('dayGridMonth')
            ->assertSee('timeGridWeek')
            ->assertSee('/api/citas')
            ->assertSee('/api/doctores');
    }

    public function test_la_vista_del_calendario_carga_filtro_de_doctores(): void
    {
        $this->get('/citas')
            ->assertOk()
            ->assertSee('filtro-doctor');
    }
}