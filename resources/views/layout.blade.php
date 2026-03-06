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
@if(request()->is('admin/*'))
    @include('admin.header_admin')
@else
    @include('header')
@endif


{{-- Contenu spécifique des pages --}}
<main>
    @yield('content')
</main>
<footer class="siteFooter">
    <div class="footerContainer">

        <div class="footerLeft">
            <span>© {{ date('Y') }} CineForAll</span>
        </div>

        <div class="footerLinks">
            <a href="{{ route('conditions') }}">Conditions d'utilisation</a>
        </div>

    </div>
</footer>

</body>
</html>
