<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ config('app.name', 'FI-Intranet') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{--<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>--}}
    <script src="{{ asset('js/tinymce.js') }}"></script>
    <script src="{{ asset('js/benutzerfreundlichkeit.js') }}" defer></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/fork-awesome.css') }}">
    <!-- This one is for tinymce; it doesn't help. -->
    <link rel="stylesheet" href="{{ asset('skins/content/default/content.css') }}">
    <style>
        @yield('style')
    </style>
</head>
<body>
<div id="app">
        @if (request()->is('admin*'))
            @include('layouts._admin_navigation')
        @else
            @include('layouts._navigation')
        @endif

        <main class="py-4">
            @include('layouts._flash')

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
