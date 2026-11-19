@extends('layouts.app')

@section('title', 'Demasiadas peticiones')

@section('content')
    <div class="text-center mt-5">
        <h1 class="display-4">🚫 Demasiadas peticiones</h1>
        <p class="lead">Has realizado demasiadas solicitudes en poco tiempo.</p>
        <p>Por favor espera unos segundos antes de intentarlo nuevamente.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Volver</a>
    </div>
@endsection
