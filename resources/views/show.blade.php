@extends('layout')

@section('title', $film->nomFil . ' - CineForAll')

@section('content')
    <div class="moviePage">
        <div class="movieWrap">

            {{-- Colonne gauche (poster + infos) --}}
            <div class="moviePosterCard">
                <div class="moviePosterTop">
                    <img
                        class="moviePosterImg"
                        src="{{ asset('img/films/' . $film->afiFil) }}"
                        alt="Affiche {{ $film->nomFil }}"
                    >
                </div>

                <div class="moviePosterBottom">
                    <div class="moviePosterTitle">{{ $film->nomFil }}</div>
                    <div class="moviePosterMeta">
                        @if(!empty($film->typeFil)) {{ $film->typeFil }} @endif
                        @if(!empty($film->datFil)) · {{ $film->datFil }} @endif
                        @if(!empty($film->annSor)) . {{ $film->annSor }}@endif
                    </div>
                </div>
            </div>

            {{-- Colonne droite (infos + synopsis + cards) --}}
            <div class="moviePanel">
                <div class="movieHeading">
                    <div class="movieH1">{{ $film->nomFil }}</div>

                    <div class="movieLine">
                        @if(!empty($film->typeFil)) <span>{{ $film->typeFil }}</span> @endif

                            @if(!empty($film->annSor)) . <span>{{ $film->annSor }}</span>@endif


                        @if(!empty($film->datFil)) <span>· {{ $film->datFil }}</span> @endif
                    </div>
                </div>

                <div class="movieSynopsis">
                    <div class="movieSynopsisTitle">Synopsis</div>
                    <div class="movieSynopsisText">
                        {{ $film->desFil }}
                    </div>
                </div>

                <div class="movieInfoGrid">
                    <div class="infoCard">
                        <div class="infoLabel">RÉALISATEUR</div>
                        <div class="infoValue">Non renseigné</div>
                    </div>

                    <div class="infoCard">
                        <div class="infoLabel">CASTING</div>
                        <div class="infoValue">Non renseigné</div>
                    </div>

                    <div class="infoCard">
                        <div class="infoLabel">LANGUE</div>
                        <div class="infoValue">Non renseigné</div>
                    </div>

                    <div class="infoCard">
                        <div class="infoLabel">CLASSIFICATION</div>
                        <div class="infoValue">
                            {{ (int)$film->malVoyEnt === 1 ? 'Tous publics avec avertissement' : 'Tous publics' }}
                        </div>
                    </div>
                </div>

                @if(!empty($film->banAnn))
                    <div class="movieActions">
                        <a class="movieBtn" href="{{ $film->banAnn }}" target="_blank" rel="noopener noreferrer">
                            Voir la bande-annonce
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
