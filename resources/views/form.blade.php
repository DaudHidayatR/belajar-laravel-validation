<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Form</title>
</head>
<body>
    @if($errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
    @endif
<form action="{{route('submit-form')}}" method="post">
    @csrf
    <label for="username">Username @error('username'){{$message}} @enderror</label>
    <br>
    <input type="text" name="username" value="{{old('username')}}" >
    <label for="password">Password @error('password'){{$message}} @enderror</label>
    <input type="password" name="password" value="{{old('password')}}">
    <input type="submit" value="login">
</form>
</body>
</html>
