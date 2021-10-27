@extends('layout')

@section('title', 'Creación de un usuario')

@section('content')

    <h1>Crear un Usuario</h1>

    <form action="{{ route('users.store') }}" method="POST">
        {{ csrf_field() }}

        <button type="submit">Crear Usuario</button>
    </form>

    <p>
        <a href="{{ route('users.index') }}">Regresar al indice</a>
    </p>

@endsection

