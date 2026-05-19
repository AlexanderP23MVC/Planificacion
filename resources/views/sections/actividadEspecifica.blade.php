@extends('layouts.app')

@section('title', $submenu_nombre)

@section('content')
<div class="card-custom">
    <div class="text-center mb-4">
        <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
            <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
        </div>
        <h2 class="fw-bold text-warning">
            {{ ucwords(str_replace('_', ' ', $submenu_nombre)) }}
        </h2>
        <div class="mt-2">
            <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-2 rounded-pill">
                <i class="bi bi-pencil-square"></i> Actividad Específica
            </span>
        </div>
    </div>

    <hr>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('guardar.actividad.especifica') }}">
        @csrf
        <input type="hidden" name="id_sub_menu" value="{{ $id_sub_menu }}">

        @foreach($actividades as $actividad)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ $actividad->actividades }}
                </h4>
            </div>
            <div class="card-body">
                <!-- Campos ocultos que vienen del join -->
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_act_central]"
                    value="{{ $actividad->id_act_central ?? '' }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_localidad]"
                    value="{{ $actividad->id_localidad ?? '' }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_ref_unica]"
                    value="{{ $actividad->id_ref_unica ?? '' }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_usuario]"
                    value="{{ session('id_usuario') }}">
                <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id_actividades]"
                    value="{{ $actividad->id_actividades }}">

                <div class="row">

                    <!-- participante_atendido -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-people"></i> Participantes Atendidos
                        </label>
                        <input type="number" name="actividades[{{ $actividad->id_actividades }}][participante_atendido]"
                            class="form-control" placeholder="Número de participantes" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <!-- cantidad_masculino -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-gender-male"></i> Cantidad Masculino
                        </label>
                        <input type="number" name="actividades[{{ $actividad->id_actividades }}][cantidad_masculino]"
                            class="form-control" placeholder="Hombres" min="0" value="0">
                    </div>

                    <!-- cantidad_femenino -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-gender-female"></i> Cantidad Femenino
                        </label>
                        <input type="number" name="actividades[{{ $actividad->id_actividades }}][cantidad_femenino]"
                            class="form-control" placeholder="Mujeres" min="0" value="0">
                    </div>

                    <!-- duracion_hora -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-clock"></i> Duración (Horas)
                        </label>
                        <input type="number" name="actividades[{{ $actividad->id_actividades }}][duracion_hora]"
                            class="form-control" placeholder="Horas" step="0.5" min="0" required>
                    </div>

                    <!-- fecha_registro -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar"></i> Fecha Registro
                        </label>
                        <input type="date" name="actividades[{{ $actividad->id_actividades }}][fecha_registro]"
                            class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- fecha_inicio -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-event"></i> Fecha Inicio
                        </label>
                        <input type="date" name="actividades[{{ $actividad->id_actividades }}][fecha_inicio]"
                            class="form-control" required>
                    </div>

                    <!-- fecha_fin -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-event"></i> Fecha Fin
                        </label>
                        <input type="date" name="actividades[{{ $actividad->id_actividades }}][fecha_fin]"
                            class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-warning btn-lg px-5">
                <i class="bi bi-save"></i> Guardar Registro
            </button>
            <a href="{{ url('/home') }}" class="btn btn-secondary btn-lg px-5">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </form>
</div>

<script>
    // Validar que fecha_fin no sea menor que fecha_inicio
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', function () {
            const fechaInicio = this.form.querySelector('[name*="fecha_inicio"]');
            const fechaFin = this.form.querySelector('[name*="fecha_fin"]');

            if (fechaInicio && fechaFin && fechaFin.value < fechaInicio.value) {
                alert('La fecha fin no puede ser menor a la fecha inicio');
                fechaFin.value = '';
            }
        });
    });
</script>
@endsection