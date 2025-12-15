<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CineForAll')</title>

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
</head>
<body>

{{-- Header --}}
@include('header')

{{-- Contenu spécifique des pages --}}
<main>
    @yield('content')
</main>

</body>
</html>
