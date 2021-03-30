<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    <link rel="icon" href="{{ URL::asset('/images/pencil-16-83329.png') }}" type="image/x-icon"/>
</head>
<body>
    <div id="app">
        {{-- @include('partials.nav') --}}
        <navbar
            guest="{{ Auth::check() ? false : true }}"
            auth-name="{{ Auth::check() ? Auth::user()->name : '' }}"
            auth-id="{{ Auth::check() ? Auth::user()->id : '' }}"
            auth-avatar="{{ Auth::check() ? Auth::user()->getUserAvatar() : '' }}"
        ></navbar>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
