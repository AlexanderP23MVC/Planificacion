@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="card-custom text-center">
    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
    <h1 class="display-5 fw-bold text-primary mt-3">Bienvenido al Home</h1>
    <p class="lead text-secondary">Has llegado al panel principal</p>
    
    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle"></i> Has iniciado sesión como: 
        <strong>{{ session('usuario') }}</strong> | 
        Departamento: <strong>{{ session('departamento') }}</strong> | 
        Cargo: <strong>{{ session('cargo') }}</strong>
    </div>
</div>
@endsection