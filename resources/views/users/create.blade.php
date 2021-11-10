@extends('layout')

@section('title', 'Creación de un usuario')

@section('content')
    <div class="card">
        <div class="card-header h4">
            crear un nuevo usuario
        </div>

        <div class="card-body">
            @include('shared._errors')

            <form action="{{ route('users.store') }}" method="POST">

                @include('users._fields')

                <div class="form-group mt-4">
                    <button type="submit">Crear Usuario</button>
                    <a href="{{ route('users.index') }}" class="btn btn-link">Regresar al listado</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card-footer">
        <p>
            <a href="{{ route('users.index') }}">Regresar al indice</a>
        </p>
    </div>


@endsection

