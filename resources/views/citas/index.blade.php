@extends('layouts.app')

@section('title', 'Control de Citas')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <h1 class="h4 mb-0">Control de Citas Médicas</h1>
        <select id="filtro-doctor" class="form-select form-select-sm ms-auto" style="max-width: 240px;">
            <option value="">Todos los doctores</option>
        </select>
    </div>

    <div id="calendario"></div>

    <div id="modal-cita" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <form id="form-cita" class="modal-content" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-titulo">Nueva cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="cita_id" id="cita_id">

                    <div class="mb-3">
                        <label for="paciente_id" class="form-label">Paciente</label>
                        <select name="paciente_id" id="paciente_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="doctor_id" class="form-label">Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="form-select" required></select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inicio" class="form-label">Inicio</label>
                            <input type="datetime-local" name="inicio" id="inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fin" class="form-label">Fin</label>
                            <input type="datetime-local" name="fin" id="fin" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo</label>
                        <input type="text" name="motivo" id="motivo" class="form-control" maxlength="255" required>
                    </div>
                    <div class="mb-3" id="bloque-estado">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="atendida">Atendida</option>
                        </select>
                    </div>
                    <div id="detalle-cita" class="d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btn-guardar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const colores = {
        pendiente: '#f6ad55',
        confirmada: '#63b3ed',
        cancelada: '#a0aec0',
        atendida: '#68d391',
    };

    const filtrar = async (url, params = {}) => {
        const qs = new URLSearchParams(params).toString();
        const res = await fetch(url + (qs ? '?' + qs : ''), { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error('Error de red');
        return (await res.json()).data;
    };

    const poblarDoctores = async () => {
        const doctores = await filtrar('/api/doctores');
        const opciones = doctores.map(d => `<option value="${d.id}">${d.nombre}</option>`).join('');
        document.getElementById('doctor_id').insertAdjacentHTML('beforeend', opciones);
        document.getElementById('filtro-doctor').insertAdjacentHTML('beforeend', opciones);
    };

    const poblarPacientes = async () => {
        const pacientes = await filtrar('/api/pacientes');
        const opciones = pacientes.map(p => `<option value="${p.id}">${p.nombre} ${p.apellido}</option>`).join('');
        document.getElementById('paciente_id').insertAdjacentHTML('beforeend', opciones);
    };

    const calendario = new FullCalendar.Calendar(document.getElementById('calendario'), {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek',
        },
        locale: 'es',
        selectable: true,
        events: async (info, success, failure) => {
            try {
                const params = {};
                const doctor = document.getElementById('filtro-doctor').value;
                if (doctor) params.doctor_id = doctor;
                const citas = await filtrar('/api/citas', params);
                success(citas.map(cita => ({
                    id: cita.id,
                    title: `${cita.paciente.nombre} ${cita.paciente.apellido} (${cita.motivo})`,
                    start: cita.inicio,
                    end: cita.fin,
                    backgroundColor: colores[cita.estado],
                    borderColor: colores[cita.estado],
                    extendedProps: cita,
                })));
            } catch (e) {
                failure(e);
            }
        },
        dateClick: (info) => abrirNueva(info.dateStr),
        eventClick: (info) => abrirDetalle(info.event.extendedProps),
    });

    calendario.render();

    const mostrarError = (mensaje) => alert(mensaje);

    const abrirNueva = (fecha) => {
        const m = document.getElementById('modal-cita');
        document.getElementById('modal-titulo').textContent = 'Nueva cita';
        document.getElementById('bloque-estado').classList.add('d-none');
        document.getElementById('detalle-cita').classList.add('d-none');
        document.getElementById('btn-guardar').textContent = 'Guardar';
        document.getElementById('form-cita').reset();
        const [dia, hora] = [fecha.slice(0, 10), fecha.length > 10 ? fecha.slice(11, 16) : '09:00'];
        document.getElementById('inicio').value = `${dia}T${hora}`;
        document.getElementById('fin').value = `${dia}T${hora.replace(/^\d\d:\d\d/, (h) => (String(parseInt(h.slice(0, 2)) + 1).padStart(2, '0')) + ':00')}`;
        new bootstrap.Modal(m).show();
    };

    const abrirDetalle = (cita) => {
        const m = document.getElementById('modal-cita');
        document.getElementById('modal-titulo').textContent = `Cita #${cita.id}`;
        document.getElementById('bloque-estado').classList.remove('d-none');
        document.getElementById('detalle-cita').classList.remove('d-none');
        document.getElementById('detalle-cita').innerHTML = `
            <p class="mb-1"><strong>Paciente:</strong> ${cita.paciente.nombre} ${cita.paciente.apellido}</p>
            <p class="mb-1"><strong>Doctor:</strong> ${cita.doctor.nombre}</p>
            <p class="mb-1"><strong>Motivo:</strong> ${cita.motivo}</p>`;
        document.getElementById('btn-guardar').textContent = 'Actualizar';
        document.getElementById('form-cita').reset();
        document.getElementById('cita_id').value = cita.id;
        document.getElementById('paciente_id').value = cita.paciente_id;
        document.getElementById('doctor_id').value = cita.doctor_id;
        document.getElementById('inicio').value = cita.inicio.slice(0, 16);
        document.getElementById('fin').value = cita.fin.slice(0, 16);
        document.getElementById('motivo').value = cita.motivo;
        document.getElementById('estado').value = cita.estado;
        new bootstrap.Modal(m).show();
    };

    document.getElementById('filtro-doctor').addEventListener('change', () => calendario.refetchEvents());

    document.getElementById('form-cita').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('cita_id').value;
        const datos = {
            paciente_id: document.getElementById('paciente_id').value,
            doctor_id: document.getElementById('doctor_id').value,
            inicio: document.getElementById('inicio').value,
            fin: document.getElementById('fin').value,
            motivo: document.getElementById('motivo').value,
        };
        const estado = document.getElementById('estado').value;
        try {
            const url = id ? `/api/citas/${id}` : '/api/citas';
            const metodo = id ? 'PUT' : 'POST';
            const res = await fetch(url, {
                method: metodo,
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(datos),
            });
            const json = await res.json();
            if (!res.ok && res.status !== 409) throw new Error(json.message || 'Error al guardar');
            if (id && estado !== json.data.estado) {
                await fetch(`/api/citas/${id}/estado`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                    body: JSON.stringify({ estado }),
                });
            }
            bootstrap.Modal.getInstance(document.getElementById('modal-cita')).hide();
            calendario.refetchEvents();
            if (res.status === 409) mostrarError(json.message);
        } catch (err) {
            mostrarError(err.message);
        }
    });

    poblarDoctores();
    poblarPacientes();
});
</script>
@endpush
