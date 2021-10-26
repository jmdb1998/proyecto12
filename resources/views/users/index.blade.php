@extends('layout')

@section('title', 'Listado de usuario')

@section('content')

        <h1>{{ $title }}</h1>
        @empty($users)
            <p>No hay usuarios</p>
        @else
            <ul>
                @foreach ($users as $user)
                    <li>{{ $user->name }}</li>
                @endforeach
            </ul>
        @endempty

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
