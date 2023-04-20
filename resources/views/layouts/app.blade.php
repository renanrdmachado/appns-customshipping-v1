<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Frete Personalizado') }}</title>

        <!--FAVICON-->
        <link rel="icon" type="image/png" href="{{ Asset('/build/assets/favicon.png') }}">
        <link rel="icon" type="image/vnd.microsoft.icon" href="{{ Asset('/build/assets/favicon.png') }}">
        <link rel="apple-touch-icon" sizes="300x300" href="{{ Asset('/build/assets/favicon.png') }}">
        <link rel="apple-touch-icon-precomposed" sizes="300x300" href="{{ Asset('/build/assets/favicon.png') }}">
        <!--/FAVICON-->

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ Asset('/build/assets/style.css') }}" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <div class="text-center text-sm text-gray-300">
                <p>Versão 1.0 - Beta.</p>
            </div>
        </div>
        

        <div class="loader">
            <i class="fa-solid fa-spinner"></i>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="{{ Asset('/build/assets/script.js') }}"></script>
    </body>
</html>
