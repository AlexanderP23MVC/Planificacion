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