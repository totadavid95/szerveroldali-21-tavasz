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
    <header class="mb-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.html">
                <img src="{{ asset('images/bootstrap.svg') }}" width="30" height="30" class="d-inline-block align-top" alt="Logo">
                Blog
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main-nav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.html">Nyitólap</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        @yield('main-content')
    </main>

    <footer>
        <div class="container mb-4">
            <hr>
            <div class="d-flex flex-column align-items-center">
                <div>
                    <span class="small">Alapszintű Blog</span>
                    <span class="mx-1">·</span>
                    <span class="small">Laravel {{ app()->version() }}</span>
                    <span class="mx-1">·</span>
                    <span class="small">PHP {{ phpversion() }}</span>
                </div>

                <div>
                    <span class="small">Szerveroldali webprogramozás 2020-21-2</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
