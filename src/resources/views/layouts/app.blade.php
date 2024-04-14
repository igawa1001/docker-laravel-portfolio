<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
</head>
<body>
    <header>
{{--        @include('layouts.header')--}}
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
{{--        @include('layouts.footer')--}}
    </footer>

</body>
</html>
