<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>
<body style="font-family: Arial; text-align:center; padding-top:50px;">

    <h1>@yield('code')</h1>

    <div>
        @yield('icon')
    </div>

    <h2>@yield('headline')</h2>

    <p>
        @yield('message')
    </p>

</body>
</html>