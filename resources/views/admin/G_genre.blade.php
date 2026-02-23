@extends('layout')

@section('title', 'Admin - Genres')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Genres</h2>
            <button class="btn-add" onclick="openModal('modal-genre-add')">+ Ajouter un genre</button>
        </div>

        <div class="admin-filters">
            <input id="searchGenre" type="text" placeholder="Rechercher un genre..." oninput="filterRows('genreRow', this.value)">
        </div>

        <div class="admin-table">
            <div class="table-header table-header-genre">
                <span>Nom du genre</span>
                <span style="text-align:right;">Actions</span>
            </div>

            @if(isset($genres) && count($genres) > 0)
                @foreach($genres as $g)
                    <div class="table-row genreRow" data-search="{{ strtolower($g->libGen) }}">
                        <span>{{ $g->libGen }}</span>

                        <span style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
                        <button class="btn-small" type="button"
                                onclick="openEditGenre({{ $g->idGen }}, @js($g->libGen))">
                            Modifier
                        </button>

                        <form method="POST" action="{{ route('admin.genres.destroy', $g->idGen) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn-small btn-danger" type="submit" onclick="return confirm('Supprimer ce genre ?')">
                                Supprimer
                            </button>
                        </form>
                    </span>
                    </div>
                @endforeach
            @else
                <div class="admin-empty">
                    <div class="empty-icon">🎪</div>
                    <p>Aucun genre</p>
                    <span>Ajoutez votre premier genre de film</span>
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL AJOUT GENRE --}}
    <div class="modal" id="modal-genre-add">
        <div class="modal-content">
            <h3>Ajouter un genre</h3>

            <form method="POST" action="{{ route('admin.genres.store') }}">
                @csrf
                <label>Nom du genre</label>
                <input type="text" name="libGen" placeholder="Action, Drame, Comédie..." required>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-genre-add')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT GENRE --}}
    <div class="modal" id="modal-genre-edit">
        <div class="modal-content">
            <h3>Modifier un genre</h3>

            <form id="formGenreEdit" method="POST">
                @csrf
                @method('PUT')

                <label>Nom du genre</label>
                <input id="editLibGen" type="text" name="libGen" required>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-genre-edit')">Annuler</button>
                    <button type="submit" class="btn-confirm">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id){ document.getElementById(id).style.display='flex'; }
        function closeModal(id){ document.getElementById(id).style.display='none'; }

        function filterRows(className, query){
            query = (query || '').toLowerCase();
            document.querySelectorAll('.' + className).forEach(row => {
                const hay = row.getAttribute('data-search') || '';
                row.style.display = hay.includes(query) ? '' : 'none';
            });
        }

        function openEditGenre(idGen, libGen){
            document.getElementById('editLibGen').value = libGen;
            document.getElementById('formGenreEdit').action = `/admin/G_genre/${idGen}`;
            openModal('modal-genre-edit');
        }
    </script>
@endsection
