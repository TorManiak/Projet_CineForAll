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
