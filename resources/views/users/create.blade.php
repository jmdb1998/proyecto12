@extends('layout')

@section('title', 'Creación de un usuario')

@section('content')
    <div class="card">
        <div class="card-header h4">
            crear un nuevo usuario
        </div>

        <div class="card-body">
            @if($errors->any())

            <div class="alert alert-danger">
                <h6>Por favor corrige los sigientes errores</h6>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
            @endif


            <form action="{{ route('users.store') }}" method="POST">
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input type="text" name="name" value="{{ old('name') }}">
                {{--@if($errors->has('name'))
                    <p>{{ $errors->first('name') }}</p>
                @endif--}}
                <br>
                <label for="email">Correo:</label>
                <input type="text" name="email" value="{{ old('email') }}">
                {{--@if($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif--}}
                <br>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" value="{{ old('password') }}">
                {{--@if($errors->has('password'))
                    <p>{{ $errors->first('password') }}</p>
                @endif--}}
                <br/>
                <button type="submit">Crear Usuario</button>
            </form>
        </div>
    </div>

    <div class="card-footer">
        <p>
            <a href="{{ route('users.index') }}">Regresar al indice</a>
        </p>
    </div>


@endsection

