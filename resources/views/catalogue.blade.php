@extends('layout')

@section('title', 'Catalogue - CineForAll')

@section('content')
    <div class="catalogue-container">

        <div class="catalogue-searchbox">
            <form method="GET" action="{{ url('/catalogue') }}">
                <input
                    type="text"
                    name="search"
                    class="catalogue-search"
                    placeholder="Rechercher un film ou acteur..."
                    id="catalogueSearch"
                    value="{{ isset($search) ? e($search) : '' }}"
                >
            </form>

            <div class="catalogue-filters">
                <button class="filter-btn" type="button">Genre</button>
                <button class="filter-btn" type="button">Année</button>
                <button class="filter-btn" type="button">Popularité</button>
            </div>
        </div>

        <div class="catalogue-grid" id="catalogueGrid">
            @if(isset($films) && count($films) > 0)
                @foreach($films as $film)
                    @php
                        $fileName = trim((string)($film->afiFil ?? ''));
                        $relativePath = $fileName !== '' ? 'img/films/' . ltrim($fileName, '/') : '';
                        $absolutePath = $relativePath !== '' ? public_path($relativePath) : '';
                        $hasPoster = $absolutePath !== '' && file_exists($absolutePath);

                        $genresTxt = trim((string)($film->genres ?? ''));
                        $acteursTxt = trim((string)($film->acteurs ?? ''));
                        $titre = (string)($film->nomFil ?? '');
                        $duree = (string)($film->datFil ?? '');
                    @endphp

                    <div
                        class="film-card"
                        data-title="{{ strtolower($titre) }}"
                        data-genres="{{ strtolower($genresTxt) }}"
                        data-acteurs="{{ strtolower($acteursTxt) }}"
                    >
                        <div class="film-poster">
                            @if($hasPoster)
                                <img
                                    src="{{ asset($relativePath) }}"
                                    alt="Affiche {{ $titre }}"
                                    class="film-img"
                                >
                            @else
                                <span class="film-star">★</span>
                            @endif
                        </div>

                        <div class="film-info">
                            <div class="film-title">{{ $titre }}</div>
                            @if($duree !== '')
                                <div class="film-duree">{{ $duree }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div style="color:#9aa0a6; padding: 20px;">
                    Aucun film dans le catalogue.
                </div>
            @endif
        </div>

    </div>

    <script>
        (function () {
            const input = document.getElementById('catalogueSearch');
            const cards = Array.from(document.querySelectorAll('#catalogueGrid .film-card'));
            if (!input) return;

            input.addEventListener('input', function () {
                const q = (this.value || '').toLowerCase().trim();

                cards.forEach(card => {
                    const title = (card.getAttribute('data-title') || '');
                    const genres = (card.getAttribute('data-genres') || '');
                    const acteurs = (card.getAttribute('data-acteurs') || '');

                    const ok = title.includes(q) || genres.includes(q) || acteurs.includes(q);
                    card.style.display = ok ? '' : 'none';
                });
            });
        })();
    </script>
@endsection
