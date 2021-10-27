@extends('layout')

@section('title', 'Listado de usuario')

@section('content')

        <h1>{{ $title }}</h1>
        @if($users->count())
            <ul>
                @foreach ($users as $user)
                    <li>{{ $user->name }}, {{ $user->email }}
                        <a href="{{ route('users.show', $user->id) }}">Ver detalles</a></li>
<!--                    <a href="{{ url('usuarios/' . $user->id) }}">Ver detalles</a></li>-->
<!--                 <a href="{{ action('UserController@show', ['id' => $user->id]) }}">Ver detalles</a></li> otra forma de hacerlo-->
                @endforeach
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
