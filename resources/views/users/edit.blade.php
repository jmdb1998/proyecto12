@extends('layout')

@section('title', 'Edición de un usuario')

@section('content')
    <h1>Editar Usuario</h1>

    @include('shared._errors')

    <form action="{{ route('users.update', $user) }}" method="POST">
        {{ method_field('PUT') }}

       @include('users._fields')

        <div class="form-group mt-4">
            <button type="submit">Actualizar usuario</button>
            <a href="{{ route('users.index') }}" class="btn btn-link">Regresar al listado</a>
        </div>
    </form>
@endsection
