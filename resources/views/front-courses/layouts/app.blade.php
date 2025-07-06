<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link href="{{ asset('css/output.css') }}" rel="stylesheet">
        {{-- style css tambahan setelah after styling css turunannya bisa menggunakan push--}}
        @stack('after-styles')
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
        <title>@yield('title')</title>
        <meta name="description" content="Obito is an innovative online learning platform that empowers students and professionals with high-quality, accessible courses.">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/logos/logo-64.png') }}">
        <link rel="apple-touch-icon" href="assets/images/logos/logo-64.png') }}">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="Obito Online Learning Platform - Learn Anytime, Anywhere">
        <meta property="og:description" content="Obito is an innovative online learning platform that empowers students and professionals with high-quality, accessible courses.">
        <meta property="og:type" content="website">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- penempatan content yang akan diteruskan akan menggunakan section sebagai turunan --}}
        @yield('content')

        {{-- menambahkan script jika ada java script tambahan turunannya bisa menggunakan push--}}
        @stack('after-scripts')
    </body>
</html>
