@extends('layout')

@section('title', 'Admin - Acteurs')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Acteurs</h2>
            <button class="btn-add" onclick="openModal('modal-acteur-add')">+ Ajouter un acteur</button>
        </div>

        <div class="admin-filters">
            <input id="searchActeur" type="text" placeholder="Rechercher un acteur..." oninput="filterRows('acteurRow', this.value)">
        </div>

        <div class="admin-table">
            <div class="table-header">
                <span>Photo</span>
                <span>Nom</span>
                <span>Prénom</span>
                <span>Date de naissance</span>
                <span>Nationalité</span>
                <span style="text-align:right;">Actions</span>
            </div>

            @if(isset($acteurs) && count($acteurs) > 0)
                @foreach($acteurs as $a)
                    <div class="table-row acteurRow" data-search="{{ strtolower($a->nomPer.' '.$a->prePer.' '.$a->natPer) }}">
                        <span>—</span>
                        <span>{{ $a->nomPer }}</span>
                        <span>{{ $a->prePer }}</span>
                        <span>{{ $a->datNaiPer ? \Carbon\Carbon::parse($a->datNaiPer)->format('d/m/Y') : '' }}</span>
                        <span>{{ $a->natPer }}</span>

                        <span style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
                        <button class="btn-small" type="button"
                                onclick="openEditActeur(
                                {{ $a->idPer }},
                                @js($a->nomPer),
                                @js($a->prePer),
                                @js($a->datNaiPer),
                                @js($a->natPer),
                                @js($a->bibPer),
                                @js($a->desPer),
                                @js($acteurFilms[$a->idPer] ?? [])
                            )">
                            Modifier
                        </button>

                        <form method="POST" action="{{ route('admin.acteurs.destroy', $a->idPer) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn-small btn-danger" type="submit" onclick="return confirm('Supprimer cet acteur ?')">
                                Supprimer
                            </button>
                        </form>
                    </span>
                    </div>
                @endforeach
            @else
                <div class="admin-empty">
                    <div class="empty-icon">🎭</div>
                    <p>Aucun acteur</p>
                    <span>Ajoutez votre premier acteur au catalogue</span>
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL AJOUT ACTEUR --}}
    <div class="modal" id="modal-acteur-add">
        <div class="modal-content modal-large">
            <h3>Ajouter un acteur</h3>

            <form method="POST" action="{{ route('admin.acteurs.store') }}">
                @csrf

                <label>Nom</label>
                <input type="text" name="nomPer" required>

                <label>Prénom</label>
                <input type="text" name="prePer" required>

                <label>Date de naissance</label>
                <input type="date" name="datNaiPer">

                <label>Nationalité</label>
                <input type="text" name="natPer" placeholder="Française">

                <label>Biographie</label>
                <textarea name="bibPer" placeholder="Biographie..."></textarea>

                <label>Description</label>
                <textarea name="desPer" placeholder="Description..."></textarea>

                <label>Films associés</label>
                <select name="films[]" multiple>
                    @if(isset($films))
                        @foreach($films as $f)
                            <option value="{{ $f->idFil }}">{{ $f->nomFil }}</option>
                        @endforeach
                    @endif
                </select>
                <small>CTRL pour sélectionner plusieurs films</small>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-acteur-add')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT ACTEUR --}}
    <div class="modal" id="modal-acteur-edit">
        <div class="modal-content modal-large">
            <h3>Modifier un acteur</h3>

            <form id="formActeurEdit" method="POST">
                @csrf
                @method('PUT')

                <label>Nom</label>
                <input id="editNomPer" type="text" name="nomPer" required>

                <label>Prénom</label>
                <input id="editPrePer" type="text" name="prePer" required>

                <label>Date de naissance</label>
                <input id="editDatNaiPer" type="date" name="datNaiPer">

                <label>Nationalité</label>
                <input id="editNatPer" type="text" name="natPer">

                <label>Biographie</label>
                <textarea id="editBibPer" name="bibPer"></textarea>

                <label>Description</label>
                <textarea id="editDesPer" name="desPer"></textarea>

                <label>Films associés</label>
                <select id="editFilms" name="films[]" multiple>
                    @if(isset($films))
                        @foreach($films as $f)
                            <option value="{{ $f->idFil }}">{{ $f->nomFil }}</option>
                        @endforeach
                    @endif
                </select>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-acteur-edit')">Annuler</button>
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

        function openEditActeur(idPer, nomPer, prePer, datNaiPer, natPer, bibPer, desPer, filmIds){
            document.getElementById('editNomPer').value = nomPer || '';
            document.getElementById('editPrePer').value = prePer || '';
            document.getElementById('editDatNaiPer').value = (datNaiPer || '').substring(0,10);
            document.getElementById('editNatPer').value = natPer || '';
            document.getElementById('editBibPer').value = bibPer || '';
            document.getElementById('editDesPer').value = desPer || '';

            const select = document.getElementById('editFilms');
            const setIds = new Set((filmIds || []).map(Number));
            Array.from(select.options).forEach(opt => opt.selected = setIds.has(Number(opt.value)));

            document.getElementById('formActeurEdit').action = `/admin/G_acteur/${idPer}`;
            openModal('modal-acteur-edit');
        }
    </script>
@endsection
