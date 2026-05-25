@extends('layouts.app')

@section('title', 'Aprobación de Actividades')

@section('content')
<div class="card-custom">
    <!-- Header con gradiente -->
    <div class="text-center mb-4 p-4 rounded-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <i class="bi bi-check-circle-fill text-white" style="font-size: 3rem;"></i>
        <h1 class="display-5 fw-bold text-white mt-2">Aprobación de Actividades</h1>
        <p class="text-white-50">Gestión de actividades pendientes por departamento</p>
        
        <div class="alert alert-light mt-3 shadow-sm" style="background: rgba(255,255,255,0.9);">
            <i class="bi bi-info-circle text-primary"></i> 
            <strong>{{ session('usuario') }}</strong> | 
            Departamento: <strong>{{ session('departamento') }}</strong> | 
            Cargo: <strong>{{ session('cargo') }}</strong>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($actividades && count($actividades) > 0)
    <div class="table-responsive" style="background: white; border-radius: 1rem; padding: 0.5rem; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);">
        <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <tr>
                    <th class="text-white py-3 text-center" style="border-top-left-radius: 1rem; width: 5%;">#</th>
                    <th class="text-white py-3" style="width: 20%;">Actividad</th>
                    <th class="text-white py-3 text-center" style="width: 10%;">Usuario</th>
                    <th class="text-white py-3 text-center" style="width: 10%;">Departamento</th>
                    <th class="text-white py-3 text-center" style="width: 10%;">Fecha Registro</th>
                    <th class="text-white py-3 text-center" style="width: 10%;">Participantes</th>
                    <th class="text-white py-3 text-center" style="width: 8%;">Duración</th>
                    <th class="text-white py-3 text-center" style="width: 10%;">Estatus</th>
                    <th class="text-white py-3 text-center" style="border-top-right-radius: 1rem; width: 12%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($actividades as $index => $actividad)
                <tr style="background: white; border-bottom: 1px solid #f0f0f0;">
                    <td class="text-center fw-bold text-muted">{{ ($actividades->currentPage() - 1) * $actividades->perPage() + $index + 1 }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <strong class="text-primary">{{ $actividad->nombre_actividad ?? 'Sin nombre' }}</strong>
                            <small class="text-muted">{{ Str::limit($actividad->descripcion_actividad ?? 'Sin descripción', 50) }}</small>
                        </div>
                    </td>
                    <td class="text-center">{{ $actividad->usuario ?? 'N/A' }}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                            <i class="bi bi-building me-1"></i> {{ $actividad->departamento ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($actividad->fecha_registro)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            <i class="bi bi-people me-1"></i> {{ $actividad->participante_atendido ?? 0 }}
                        </span>
                        <div class="mt-1">
                            <small class="text-muted">
                                👨 {{ $actividad->cantidad_masculino ?? 0 }} | 👩 {{ $actividad->cantidad_femenino ?? 0 }}
                            </small>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info rounded-pill px-3 py-2">
                            <i class="bi bi-clock me-1"></i> {{ $actividad->duracion_hora ?? 0 }} hrs
                        </span>
                    </td>
                    <td class="text-center">
                        @php
                            $estatusActual = $actividad->estatus ?? 'Pendiente';
                            $esPendiente = ($estatusActual == 'PENDIENTE' || $actividad->id_estatus == 1);
                            $esAprobado = ($estatusActual == 'APROBADO' || $actividad->id_estatus == 2);
                            $esCertificado = ($estatusActual == 'CERTIFICADO' || $actividad->id_estatus == 5);
                            
                            if($esPendiente) {
                                $badgeClass = 'bg-warning';
                                $badgeIcon = 'bi-clock-history';
                                $estatusTexto = 'Pendiente';
                            } elseif($esAprobado) {
                                $badgeClass = 'bg-success';
                                $badgeIcon = 'bi-check-circle-fill';
                                $estatusTexto = 'Aprobado';
                            } elseif($esCertificado) {
                                $badgeClass = 'bg-info';
                                $badgeIcon = 'bi-award-fill';
                                $estatusTexto = 'Certificado';
                            } else {
                                $badgeClass = 'bg-danger';
                                $badgeIcon = 'bi-x-circle-fill';
                                $estatusTexto = 'Rechazado';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                            <i class="bi {{ $badgeIcon }} me-1"></i>
                            {{ $estatusTexto }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php
                            $esPlanificacion = (strtolower(session('departamento', '')) == 'planificacion');
                        @endphp
                        
                        @if($esPendiente && !$esPlanificacion)
                        <div class="d-flex gap-2 justify-content-center">
                            <form method="POST" action="{{ route('aprobar.actividad') }}" onsubmit="return confirm('¿Estás seguro de APROBAR esta actividad?')">
                                @csrf
                                <input type="hidden" name="id_act_central" value="{{ $actividad->id_act_central }}">
                                <input type="hidden" name="id_ref_unica" value="{{ $actividad->id_ref_unica }}">
                                <input type="hidden" name="accion" value="APROBADO">          
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="bi bi-check-lg"></i> Aprobar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('rechazar.actividad') }}" onsubmit="return confirm('¿Estás seguro de RECHAZAR esta actividad?')">
                                @csrf                        
                                <input type="hidden" name="id_act_central" value="{{ $actividad->id_act_central }}">
                                <input type="hidden" name="id_ref_unica" value="{{ $actividad->id_ref_unica }}"> 
                                <input type="hidden" name="accion" value="RECHAZADO">
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                                    <i class="bi bi-x-lg"></i> Rechazar
                                </button>
                            </form>
                        </div>
                        @elseif($esAprobado && $esPlanificacion)
                        <div class="d-flex gap-2 justify-content-center">
                            <form method="POST" action="{{ route('certificar.actividad') }}" onsubmit="return confirm('¿Estás seguro de CERTIFICAR esta actividad?')">
                                @csrf
                                <input type="hidden" name="id_act_central" value="{{ $actividad->id_act_central }}">
                                <input type="hidden" name="id_ref_unica" value="{{ $actividad->id_ref_unica }}">
                                <input type="hidden" name="accion" value="CERTIFICADO">
                                <button type="submit" class="btn btn-info btn-sm rounded-pill px-3">
                                    <i class="bi bi-award-fill"></i> Certificar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('rechazar.actividad') }}" onsubmit="return confirm('¿Estás seguro de RECHAZAR esta actividad?')">
                                @csrf                        
                                <input type="hidden" name="id_act_central" value="{{ $actividad->id_act_central }}">
                                <input type="hidden" name="id_ref_unica" value="{{ $actividad->id_ref_unica }}"> 
                                <input type="hidden" name="accion" value="RECHAZADO">
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                                    <i class="bi bi-x-lg"></i> Rechazar
                                </button>
                            </form>
                        </div>
                        @elseif($esAprobado)
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-2">
                            <i class="bi bi-check-circle-fill me-1"></i> En espera de certificación
                        </span>
                        @elseif($esCertificado)
                        <span class="badge bg-info bg-opacity-25 text-info rounded-pill px-3 py-2">
                            <i class="bi bi-award-fill me-1"></i> Certificada
                        </span>
                        @else
                        <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill px-3 py-2">
                            <i class="bi bi-x-circle-fill me-1"></i> Rechazada
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- PAGINACIÓN -->
    <div class="d-flex justify-content-center mt-4">
        {{ $actividades->links('pagination::bootstrap-5') }}
    </div>
    
    <!-- Resumen de actividades -->
    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center py-4">
                    <i class="bi bi-calendar-check text-white" style="font-size: 2rem;"></i>
                    <h2 class="display-4 fw-bold text-white mb-0">{{ $actividades->total() }}</h2>
                    <p class="text-white-50 mb-0">Total Actividades</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-center py-4">
                    <i class="bi bi-check-circle-fill text-white" style="font-size: 2rem;"></i>
                    <h2 class="display-4 fw-bold text-white mb-0">{{ $actividades->where('estatus', 'APROBADO')->count() }}</h2>
                    <p class="text-white-50 mb-0">Actividades Aprobadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-center py-4">
                    <i class="bi bi-clock-history text-white" style="font-size: 2rem;"></i>
                    <h2 class="display-4 fw-bold text-white mb-0">{{ $actividades->where('estatus', 'PENDIENTE')->count() }}</h2>
                    <p class="text-white-50 mb-0">Actividades Pendientes</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">No hay actividades pendientes</h4>
        <p class="text-secondary">No se encontraron actividades para tu departamento.</p>
    </div>
    @endif

    <!-- Botón Volver -->
    <div class="text-center mt-5">
        <a href="{{ url('/home') }}" class="btn rounded-pill px-5 py-3 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 500; transition: all 0.3s ease;">
            <i class="bi bi-arrow-left me-2"></i> Volver al Inicio
        </a>
    </div>
</div>

<style>
    .card-custom {
        border-radius: 1rem;
        overflow: hidden;
        background: transparent;
    }
    
    .table-responsive {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05) !important;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .row .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .row .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .d-flex.gap-2 {
        gap: 0.5rem;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endsection