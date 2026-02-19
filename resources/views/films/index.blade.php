<h1>Catalogue des films</h1>

@foreach($films as $film)
    <div>
        <h2>{{ $film->nomFil }}</h2>
        <p>{{ $film->desFil }}</p>
        <a href="{{ route('films.show', $film->idFil) }}">Voir détail</a>
    </div>
@endforeach
