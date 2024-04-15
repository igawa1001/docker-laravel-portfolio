<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Application-specific CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
