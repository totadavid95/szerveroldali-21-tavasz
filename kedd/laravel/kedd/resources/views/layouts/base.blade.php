<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if (View::hasSection('title'))
            @yield('title')
        @else
            Laravel Alkalmazás
        @endif
    </title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fa.css') }}">
</head>
<body>
    <main>
        @yield('main-content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
