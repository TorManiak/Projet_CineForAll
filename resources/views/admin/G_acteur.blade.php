@extends('layout')

@section('title', 'Admin - Acteurs')

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
            <h2>Acteurs</h2>
            <button class="btn-add" type="button" onclick="openModal('modal-acteur-add')">+ Ajouter un acteur</button>
        </div>

        <div class="admin-filters">
            <input
                id="searchActeur"
                type="text"
                placeholder="Rechercher un acteur..."
                oninput="filterRows('acteurRow', this.value)"
                value="{{ $search ?? '' }}"
            >
        </div>

        <div class="admin-table">
            <div class="table-header">
                <div class="th">Nom</div>
                <div class="th">Prénom</div>
                <div class="th">Date naissance</div>
                <div class="th">Nationalité</div>
                <div class="th">Films</div>
                <div class="th th-actions">Actions</div>
            </div>

            <div class="table-body">
                @forelse($acteurs ?? [] as $a)
                    @php
                        $id     = (int)($a->idPer ?? 0);
                        $nom    = (string)($a->nomPer ?? '');
                        $prenom = (string)($a->prePer ?? '');
                        $dat    = (string)($a->datNaiPer ?? '');
                        $nat    = (string)($a->natPer ?? '');
                        $bio    = (string)($a->bibPer ?? '');

                        $idsFilms = $acteurFilms[$id] ?? [];
                        $nbFilms  = is_array($idsFilms) ? count($idsFilms) : 0;

                        $searchText = strtolower(trim($nom.' '.$prenom.' '.$nat));
                    @endphp

                    <div class="table-row acteurRow" data-search="{{ $searchText }}">
                        <div class="td">{{ $nom }}</div>
                        <div class="td">{{ $prenom }}</div>
                        <div class="td">{{ $dat }}</div>
                        <div class="td">{{ $nat }}</div>
                        <div class="td">{{ $nbFilms }} film(s)</div>

                        <div class="td td-actions">
                            <div class="actions-group">
                                <button
                                    type="button"
                                    class="btn-action btn-edit js-edit-acteur"
                                    data-id="{{ $id }}"
                                    data-nom="{{ e($nom) }}"
                                    data-prenom="{{ e($prenom) }}"
                                    data-datnai="{{ e($dat) }}"
                                    data-nat="{{ e($nat) }}"
                                    data-bio="{{ e($bio) }}"
                                    data-films='@json($idsFilms)'
                                >
                                    Modifier
                                </button>

                                <form method="POST" action="{{ route('admin.acteurs.destroy', ['idPer' => $id]) }}"
                                      onsubmit="return confirm('Supprimer cet acteur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="admin-empty">
                        <p>Aucun acteur</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL AJOUT ACTEUR --}}
    <div class="modal" id="modal-acteur-add" style="display:none;">
        <div class="modal-content">
            <h3>Ajouter un acteur</h3>

            <form method="POST" action="{{ route('admin.acteurs.store') }}">
                @csrf

                <label>Nom</label>
                <input name="nomPer" type="text" required value="{{ old('nomPer') }}">

                <label>Prénom</label>
                <input name="prePer" type="text" required value="{{ old('prePer') }}">

                <label>Date de naissance</label>
                <input name="datNaiPer" type="date" value="{{ old('datNaiPer') }}">

                <label>Nationalité</label>
                <input name="natPer" type="text" value="{{ old('natPer') }}">

                <label>Biographie</label>
                <textarea name="bibPer" placeholder="Biographie...">{{ old('bibPer') }}</textarea>

                <label>Description</label>
                <textarea id="edit_bibPer" name="desPer" placeholder="Desc..."></textarea>

                <label>Films associés</label>
                <select name="films[]" multiple size="6">
                    @foreach($films ?? [] as $f)
                        <option value="{{ $f->idFil }}"
                            {{ collect(old('films', []))->contains((string)$f->idFil) ? 'selected' : '' }}>
                            {{ $f->nomFil }}
                        </option>
                    @endforeach
                </select>

                <div style="margin-top:6px; font-size:12px; color:#9aa0a6;">
                    CTRL pour sélectionner plusieurs films
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-acteur-add')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT ACTEUR --}}
    <div class="modal" id="modal-acteur-edit" style="display:none;">
        <div class="modal-content">
            <h3>Modifier un acteur</h3>

            <form id="acteurEditForm" method="POST" action="">
                @csrf
                @method('PUT')

                <label>Nom</label>
                <input id="edit_nomPer" name="nomPer" type="text" required>

                <label>Prénom</label>
                <input id="edit_prePer" name="prePer" type="text" required>

                <label>Date de naissance</label>
                <input id="edit_datNaiPer" name="datNaiPer" type="date">

                <label>Nationalité</label>
                <input id="edit_natPer" name="natPer" type="text">

                <label>Biographie</label>
                <textarea id="edit_bibPer" name="bibPer" placeholder="Biographie..."></textarea>

                <label>Description</label>
                <textarea id="edit_bibPer" name="desPer" placeholder="Desc..."></textarea>

                <label>Films associés</label>
                <select id="edit_films" name="films[]" multiple size="6">
                    @foreach($films ?? [] as $f)
                        <option value="{{ $f->idFil }}">{{ $f->nomFil }}</option>
                    @endforeach
                </select>

                <div style="margin-top:6px; font-size:12px; color:#9aa0a6;">
                    CTRL pour sélectionner plusieurs films
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-acteur-edit')">Annuler</button>
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
                const hay = (row.getAttribute('data-search') || '').toLowerCase();
                row.style.display = hay.includes(query) ? '' : 'none';
            });
        }

        document.querySelectorAll('.js-edit-acteur').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;

                document.getElementById('edit_nomPer').value = btn.dataset.nom || '';
                document.getElementById('edit_prePer').value = btn.dataset.prenom || '';
                document.getElementById('edit_datNaiPer').value = btn.dataset.datnai || '';
                document.getElementById('edit_natPer').value = btn.dataset.nat || '';
                document.getElementById('edit_bibPer').value = btn.dataset.bio || '';

                // sélectionner les films associés
                const films = JSON.parse(btn.dataset.films || '[]').map(Number);
                const select = document.getElementById('edit_films');
                Array.from(select.options).forEach(opt => {
                    opt.selected = films.includes(parseInt(opt.value, 10));
                });

                const form = document.getElementById('acteurEditForm');
                form.action = "{{ url('/admin/G_acteur') }}/" + id;

                openModal('modal-acteur-edit');
            });
        });

        @if($errors->any())
        // si validation KO, on ré-ouvre la modale d'ajout
        openModal('modal-acteur-add');
        @endif
    </script>
@endsection
