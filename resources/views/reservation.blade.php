{{-- resources/views/reservation.blade.php --}}
@extends('layout')

@section('title', 'Mes réservations - CineForAll')

@section('content')
    <div class="reservations-page">
        <div class="reservations-wrap">

            <h1 class="reservations-title">Mes réservations</h1>

            <div class="reservations-toolbar">
                <form method="GET" action="{{ url('/reservation') }}" class="reservations-search">
                    <input
                        type="text"
                        name="search"
                        placeholder="Rechercher par film..."
                        value="{{ isset($search) ? e($search) : '' }}"
                    />
                    <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
                </form>

                <div class="reservations-filters">
                    <a class="res-btn {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}"
                       href="{{ url('/reservation?filter=all&search=' . urlencode($search ?? '')) }}">Toutes</a>

                    <a class="res-btn {{ ($filter ?? 'all') === 'upcoming' ? 'active' : '' }}"
                       href="{{ url('/reservation?filter=upcoming&search=' . urlencode($search ?? '')) }}">À venir</a>

                    <a class="res-btn {{ ($filter ?? 'all') === 'past' ? 'active' : '' }}"
                       href="{{ url('/reservation?filter=past&search=' . urlencode($search ?? '')) }}">Passées</a>
                </div>
            </div>

            @if(isset($reservations) && count($reservations) > 0)
                <div class="reservations-list">
                    @foreach($reservations as $r)
                        <div class="reservation-card">
                            <div class="reservation-poster">
                                @if(!empty($r['poster']))
                                    <img src="{{ asset($r['poster']) }}" alt="Affiche {{ $r['film_title'] }}">
                                @else
                                    <div class="reservation-poster-empty">★</div>
                                @endif
                            </div>

                            <div class="reservation-info">
                                <div class="reservation-film">{{ $r['film_title'] }}</div>

                                @if(!empty($r['date_time']))
                                    <div class="reservation-meta">{{ $r['date_time'] }}</div>
                                @else
                                    <div class="reservation-meta">Date non renseignée</div>
                                @endif

                                {{-- ACTIONS (suppression) --}}
                                @if(!empty($r['idRes']))
                                    <div class="reservation-actions" style="margin-top:10px;">
                                        <form method="POST" action="{{ url('/reservation/' . $r['idRes']) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="res-btn"
                                                style="background:#b30000; border-color:#b30000;"
                                                onclick="return confirm('Supprimer cette réservation ?');"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="reservations-empty">
                    <div class="empty-icon">≡</div>
                    <div class="empty-text">
                        Vous n'avez aucune réservation en cours. Parcourez le catalogue pour réserver un film !
                    </div>
                    <a href="{{ url('/catalogue') }}" class="empty-cta">Voir les films</a>
                </div>
            @endif

        </div>
    </div>
@endsection
