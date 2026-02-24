@extends('layout')

@section('title', 'Catalogue - CineForAll')

@section('content')
    <div class="catalogue-container">

        <div class="catalogue-searchbox">
            <form method="GET" action="{{ url('/catalogue') }}" style="display:flex; gap:12px; align-items:center;">
                <input
                    type="text"
                    name="search"
                    class="catalogue-search"
                    placeholder="Rechercher un film ou acteur..."
                    id="catalogueSearch"
                    value="{{ isset($search) ? e($search) : '' }}"
                >

                <button type="submit" class="filter-btn">Rechercher</button>
            </form>

            <div class="catalogue-filters">

                <form method="GET" action="{{ url('/catalogue') }}" class="catalogue-filter-form">

                    <!-- Recherche conservée -->
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">

                    <!-- GENRE -->
                    <select name="genre" class="filter-select" onchange="this.form.submit()">
                        <option value="">Genre</option>

                        @foreach($genres as $g)
                            <option value="{{ $g->idGen }}" {{ (string)($selectedGenre ?? '') === (string)$g->idGen ? 'selected' : '' }}>
                                {{ $g->libGen }}
                            </option>
                        @endforeach
                    </select>

                    <!-- ANNEE -->
                    <select name="annee" class="filter-select" onchange="this.form.submit()">
                        <option value="">Année</option>

                        @for($y = date('Y'); $y >= 2000; $y--)
                            <option value="{{ $y }}" {{ request('annee')==$y?'selected':'' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <!-- POPULARITE -->
                    <select name="pop" class="filter-select" onchange="this.form.submit()">
                        <option value="">Popularité</option>

                        <option value="asc" {{ request('pop')=='asc'?'selected':'' }}>Moins populaire</option>
                        <option value="desc" {{ request('pop')=='desc'?'selected':'' }}>Plus populaire</option>
                    </select>

                </form>

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
                        $genre = (string)($film->typeFil ?? '');
                    @endphp

                    <a href="{{ route('films.show', $film->idFil) }}" style="text-decoration:none; color:inherit; display:block;">
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
                                @endif
                            </div>

                            <div class="film-info">
                                <div class="film-title">{{ $titre }}</div>

                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div style="color:#9aa0a6; padding: 20px;">
                    Aucun film dans le catalogue.
                </div>
            @endif
        </div>

    </div>
@endsection
