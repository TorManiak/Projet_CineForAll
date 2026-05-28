@php use Carbon\Carbon; @endphp
@extends('layout')

@section('title', $personnalite->prePer . ' ' . $personnalite->nomPer . ' - CineForAll')

@section('content')
    <div class="moviePage">
        <div class="movieWrap">

            {{-- Colonne gauche --}}
            <div class="moviePosterCard">
                <div class="moviePosterTop">
                    <img
                        class="moviePosterImg"
                        src="{{ asset('img/personnalites/' . $personnalite->idPer . '.jpg') }}"
                        alt="{{ $personnalite->prePer }} {{ $personnalite->nomPer }}"
                        onerror="this.style.display='none'"
                    >
                </div>
                <div class="moviePosterBottom">
                    <div class="moviePosterTitle">{{ $personnalite->prePer }} {{ $personnalite->nomPer }}</div>
                    <div class="moviePosterMeta">
                        @if(!empty($personnalite->datNaiPer))
                            Né(e) le {{ Carbon::parse($personnalite->datNaiPer)->format('d/m/Y') }}
                        @endif
                        @if(!empty($personnalite->nationalite))
                            · {{ $personnalite->nationalite }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- Colonne droite --}}
            <div class="moviePanel">
                <div class="movieHeading">
                    <div class="movieH1">{{ $personnalite->prePer }} {{ $personnalite->nomPer }}</div>
                </div>

                <div class="movieSynopsis">
                    <div class="movieSynopsisTitle">Biographie</div>
                    <div class="movieSynopsisText">
                        {{ $personnalite->desPer ?? 'Aucune description disponible.' }}
                    </div>
                </div>

                <div class="movieInfoGrid">
                    <div class="infoCard">
                        <div class="infoLabel">NATIONALITÉ</div>
                        <div class="infoValue">{{ $personnalite->nationalite ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="infoCard">
                        <div class="infoLabel">DATE DE NAISSANCE</div>
                        <div class="infoValue">
                            @if(!empty($personnalite->datNaiPer))
                                {{ Carbon::parse($personnalite->datNaiPer)->format('d/m/Y') }}
                            @else
                                Non renseigné
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filmographie --}}
        <div class="reservationWrap">
            <div class="reservationCard">
                <div class="reservationTitle">Filmographie</div>

                @if($films->isEmpty())
                    <p style="color:#9aa0a6;">Aucun film enregistré.</p>
                @else
                    <div class="catalogue-grid" style="margin-top:16px;">
                        @foreach($films as $film)
                            <a href="{{ route('films.show', $film->idFil) }}" style="text-decoration:none; color:inherit; display:block;">
                                <div class="film-card">
                                    <div class="film-poster">
                                        <img
                                            src="{{ asset('img/films/' . $film->afiFil) }}"
                                            alt="{{ $film->nomFil }}"
                                            class="film-img"
                                            onerror="this.style.display='none'"
                                        >
                                    </div>
                                    <div class="film-info">
                                        <div class="film-title">{{ $film->nomFil }}</div>
                                        <div style="font-size:0.85rem; opacity:0.7; margin-top:4px;">
                                            {{ $film->annSor }} · {{ $film->libRol }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
