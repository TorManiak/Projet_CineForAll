@extends('layout')

@section('title', 'Admin - Films')

@section('content')
    <div class="admin-container">

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

            <select id="statusFilter" onchange="filterFilmStatus()">
                <option value="">Tous les statuts</option>
                <option value="1">En salle</option>
                <option value="0">Pas en salle</option>
            </select>
        </div>

        {{-- TABLE (alignée, colonnes respectées) --}}
        <div class="admin-table">
            <div class="table-header">
                <div class="th">Titre</div>
                <div class="th">Durée</div>
                <div class="th">Genre(s)</div>
                <div class="th">En salle</div>
                <div class="th th-actions">Actions</div>
            </div>

            <div class="table-body">
                @forelse($films ?? [] as $film)
                    @php
                        $id = $film->idFil ?? $film->id ?? null;
                        $titre = $film->nomFil ?? $film->titre ?? '';
                        $duree = $film->datFil ?? $film->duree ?? '';
                        $genres = $film->typeFil
                            ?? ($film->genres_list ?? null)
                            ?? (isset($film->genres) ? $film->genres->pluck('libGen')->implode(', ') : '');
                        $enSalleVal = $film->malVoyEnt ?? $film->en_salle ?? 0;
                        $enSalleTxt = ((string)$enSalleVal === '1') ? 'Oui' : 'Non';
                    @endphp

                    <div class="table-row filmRow" data-status="{{ (string)$enSalleVal === '1' ? '1' : '0' }}">
                        <div class="td td-title">{{ $titre }}</div>
                        <div class="td">{{ $duree }}</div>
                        <div class="td">{{ $genres }}</div>
                        <div class="td">{{ $enSalleTxt }}</div>

                        <div class="td td-actions">
                            <div class="actions-group">
                                <button
                                    type="button"
                                    class="btn-action btn-edit"
                                    onclick="openEditFilmModal(
                                    '{{ $id }}',
                                    @json($titre),
                                    @json($duree),
                                    @json($genres),
                                    '{{ (string)$enSalleVal === '1' ? '1' : '0' }}'
                                )"
                                >
                                    Modifier
                                </button>

                                <form
                                    method="POST"
                                    action="{{ url('/admin/films/' . $id) }}"
                                    onsubmit="return confirm('Supprimer ce film ?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="admin-empty">
                        <div class="empty-icon">🎬</div>
                        <p>Aucun film</p>
                        <span>Commencez par ajouter votre premier film au catalogue</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- MODAL AJOUT FILM --}}
    <div class="modal" id="modal-film-add" style="display:none;">
        <div class="modal-content">
            <h3>Ajouter un film</h3>

            <form method="POST" action="{{ url('/admin/films') }}">
                @csrf

                <label>Titre du film</label>
                <input name="nomFil" type="text" required>

                <label>Durée</label>
                <input name="datFil" type="text" placeholder="01:52:00" required>

                <label>Genre(s) (séparés par des virgules)</label>
                <input name="typeFil" type="text" placeholder="Action, Drame, Sci-fi" required>

                <label>En salle</label>
                <select name="malVoyEnt" required>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
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

            <form id="filmEditForm" method="POST" action="">
                @csrf
                @method('PUT')

                <label>Titre du film</label>
                <input id="edit_nomFil" name="nomFil" type="text" required>

                <label>Durée</label>
                <input id="edit_datFil" name="datFil" type="text" placeholder="01:52:00" required>

                <label>Genre(s) (séparés par des virgules)</label>
                <input id="edit_typeFil" name="typeFil" type="text" required>

                <label>En salle</label>
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

    {{-- JS (aucun changement UI, juste alignement + modals + filtres) --}}
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
                if (status === '') {
                    row.style.display = '';
                } else {
                    row.style.display = (rowStatus === status) ? '' : 'none';
                }
            });
        }

        function openEditFilmModal(id, titre, duree, genres, enSalle) {
            const modalId = 'modal-film-edit';
            const modal = document.getElementById(modalId);
            if (!modal) return;

            // Remplissage champs
            document.getElementById('edit_nomFil').value = titre || '';
            document.getElementById('edit_datFil').value = duree || '';
            document.getElementById('edit_typeFil').value = genres || '';
            document.getElementById('edit_malVoyEnt').value = (String(enSalle) === '1') ? '1' : '0';

            // Action du form
            const form = document.getElementById('filmEditForm');
            form.action = "{{ url('/admin/films') }}/" + id;

            openModal(modalId);
        }
    </script>

    {{-- CSS d’alignement COLONNES (ne change pas ton UI, juste corrige l’alignement) --}}
    <style>
        /* Ces styles ne changent pas le design global : ils forcent juste l'alignement des colonnes */
        .table-header,
        .table-row{
            display: grid;
            grid-template-columns: 1.4fr 0.7fr 1.2fr 0.6fr 0.9fr; /* Titre | Durée | Genres | En salle | Actions */
            align-items: center;
            column-gap: 16px;
        }

        .table-header .th,
        .table-row .td{
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .th-actions,
        .td-actions{
            justify-self: end;
            text-align: right;
        }

        .actions-group{
            display: inline-flex;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
        }

        .actions-group form{
            margin: 0;
        }
    </style>

@endsection
