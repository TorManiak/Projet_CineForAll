@extends('layout')

@section('title', 'Admin - Genres')

@section('content')
    <div class="admin-container">

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

        <div class="admin-header">
            <h2>Genres</h2>
            <button class="btn-add" type="button" onclick="openModal('modal-genre-add')">+ Ajouter un genre</button>
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
                            <button class="btn-action btn-edit js-edit-acteur" type="button"
                                    onclick="openEditGenre({{ $g->idGen }}, @js($g->libGen))">
                                Modifier
                            </button>

                            <form method="POST" action="{{ route('admin.genres.destroy', $g->idGen) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn-action btn-delete" type="submit" onclick="return confirm('Supprimer ce genre ?')">
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
    <div class="modal" id="modal-genre-add" style="display:none;">
        <div class="modal-content">
            <h3>Ajouter un genre</h3>

            <form method="POST" action="{{ route('admin.genres.store') }}">
                @csrf

                <label>Nom du genre</label>
                <input
                    type="text"
                    name="libGen"
                    value="{{ old('libGen') }}"
                    placeholder="Action, Western, Drama"
                    required
                    maxlength="50"
                    pattern="^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$"
                    title="Lettres uniquement (espaces, tirets et apostrophes autorisés)."
                >
                @error('libGen')
                <div class="field-error" style="margin-top:6px;">{{ $message }}</div>
                @enderror

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-genre-add')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT GENRE --}}
    <div class="td td-actions" id="modal-genre-edit" style="display:none;">
        <div class="modal-content">
            <h3>Modifier un genre</h3>

            <form id="formGenreEdit" method="POST">
                @csrf
                @method('PUT')

                <label>Nom du genre</label>
                <input
                    id="editLibGen"
                    type="text"
                    name="libGen"
                    required
                    maxlength="50"
                    pattern="^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$"
                    title="Lettres uniquement (espaces, tirets et apostrophes autorisés)."
                >
                @error('libGen')
                <div class="field-error" style="margin-top:6px;">{{ $message }}</div>
                @enderror

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

        // Si validation KO sur libGen, on rouvre la modale d'ajout
        @if($errors->has('libGen'))
        openModal('modal-genre-add');
        @endif
    </script>
@endsection
