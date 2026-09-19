# Evidencia - Control de Citas Médicas

## Laravel

```
Laravel Framework 12.69.2 (Composer: laravel/laravel, PHP ^8.2)
```

## Docker Compose

```
docker compose ps
```

| Nombre | Imagen | Estado |
|---|---|---|
| control-citas-mysql | mysql:8.4 | Up (healthy) |

- Puerto: host `3307` -> contenedor `3306`
- Volumen persistente: `mysql_data`

## MySQL

- Motor: MySQL Community Server 8.4.11 (verificado con `SELECT VERSION()`)
- Ejecución exclusiva en contenedor: `control-citas-mysql` (Docker Desktop)
- Base: `control_citas` — Usuario: `citas_user`
- Conexión cómo clientes se hace como: `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3307`
- Extensiones PHP activas: `PDO`, `pdo_mysql`

## Migraciones

```
php artisan migrate:status
```

| Migración | Estado |
|---|---|
| 0001_01_01_000000_create_users_table | Ran |
| 0001_01_01_000001_create_cache_table | Ran |
| 0001_01_01_000002_create_jobs_table | Ran |
| 2026_09_19_000001_create_pacientes_table | Ran |
| 2026_09_19_000002_create_doctores_table | Ran |
| 2026_09_19_000003_create_citas_table | Ran |

```
php artisan migrate:fresh --seed  (via migrate:fresh --seed)
```

Migraciones: 6 ejecutadas, seeding de pacientes/doctores/citas correcto.

## Tablas verificadas en MySQL

Ejecutado en el contenedor:

```
docker exec control-citas-mysql mysql -ucitas_user -pcitas_password control_citas \
  -e "SHOW TABLES; SELECT * FROM pacientes; SELECT * FROM doctores; SELECT * FROM citas;"
```

Resultado:

- Tablas: `pacientes`, `doctores`, `citas` (además de sistema: users, cache, jobs, migrations)
- `pacientes` (3): Ana López, Carlos Pérez, María García
- `doctores` (3): Dr. Juan Morales (Medicina General), Dra. Laura Castillo (Pediatría), Dr. Pedro Gómez (Cardiología)
- `citas` (1): paciente 1 + doctor 1, mañana 09:00–10:00, motivo "Consulta general", estado `pendiente`

## API REST

```
php artisan route:list --path=api
```

| Método | URI |
|---|---|
| GET | /api/citas (filtros: doctor_id, paciente_id, desde, hasta) |
| POST | /api/citas |
| GET | /api/citas/{id} |
| PUT | /api/citas/{id} |
| PATCH | /api/citas/{id}/estado |
| GET | /api/doctores |
| GET | /api/pacientes |

- Códigos: 200, 201, 400, 404, 409 (conflicto de horario)
- Mensaje de conflicto 409: `El doctor ya posee una cita activa en ese horario.`

## Pruebas API contra MySQL

```
php artisan test
```

- ConflictoCitaTest: 5 passed (15 assertions)
  - doble reserva devuelve 409
  - cita cancelada no bloquea
  - reprogramación conflictiva devuelve 409
  - patch de estado persiste en MySQL
- CitaApiTest: 2 passed (9 assertions)

## FullCalendar

- Ruta: `/citas`
- Librería: FullCalendar 6.1 (CDN), Bootstrap 5
- Vistas: `dayGridMonth`, `timeGridWeek`, `timeGridDay`
- Colores por estado: pendiente=naranja, confirmada=azul, cancelada=gris, atendida=verde
- Crear cita: clic en fecha → modal
- Detalle: clic en evento
- Reprogramación: drag & drop (eventDrop → PUT /api/citas/{id}) con revert si ocurre 409
- Filtro por doctor

## Git

```
git log --graph --all --decorate --oneline
```

- Rama `main`: PR final de integración
- Rama `develop`: integración de las 4 features
- Ramas feature: feature/docker-mysql-schema, feature/api-rest-citas, feature/validacion-conflictos-estados, feature/fullcalendar-ui
- Flujo: feature → PR → develop → PR → main

## Docker final

```
docker compose ps
```

`control-citas-mysql` — `mysql:8.4` — healthy. MySQL corre exclusivamente dentro del contenedor.
