<header class="navbar">
    <div class="nav-left">
        <img src="{{ asset('img/logo.png') }}" alt="CineForAll" class="logo">
        <span class="brand">CineForAll</span>
    </div>

    <nav class="nav-right">
        <a href="{{ url('/') }}">Accueil</a>
        <a href="{{ url('/catalogue') }}">Catalogue</a>
        <a href="{{ url('/reservation') }}">Réservation</a>

        @php
            // Compatible avec Auth Laravel OU une connexion en session "maison"
            $isLogged = auth()->check()
                || session()->has('user')
                || session()->has('utilisateur')
                || session()->has('idUti')
                || session()->has('mailUti');
        @endphp

        @if($isLogged)
            <a href="{{ url('/logout') }}">Déconnexion</a>
        @else
            <a href="{{ url('/connexion') }}">Connexion</a>
        @endif
    </nav>
</header>
