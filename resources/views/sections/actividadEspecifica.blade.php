@extends('layouts.app')

@section('title', $submenu_nombre)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <div class="bg-gradient-warning rounded-circle p-3 d-inline-block mb-3 shadow">
            <i class="bi bi-star-fill text-white" style="font-size: 2rem;"></i>
        </div>
        <h2 class="fw-bold text-warning mb-2">
            {{ ucwords(str_replace('_', ' ', $submenu_nombre)) }}
        </h2>
        <div class="mt-2">
            <span class="badge bg-warning text-white px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Actividad Específica
            </span>
        </div>
    </div>

    <hr class="my-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger rounded-3 shadow-sm mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('guardar.actividad.especifica') }}">
        @csrf
        <input type="hidden" name="id_sub_menu" value="{{ $id_sub_menu }}">

        @foreach($actividades as $actividad)
        <!-- Tarjeta principal -->
        <div class="card border-0 shadow rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-gradient-warning text-white border-0 py-3 px-4">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ $actividad->actividades }}
                </h4>
            </div>
            <div class="card-body p-4">
                <!-- Campos ocultos -->
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_act_central]" value="{{ $actividad->id_act_central ?? '' }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_localidad]" value="{{ $actividad->id_localidad ?? '' }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_ref_unica]" value="{{ $actividad->id_ref_unica ?? '' }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_usuario]" value="{{ session('id_usuario') }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_actividades]" value="{{ $actividad->id_actividades }}">

                <!-- ================= SECCIÓN 0: NOMBRE Y DESCRIPCIÓN DE LA ACTIVIDAD (SIEMPRE ACTIVA) ================= -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-secondary text-white border-0 py-2 px-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle-fill me-2"></i> Información de la Actividad
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-tag me-1 text-secondary"></i> Nombre de la Actividad
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-pencil-square text-secondary"></i>
                                    </span>
                                    <input type="text"
                                        name="actividades[{{ $actividad->id_actividades }}][nombre_actividad]"
                                        class="form-control"
                                        placeholder="Ej: Jornada de Sensibilización"
                                        value="{{ old('actividades.' . $actividad->id_actividades . '.nombre_actividad', $actividad->actividades) }}"
                                        required>
                                </div>
                                <small class="text-muted mt-1 d-block">Nombre específico de la actividad realizada</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-file-text me-1 text-secondary"></i> Descripción de la Actividad
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-chat-text text-secondary"></i>
                                    </span>
                                    <textarea name="actividades[{{ $actividad->id_actividades }}][descripcion_actividad]"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Describa detalladamente la actividad realizada..."
                                        required>{{ old('actividades.' . $actividad->id_actividades . '.descripcion_actividad') }}</textarea>
                                </div>
                                <small class="text-muted mt-1 d-block">Describa en qué consistió la actividad, objetivos y resultados</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= SECCIÓN 1: UBICACIÓN GEOGRÁFICA ================= -->
