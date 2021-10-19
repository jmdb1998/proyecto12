<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <h1>{{$title}}</h1>

{{--    @if(!empty($users))--}}

{{--<ul>--}}
{{--    @foreach ($users as $user)--}}
{{--    <li>{{$user}}</li>--}}
{{--    @endforeach--}}
{{--</ul>--}}

{{--    @else--}}
{{--        <p>No hay usuarios</p>--}}
{{--    @endif--}}

    @empty($users)
        <p>No hay usuarios</p>
    @else
        <ul>
            @foreach ($users as $user)
                <li>{{$user}}</li>
            @endforeach
        </ul>
    @endempty


{{--    <ul>--}}
{{--        @forelse($users as $user)--}}
{{--            <li>{{$user}}</li>--}}
{{--        @empty--}}
{{--            <p>No hay usuarios</p>--}}
{{--        @endforelse--}}
{{--    </ul>--}}



</body>
</html>