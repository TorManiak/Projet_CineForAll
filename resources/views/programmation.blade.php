@extends('layout')

@section('title', 'Programmation')

@section('content')
    <div class="admin-container">
        <div class="admin-header">
            <h2>Programme des cinémas</h2>
        </div>

        <div class="admin-filters admin-programmation-filters">
            <div class="date-selector">
                <a class="btn-action" href="{{ route('programmation.index', ['date' => $prevDate, 'cinema' => $cinema]) }}">&lt;</a>

                <span>
                    {{ \Carbon\Carbon::parse($date)->locale('fr')->translatedFormat('l j F Y') }}
                </span>

                <a class="btn-action" href="{{ route('programmation.index', ['date' => $nextDate, 'cinema' => $cinema]) }}">&gt;</a>
            </div>

            <form method="GET" action="{{ route('programmation.index') }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <select name="cinema" onchange="this.form.submit()">
                    <option value="all" {{ $cinema === 'all' ? 'selected' : '' }}>Tous les cinémas</option>
                    @foreach($cinemas as $c)
                        <option value="{{ $c->idCin }}" {{ (string)$cinema === (string)$c->idCin ? 'selected' : '' }}>
                            {{ $c->nomCin }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(($seances ?? collect())->count() === 0)
            <div class="admin-empty">
                <p>Aucune séance</p>
                <span>Aucune séance programmée pour cette date.</span>
            </div>
        @else
            <div class="admin-table">
                <div class="table-header">
                    <div class="th">Heure</div>
                    <div class="th">Film</div>
                    <div class="th">Cinéma</div>
                    <div class="th">Salle</div>
                    <div class="th">Langue</div>
                    <div class="th">Accessibilité</div>
                    <div class="th">Prix</div>
                </div>

                <div class="table-body">
                    @foreach($seances as $s)
                        @php
                            $dt = $s->datHeuSea ? \Carbon\Carbon::parse($s->datHeuSea) : null;
                            $rowTime = $dt ? $dt->format('H:i') : '-';
                        @endphp

                        <div class="table-row">
                            <div class="td">{{ $rowTime }}</div>
                            <div class="td">{{ $s->film_title ?? '-' }}</div>
                            <div class="td">{{ $s->cinema_name ?? '-' }}</div>
                            <div class="td">{{ $s->salle_name ?? 'Aucune salle' }}</div>
                            <div class="td">{{ $s->langue_name ?? '-' }}</div>
                            <div class="td">{{ (int)$s->malVoyEnt === 1 ? 'Mal voyant' : 'Standard' }}</div>
                            <div class="td">{{ number_format((float)$s->priSea, 2, ',', ' ') }} €</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
