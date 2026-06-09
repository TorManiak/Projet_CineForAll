@extends('layout')

@section('title', 'Personnalités - CineForAll')

@section('content')
    <div class="catalogue-container">

        <div class="catalogue-searchbox">
            <form method="GET" action="{{ url('/personnalites') }}" style="display:flex; gap:12px; align-items:center;">
                <input
                    type="text"
                    name="search"
                    class="catalogue-search"
                    placeholder="Rechercher un acteur ou réalisateur..."
                    value="{{ isset($search) ? e($search) : '' }}"
                >
                <button type="submit" class="filter-btn">Rechercher</button>
                @if(!empty($search))
                    <a href="{{ url('/personnalites') }}" class="filter-btn" style="background:#555;">Effacer</a>
                @endif
            </form>
        </div>

        <div class="catalogue-grid">
            @if(isset($personnalites) && count($personnalites) > 0)
                @foreach($personnalites as $p)
                    <a href="{{ route('personnalites.show', $p->idPer) }}" style="text-decoration:none; color:inherit; display:block;">
                        <div class="film-card">
                            <div class="film-poster">
                                <img
                                    src="{{ asset('img/personnalites/' . $p->idPer . '.jpg') }}"
                                    alt="{{ $p->prePer }} {{ $p->nomPer }}"
                                    class="film-img"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($p->prePer . ' ' . $p->nomPer) }}&size=300&background=333&color=fff&bold=true&font-size=0.33'"
                                >
                            </div>
                            <div class="film-info">
                                <div class="film-title">{{ $p->prePer }} {{ $p->nomPer }}</div>
                                <div style="font-size:0.85rem; opacity:0.7; margin-top:4px;">{{ $p->nationalite ?? '' }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div style="color:#9aa0a6; padding:20px;">
                    Aucune personnalité trouvée.
                </div>
            @endif
        </div>

    </div>
@endsection
