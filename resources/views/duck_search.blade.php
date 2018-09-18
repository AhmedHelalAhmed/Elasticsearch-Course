<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Dan's disco Duck Page</title>
</head>
<body>
<h1>Dan's disco Duck Page</h1>
<h2>Feel free to search for your favorite duck!</h2>
{{ Form::open(['method'=>'get','route'=>'duck_search']) }}
{{ Form::text('query',$query) }}
{{ Form::submit('Search All Ducks') }}
{{ Form::close() }}
@if(isset($results))
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Color</th>
            <th>Gender</th>
            <th>hometown</th>
            <th>funky?</th>
            <th>About</th>
            <th>Registered Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result->name }}</td>
                <td>{{ $result->age }}</td>
                <td>{{ $result->color }}</td>
                <td>{{ $result->gender }}</td>
                <td>{{ $result->hometown }}</td>
                <td>{{ $result->funkyDuck }}</td>
                <td>{{ $result->about }}</td>
                <td>{{ $result->registered }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
</body>
</html>