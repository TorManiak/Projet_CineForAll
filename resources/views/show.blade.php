@php use Carbon\Carbon; @endphp
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
                        @if(!empty($film->typeFil))
                            {{ $film->typeFil }}
                        @endif

                        @if(!empty($film->datFil))
                            · {{ $film->datFil }}
                        @endif

                        @if(!empty($film->annSor))
                            . {{ $film->annSor }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- Colonne droite --}}
            <div class="moviePanel">
                <div class="movieHeading">
                    <div class="movieH1">{{ $film->nomFil }}</div>

                    <div class="movieLine">
                        @if(!empty($film->typeFil))
                            <span>{{ $film->typeFil }}</span>
                        @endif

                        @if(!empty($film->annSor))
                            . <span>{{ $film->annSor }}</span>
                        @endif

                        @if(!empty($film->datFil))
                            <span>· {{ $film->datFil }}</span>
                        @endif
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
                        <div class="infoValue">
                            @if(!empty($realisateurs))
                                {{ implode(', ', $realisateurs) }}
                            @else
                                Non renseigné
                            @endif
                        </div>
                    </div>

                    <div class="infoCard">
                        <div class="infoLabel">CASTING</div>
                        <div class="infoValue">
                            @if(!empty($casting))
                                {{ implode(', ', $casting) }}
                            @else
                                Non renseigné
                            @endif
                        </div>
                    </div>

                    <div class="infoCard">
                        <div class="infoLabel">LANGUE</div>
                        <div class="infoValue">
                            @if(!empty($langues))
                                {{ implode(', ', $langues) }}
                            @else
                                Non renseigné
                            @endif
                        </div>
                    </div>

                    <div class="infoCard">
                        <div class="infoLabel">CLASSIFICATION</div>
                        <div class="infoValue">
                            {{ $film->libClassification ?? 'Non renseigné' }}
                        </div>
                    </div>
                </div>

                {{-- BLOC NOTE --}}
                <div class="movieSynopsis" style="margin-top:16px;">
                    <div class="movieSynopsisTitle">Note</div>

                    <div class="movieSynopsisText" style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                        <div>
                            <strong>
                                {{ ($noteMoyenne !== null) ? number_format($noteMoyenne, 1) . '/5' : 'Aucune note' }}
                            </strong>
                            <span style="opacity:.8;">
                            ({{ $nbNotes ?? 0 }} avis)
                        </span>
                        </div>

                        @if(session()->has('user'))
                            <form
                                method="POST"
                                action="{{ route('films.note', ['film' => $film->idFil]) }}"
                                style="display:flex; gap:10px; align-items:center;"
                            >
                                @csrf

                                <label style="opacity:.9;">Votre note :</label>

                                <select name="note" class="ratingSelect" style="width:auto; min-width:90px;">
                                    <option value="">--</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ (int)($maNote ?? 0) === $i ? 'selected' : '' }}>
                                            {{ $i }}/5
                                        </option>
                                    @endfor
                                </select>

                                <button
                                    type="submit"
                                    class="reservationBtnPrimary"
                                    style="width:auto; padding:10px 14px;"
                                >
                                    Noter
                                </button>

                                @if(!empty($maNote))
                                    <span style="opacity:.8;">
                                    (Votre note actuelle : {{ (int)$maNote }}/5)
                                </span>
                                @endif
                            </form>
                        @else
                            <div style="opacity:.8;">
                                Connectez-vous pour noter ce film.
                            </div>
                        @endif
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

        {{-- BLOC RÉSERVATION --}}
        <div class="reservationWrap">
            <div class="reservationCard">
                <div class="reservationTitle">Séances &amp; Réservation</div>

                {{-- Messages --}}
                @if(session('success'))
                    <div class="alert-success" style="margin-bottom:12px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error" style="margin-bottom:12px;">
                        <ul style="margin:0; padding-left:18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Choix cinéma (GET) --}}
                <div class="reservationField">
                    <div class="reservationLabel">Choisissez votre cinéma</div>

                    <form method="GET" action="{{ route('films.show', ['film' => $film->idFil]) }}">
                        @if(!empty($selectedDate))
                            <input type="hidden" name="date" value="{{ $selectedDate }}">
                        @endif

                        <select name="cinema" class="reservationSelect" onchange="this.form.submit()">
                            <option value="">-- Sélectionner un cinéma --</option>
                            @foreach(($cinemas ?? []) as $cinema)
                                <option
                                    value="{{ $cinema->idCin }}"
                                    {{ (string)$selectedCinema === (string)$cinema->idCin ? 'selected' : '' }}
                                >
                                    {{ $cinema->nomCin }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Dates (liens GET) --}}
                <div class="reservationField">
                    <div class="reservationLabel">Sélectionnez une date</div>

                    <div class="reservationDates">
                        @foreach(collect($dates ?? []) as $d)
                            @php $carbon = Carbon::parse($d)->locale('fr'); @endphp

                            <a
                                class="dateBtn {{ (string)$selectedDate === (string)$d ? 'is-active' : '' }}"
                                href="{{ route('films.show', ['film' => $film->idFil, 'cinema' => $selectedCinema, 'date' => $d]) }}"
                            >
                                <div class="dateBtnDay">{{ ucfirst($carbon->dayName) }}</div>
                                <div class="dateBtnNum">{{ $carbon->format('d') }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Horaires + submit (POST) --}}
                <form method="POST" action="{{ route('reservations.store') }}">
                    @csrf

                    <div class="reservationField">
                        <div class="reservationLabel">Choisissez un horaire</div>

                        <div class="reservationTimes">
                            @foreach(collect($seances ?? []) as $s)
                                @php
                                    $dt = !empty($s->datHeuSea) ? Carbon::parse($s->datHeuSea) : null;
                                    $hhmm = $dt ? $dt->format('H:i') : '--:--';

                                    $hasPlaces = property_exists($s, 'plaRes');
                                    $places = $hasPlaces ? (int)($s->plaRes ?? 0) : null;

                                    $disabled = $hasPlaces && $places <= 0;
                                    $checked = !$disabled && (int)($defaultSeanceId ?? 0) === (int)$s->idSea;

                                    $meta = $disabled ? 'Complet' : ($hasPlaces ? ($places . ' places') : 'Disponible');
                                @endphp

                                <input
                                    type="radio"
                                    name="idSea"
                                    id="sea_{{ $s->idSea }}"
                                    value="{{ $s->idSea }}"
                                    class="timeRadio"
                                    {{ $checked ? 'checked' : '' }}
                                    {{ $disabled ? 'disabled' : '' }}
                                >

                                <label for="sea_{{ $s->idSea }}" class="timeBtn {{ $disabled ? 'is-disabled' : '' }}">
                                    <div class="timeBtnHour">{{ $hhmm }}</div>
                                    <div class="timeBtnMeta">{{ $meta }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="reservationActions">
                        <button
                            class="reservationBtnPrimary"
                            type="submit"
                            {{ empty($defaultSeanceId) ? 'disabled' : '' }}
                        >
                            Réserver une place
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
