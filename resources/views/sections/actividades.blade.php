@extends('layouts.app')

@section('title', $submenu_nombre)

@section('content')
<div class="card-custom">
    <div class="text-center mb-4">
        <div class="bg-primary text-white rounded-3 p-3 d-inline-block shadow-sm mb-3">
            <i class="bi bi-pencil-square" style="font-size: 2rem;"></i>
        </div>
        <h2 class="fw-bold">
            <span class="text-primary">{{ ucwords(str_replace('_', ' ', $submenu_nombre)) }}</span>
        </h2>
        <div class="mt-2">
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                Semana {{ date('W') }} - {{ date('Y') }}
            </span>
        </div>
    </div>

    <hr>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('guardar.registros') }}">
        @csrf
        <input type="hidden" name="id_sub_menu" value="{{ $id_sub_menu }}">
        <input type="hidden" name="submenu_nombre" value="{{ $submenu_nombre }}">
        <input type="hidden" name="semana" value="{{ date('W') }}">
        <input type="hidden" name="anio" value="{{ date('Y') }}">

        @php
        // Agrupar actividades por unidad
        $agrupadas = [];
        foreach ($actividades as $actividad) {
        $agrupadas[$actividad->unidad][] = $actividad;
        }
        @endphp

        @foreach($agrupadas as $unidad => $actividadesUnidad)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-grid"></i> {{ $unidad }}
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 70%">Actividad</th>
                                <th style="width: 25%">Cantidad Semanal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($actividadesUnidad as $index => $actividad)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $actividad->actividades }}</strong>
                                    <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][id]"
                                        value="{{ $actividad->id_actividades }}">
                                    <input type="hidden" name="actividades[{{ $actividad->id_actividades }}][nombre]"
                                        value="{{ $actividad->actividades }}">
                                </td>
                                <td>
                                    <input type="number" name="actividades[{{ $actividad->id_actividades }}][cantidad]"
                                        class="form-control text-center cantidad-input"
                                        value="{{ old('actividades.' . $actividad->id_actividades . '.cantidad', 0) }}"
                                        min="0" step="1"
                                        style="font-size: 1.2rem; font-weight: bold; width: 120px; margin: 0 auto;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total Unidad:</td>
                                <td class="text-center fw-bold text-primary total-unidad"
                                    id="total-{{ Str::slug($unidad) }}">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

        <div class="card mb-4 shadow-sm bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">TOTAL GENERAL:</h4>
                            <h2 class="mb-0 text-primary">
                                <span id="totalGeneral" class="badge bg-primary fs-3 p-3">0</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-save"></i> Guardar Registros
            </button>
            <a href="{{ url('/home') }}" class="btn btn-secondary btn-lg px-5">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </form>
</div>

<script>
    function calcularTotales() {
        // Calcular total por unidad
        document.querySelectorAll('.card').forEach(card => {
            let totalUnidad = 0;
            card.querySelectorAll('.cantidad-input').forEach(input => {
                totalUnidad += parseInt(input.value) || 0;
            });
            const totalSpan = card.querySelector('.total-unidad');
            if (totalSpan) totalSpan.innerText = totalUnidad;
        });

        // Calcular total general
        let totalGeneral = 0;
        document.querySelectorAll('.cantidad-input').forEach(input => {
            totalGeneral += parseInt(input.value) || 0;
        });
        document.getElementById('totalGeneral').innerText = totalGeneral;
    }

    document.querySelectorAll('.cantidad-input').forEach(input => {
        input.addEventListener('change', calcularTotales);
        input.addEventListener('keyup', calcularTotales);
    });

    calcularTotales();
</script>
@endsection