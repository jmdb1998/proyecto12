@extends('layout')

@section('title', 'Edición de un usuario')

@section('content')
    <h1>Editar Usuario</h1>

    @if($errors->any())

        <div class="alert alert-danger">
            <h6>Por favor corrige los sigientes errores</h6>

        <!--            <ul>
                @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
                @endforeach
                </ul>-->

        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <label for="name">Nombre:</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        @if($errors->has('name'))
            <p>{{ $errors->first('name') }}</p>
        @endif
        <br>
        <label for="email">Correo:</label>
        <input type="text" name="email" value="{{ old('email', $user->email) }}">
        @if($errors->has('email'))
            <p>{{ $errors->first('email') }}</p>
        @endif
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" value="{{ old('password') }}">
        @if($errors->has('password'))
            <p>{{ $errors->first('password') }}</p>
        @endif


        <button type="submit">Crear Usuario</button>
    </form>

    <p>
        <a href="{{ route('users.index') }}">Regresar al indice</a>
    </p>
@endsection
