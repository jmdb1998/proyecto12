@extends('layout')

@section('title', 'Listado de usuario')

@section('content')

        <h1>{{ $title }}</h1>
        <p>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Nuevo Usuaro</a>
        </p>
        @if($users->count())
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>

                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-link"><span class="oi oi-eye"></span></a> |
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-link"><span class="oi oi-pencil"></span></a> |
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-link"><span class="oi oi-trash"></span></button>
                        </form>
                        </td>
                    </tr>
                    <!--                    <a href="{{ url('usuarios/' . $user->id) }}">Ver detalles</a></li>-->
                    <!--                 <a href="{{ action('UserController@show', ['id' => $user->id]) }}">Ver detalles</a></li> otra forma de hacerlo-->
                @endforeach
                </tbody>
            </table>



            <ul>

            </ul>

        @else
            <p>No hay usuarios</p>
        @endif

@endsection

@section('sidebar')
    Barra Lateral
@endsection

{{--    @if(!empty($users))--}}

{{--<ul>--}}
{{--    @foreach ($users as $user)--}}
{{--    <li>{{$user}}</li>--}}
{{--    @endforeach--}}
{{--</ul>--}}

{{--    @else--}}
{{--        <p>No hay usuarios</p>--}}
{{--    @endif--}}


{{--    <ul>--}}
{{--        @forelse($users as $user)--}}
{{--            <li>{{$user}}</li>--}}
{{--        @empty--}}
{{--            <p>No hay usuarios</p>--}}
{{--        @endforelse--}}
{{--    </ul>--}}
