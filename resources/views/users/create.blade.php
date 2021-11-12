@extends('layout')

@section('title', 'Creación de un usuario')

@section('content')
    @card
        @slot('header','Crear un nuevo usuario')

            @include('shared._errors')

            <form action="{{ route('users.store') }}" method="POST">

                @include('users._fields')

                <div class="form-group mt-4">
                    <button type="submit">Crear Usuario</button>
                    <a href="{{ route('users.index') }}" class="btn btn-link">Regresar al listado</a>
                </div>
            </form>

    @endcard
@endsection