<!-- ================= SECCIÓN 1: UBICACIÓN GEOGRÁFICA ================= -->
<div class="card border-0 shadow-sm rounded-3 mb-3" id="ubicacionCard">
    <div class="card-header bg-info text-white border-0 py-2 px-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-geo-alt-fill me-2"></i> Ubicación Geográfica
        </h5>
        <button type="button" class="btn btn-sm btn-light toggle-btn" onclick="toggleSeccion('ubicacionSection', this)">
            <i class="bi bi-pencil-square me-1"></i> Activar
        </button>
    </div>
    <div class="card-body p-3 seccion-contenido" id="ubicacionSection" style="pointer-events: none; opacity: 0.6;">
        <!-- Estado / Región -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold mb-1">
                    <i class="bi bi-map me-1 text-info"></i> Estado / Región
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-pin-map-fill text-info"></i>
                    </span>
                    <select class="form-select select-estado" data-actividad="{{ $actividad->id_actividades }}">
                        <option value="">-- Seleccione un Estado --</option>
                        @foreach($estados as $estado)
                        <option value="{{ $estado->id_localidad }}">{{ $estado->localidad }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Municipio -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold mb-1">
                    <i class="bi bi-building me-1 text-info"></i> Municipio
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-building-fill text-info"></i>
                    </span>
                    <select class="form-select select-municipio" data-actividad="{{ $actividad->id_actividades }}" disabled>
                        <option value="">-- Primero seleccione un Estado --</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Parroquia -->
        <div class="row mb-2">
            <div class="col-md-12">
                <label class="form-label fw-semibold mb-1">
                    <i class="bi bi-pin-map me-1 text-info"></i> Parroquia
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-geo-alt-fill text-info"></i>
                    </span>
                    <select name="actividades[{{ $actividad->id_actividades }}][id_localidad]" 
                        class="form-select select-parroquia" 
                        data-actividad="{{ $actividad->id_actividades }}" disabled>
                        <option value="">-- Primero seleccione un Municipio --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

                <!-- ================= SECCIÓN 2: PARTICIPANTES ================= -->
                <div class="card border-0 shadow-sm rounded-3 mb-3" id="participantesCard">
                    <div class="card-header bg-success text-white border-0 py-2 px-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-people-fill me-2"></i> Información de Participantes
                        </h5>
                        <button type="button" class="btn btn-sm btn-light toggle-btn" onclick="toggleSeccion('participantesSection', this)">
                            <i class="bi bi-pencil-square me-1"></i> Activar
                        </button>
                    </div>
                    <div class="card-body p-3 seccion-contenido" id="participantesSection" style="pointer-events: none; opacity: 0.6;">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-person-raised-hand me-1 text-success"></i> Total Participantes Atendidos
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-people-fill text-success"></i>
                                    </span>
                                    <input type="number"
                                        name="actividades[{{ $actividad->id_actividades }}][participante_atendido]"
                                        class="form-control"
                                        placeholder="0"
                                        min="0"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-gender-male me-1 text-primary"></i> Cantidad Hombres
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-gender-male text-primary"></i>
                                    </span>
                                    <input type="number"
                                        name="actividades[{{ $actividad->id_actividades }}][cantidad_masculino]"
                                        class="form-control"
                                        placeholder="0"
                                        min="0"
                                        value="0"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-gender-female me-1 text-danger"></i> Cantidad Mujeres
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-gender-female text-danger"></i>
                                    </span>
                                    <input type="number"
                                        name="actividades[{{ $actividad->id_actividades }}][cantidad_femenino]"
                                        class="form-control"
                                        placeholder="0"
                                        min="0"
                                        value="0"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= SECCIÓN 3: FECHAS Y DURACIÓN ================= -->
                <div class="card border-0 shadow-sm rounded-3" id="fechasCard">
                    <div class="card-header bg-primary text-white border-0 py-2 px-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-event-fill me-2"></i> Fechas y Duración
                        </h5>
                        <button type="button" class="btn btn-sm btn-light toggle-btn" onclick="toggleSeccion('fechasSection', this)">
                            <i class="bi bi-pencil-square me-1"></i> Activar
                        </button>
                    </div>
                    <div class="card-body p-3 seccion-contenido" id="fechasSection" style="pointer-events: none; opacity: 0.6;">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-clock me-1 text-primary"></i> Duración (Horas)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-hourglass-split text-primary"></i>
                                    </span>
                                    <input type="number"
                                        name="actividades[{{ $actividad->id_actividades }}][duracion_hora]"
                                        class="form-control"
                                        placeholder="Ej: 2.5"
                                        step="0.5"
                                        min="0"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-calendar-check me-1 text-primary"></i> Fecha de Registro
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar3 text-primary"></i>
                                    </span>
                                    <input type="date"
                                        name="actividades[{{ $actividad->id_actividades }}][fecha_registro]"
                                        class="form-control"
                                        value="{{ date('Y-m-d') }}"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-calendar-plus me-1 text-primary"></i> Fecha de Inicio
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar-week text-primary"></i>
                                    </span>
                                    <input type="date"
                                        name="actividades[{{ $actividad->id_actividades }}][fecha_inicio]"
                                        class="form-control"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="bi bi-calendar-x me-1 text-primary"></i> Fecha de Fin
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar-range text-primary"></i>
                                    </span>
                                    <input type="date"
                                        name="actividades[{{ $actividad->id_actividades }}][fecha_fin]"
                                        class="form-control"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Botones -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-warning btn-lg px-4 py-2 me-3 rounded-pill shadow">
                <i class="bi bi-save me-2"></i> Guardar Registro
            </button>
            <a href="{{ url('/home') }}" class="btn btn-outline-secondary btn-lg px-4 py-2 rounded-pill shadow">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </form>
</div>

<script>
    // Función para alternar sección (Activar/Desactivar)
    function toggleSeccion(sectionId, boton) {
        const section = document.getElementById(sectionId);
        const card = section.closest('.card');
        const isActivated = section.style.pointerEvents === 'auto';
        
        if (isActivated) {
            // Desactivar la sección
            section.style.pointerEvents = 'none';
            section.style.opacity = '0.6';
            
            // Cambiar el botón
            boton.innerHTML = '<i class="bi bi-pencil-square me-1"></i> Activar';
            boton.classList.remove('btn-success');
            boton.classList.add('btn-light');
            
            // Desactivar todos los inputs y selects dentro de la sección
            section.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = true;
            });
            
            // Quitar el borde verde
            if (card) {
                card.style.border = 'none';
                card.style.boxShadow = '0 0.0625rem 0.25rem rgba(0, 0, 0, 0.05)';
            }
        } else {
            // Activar la sección
            section.style.pointerEvents = 'auto';
            section.style.opacity = '1';
            
            // Cambiar el botón
            boton.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Desactivar';
            boton.classList.remove('btn-light');
            boton.classList.add('btn-warning');
            
            // Activar todos los inputs y selects dentro de la sección
            section.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = false;
            });
            
            // Agregar un borde verde a la tarjeta
            if (card) {
                card.style.border = '2px solid #28a745';
                card.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            }
        }
    }

    // Función para cargar localidades
    function cargarLocalidades(idPadre, selectElement, textoDefault) {
        if (!idPadre) {
            selectElement.innerHTML = '<option value="">-- Primero seleccione ' + textoDefault + ' --</option>';
            selectElement.disabled = true;
            return;
        }

        fetch(`/api/localidades/${idPadre}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    selectElement.innerHTML = '<option value="">-- Seleccione ' + textoDefault + ' --</option>';
                    data.forEach(localidad => {
                        selectElement.innerHTML += `<option value="${localidad.id_localidad}">${localidad.localidad}</option>`;
                    });
                    selectElement.disabled = false;
                } else {
                    selectElement.innerHTML = '<option value="">-- No hay ' + textoDefault + ' disponibles --</option>';
                    selectElement.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                selectElement.innerHTML = '<option value="">-- Error al cargar datos --</option>';
                selectElement.disabled = true;
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Estados
        document.querySelectorAll('.select-estado').forEach(select => {
            const actividadId = select.dataset.actividad;
            const selectMunicipio = document.querySelector(`.select-municipio[data-actividad="${actividadId}"]`);
            const selectParroquia = document.querySelector(`.select-parroquia[data-actividad="${actividadId}"]`);
            
            select.addEventListener('change', function() {
                cargarLocalidades(this.value, selectMunicipio, 'Municipio');
                if (selectParroquia) {
                    selectParroquia.innerHTML = '<option value="">-- Primero seleccione Municipio --</option>';
                    selectParroquia.disabled = true;
                }
            });
        });

        // Municipios
        document.querySelectorAll('.select-municipio').forEach(select => {
            const actividadId = select.dataset.actividad;
            const selectParroquia = document.querySelector(`.select-parroquia[data-actividad="${actividadId}"]`);
            
            select.addEventListener('change', function() {
                cargarLocalidades(this.value, selectParroquia, 'Parroquia');
            });
        });
    });
</script>

@endsection