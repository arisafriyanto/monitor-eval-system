<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MonitorEval System' }}</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles Custom -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div>
        @include('includes._alert')
        <main>
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
