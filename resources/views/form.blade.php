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
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $errors)
                    <li>{{$errors}}</li>
                @endforeach
            </ul>
        </div>
    @endif
<form action="{{route('submit-form')}}" method="post">
    @csrf
    <label for="username">Username</label>
    <br>
    <input type="text" name="username" >
    <label for="username">Password</label>
    <input type="password" name="password">
    <input type="submit" value="login">
</form>
</body>
</html>
