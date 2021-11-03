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

                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" name="name" value="{{ old('name') }}">

                </div>

                <div class="form-group">
                    <label for="email">Correo:</label>
                    <input type="text" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" value="{{ old('password') }}">
                </div>

                <div class="form-group">
                    <label for="bio">Biografía</label>
                    <textarea type="text" name="bio" class="form-control">{{ old('bio') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Twitter</label>
                    <input type="text" name="twitter" class="form-control" value="{{ old('twitter') }}" placeholder="Url de tu usuario de twitter">
                </div>

                <div class="form-group">
                    <button type="submit">Crear Usuario</button>
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

