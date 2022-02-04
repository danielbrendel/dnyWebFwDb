<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="author" content="{{ env('APP_AUTHOR') }}">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="tags" content="{{ env('APP_TAGS') }}">

        <title>{{ env('APP_NAME') }}@yield('title')</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <script src="{{ asset('js/fontawesome.js') }}"></script>
        @if (env('APP_ENV') === 'production')
        <script src="{{ asset('js/vue.min.js') }}"></script>
        @else
        <script src="{{ asset('js/vue.js') }}"></script>
        @endif
    </head>

    <body>
        <main id="main">
            @include('navbar')

            <div class="content">
                @yield('content')
            </div>

            @include('about')
            @include('footer')
        </main>

        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.vue.initNavbar();
            });
        </script>
        @yield('javascript')
    </body>
</html>