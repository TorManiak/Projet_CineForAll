@extends('layout')

@section('title', 'Admin - Films')

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
            <h2>Films</h2>
            <button class="btn-add" type="button" onclick="openModal('modal-film-add')">+ Ajouter un film</button>
        </div>

        <div class="admin-filters">
            <input
                id="searchFilm"
                type="text"
                placeholder="Rechercher un film..."
                oninput="filterRows('filmRow', this.value)"
            >

            {{-- Garde ton filtre tel quel (il filtre sur malVoyEnt car data-status = malVoyEnt) --}}
            <select id="statusFilter" onchange="filterFilmStatus()">
                <option value="">Tous les statuts</option>
                <option value="1">Mal voyant = Oui</option>
                <option value="0">Mal voyant = Non</option>
            </select>
        </div>

        <div class="admin-table">
            <div class="table-header">
                <div class="th">Titre</div>
                <div class="th">Durée</div>
                <div class="th">Genre(s)</div>
                <div class="th">Mal voyant</div>
                <div class="th th-actions">Actions</div>
            </div>

            <div class="table-body">
                @forelse($films ?? [] as $film)
                    @php
                        $id = $film->idFil ?? null;
                        $titre = $film->nomFil ?? '';
                        $duree = $film->datFil ?? '';
                        $genres = $film->typeFil ?? '';
                        $desFil = $film->desFil ?? '';
                        $banAnn = $film->banAnn ?? '';
                        $malVoy = (string)($film->malVoyEnt ?? '0');
                        $malVoyTxt = ($malVoy === '1') ? 'Oui' : 'Non';
                    @endphp

                    <div class="table-row filmRow" data-status="{{ $malVoy }}">
                        <div class="td td-title">{{ $titre }}</div>
                        <div class="td">{{ $duree }}</div>
                        <div class="td">{{ $genres }}</div>
                        <div class="td">{{ $malVoyTxt }}</div>

                        <div class="td td-actions">
                            <div class="actions-group">
                                {{-- IMPORTANT: plus de onclick avec params => on évite les erreurs JS --}}
                                <button
                                    type="button"
                                    class="btn-action btn-edit js-edit-film"
                                    data-id="{{ $id }}"
                                    data-titre="{{ e($titre) }}"
                                    data-duree="{{ e($duree) }}"
                                    data-genres="{{ e($genres) }}"
                                    data-desfil="{{ e($desFil) }}"
                                    data-banann="{{ e($banAnn) }}"
                                    data-malvoy="{{ $malVoy }}"
                                >
                                    Modifier
                                </button>

                                <form method="POST"
                                      action="{{ route('admin.films.destroy', ['idFil' => $id]) }}"
                                      onsubmit="return confirm('Supprimer ce film ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="admin-empty">
                        <p>Aucun film</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- MODAL AJOUT FILM --}}
    <div class="modal" id="modal-film-add" style="display:none;">
        <div class="modal-content">
            <h3>Ajouter un film</h3>

            <form method="POST"
                  action="{{ route('admin.films.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <label>Titre du film</label>
                <input name="nomFil" type="text" required value="{{ old('nomFil') }}">

                <label>Durée (HH:MM:SS)</label>
                <input name="datFil" type="text" placeholder="01:52:00" required value="{{ old('datFil') }}">

                <label>Genre(s) (séparés par des virgules)</label>
                <input name="genres" type="text" placeholder="Action, Drame, Sci-fi" value="{{ old('genres') }}">

                <label>Synopsis</label>
                <textarea name="desFil" placeholder="Description du film...">{{ old('desFil') }}</textarea>

                <label>Bande-annonce (URL YouTube)</label>
                <input type="text" name="banAnn" placeholder="https://youtube.com/watch?v=..." value="{{ old('banAnn') }}">

                <label>Affiche (jpg/png/webp)</label>
                <input type="file" name="afiFil" accept=".jpg,.jpeg,.png,.webp,image/*">

                <label>Mal voyant</label>
                <select name="malVoyEnt" required>
                    <option value="1" {{ old('malVoyEnt', '1') == '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('malVoyEnt') == '0' ? 'selected' : '' }}>Non</option>
                </select>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-film-add')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT FILM --}}
    <div class="modal" id="modal-film-edit" style="display:none;">
        <div class="modal-content">
            <h3>Modifier un film</h3>

            <form id="filmEditForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label>Titre du film</label>
                <input id="edit_nomFil" name="nomFil" type="text" required>

                <label>Durée (HH:MM:SS)</label>
                <input id="edit_datFil" name="datFil" type="text" placeholder="01:52:00" required>

                <label>Genre(s) (séparés par des virgules)</label>
                <input id="edit_genres" name="genres" type="text">

                <label>Synopsis</label>
                <textarea id="edit_desFil" name="desFil" placeholder="Description du film..."></textarea>

                <label>Bande-annonce (URL YouTube)</label>
                <input id="edit_banAnn" type="text" name="banAnn" placeholder="https://youtube.com/watch?v=...">

                <label>Nouvelle affiche (optionnel)</label>
                <input type="file" name="afiFil" accept=".jpg,.jpeg,.png,.webp,image/*">

                <label>Mal voyant</label>
                <select id="edit_malVoyEnt" name="malVoyEnt" required>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-film-edit')">Annuler</button>
                    <button type="submit" class="btn-confirm">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = 'flex';
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        }

        function filterRows(rowClass, query) {
            query = (query || '').toLowerCase();
            document.querySelectorAll('.' + rowClass).forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }

        function filterFilmStatus() {
            const status = document.getElementById('statusFilter').value;
            document.querySelectorAll('.filmRow').forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (status === '') row.style.display = '';
                else row.style.display = (rowStatus === status) ? '' : 'none';
            });
        }

        // Click sur "Modifier"
        document.querySelectorAll('.js-edit-film').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;

                document.getElementById('edit_nomFil').value   = btn.dataset.titre || '';
                document.getElementById('edit_datFil').value   = btn.dataset.duree || '';
                document.getElementById('edit_genres').value   = btn.dataset.genres || '';
                document.getElementById('edit_desFil').value   = btn.dataset.desfil || '';
                document.getElementById('edit_banAnn').value   = btn.dataset.banann || '';
                document.getElementById('edit_malVoyEnt').value = (String(btn.dataset.malvoy) === '1') ? '1' : '0';

                const form = document.getElementById('filmEditForm');
                form.action = "{{ url('/admin/G_film') }}/" + id;

                openModal('modal-film-edit');
            });
        });

        @if($errors->any())
        // si validation KO, on ré-ouvre la modale d'ajout
        openModal('modal-film-add');
        @endif
    </script>
@endsection
