<header class="navbar">
    <div class="nav-left">
        <img src="{{ asset('img/logo.png') }}" class="logo" alt="">
        <span class="brand">CineForAll</span>
    </div>

    <nav class="nav-right">
        <a href="/admin/G_film">Films</a>
        <a href="/admin/G_acteur">Personnalités</a>
        <a href="/admin/G_genre">Genres</a>
        <a href="/admin/G_cine_salle">Cinéma</a>
        <a href="/admin/G_prog">Programmation</a>
        <a href="{{ route('logout') }}">Déconnexion</a>
    </nav>
</header>
