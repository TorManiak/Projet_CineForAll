@extends('layout')

@section('title', 'Admin - Cinémas')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Cinémas</h2>
            <button class="btn-add" onclick="openModal('modal-cinema-add')">+ Ajouter un cinéma</button>
        </div>

        <div class="admin-filters">
            <input id="searchCinema" type="text" placeholder="Rechercher un cinéma..." oninput="filterRows('cinemaRow', this.value)">
            <select id="cityFilter" onchange="filterCinemaCity()">
                <option value="">Toutes les villes</option>
                @if(isset($cinemas))
                    @foreach(collect($cinemas)->pluck('vilCin')->unique() as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="admin-table">
            <div class="table-header table-header-cinema">
                <span>Nom</span>
                <span>Adresse</span>
                <span>Ville</span>
                <span style="text-align:right;">Actions</span>
            </div>

            @if(isset($cinemas) && count($cinemas) > 0)
                @foreach($cinemas as $c)
                    <div class="table-row cinemaRow"
                         data-search="{{ strtolower($c->nomCin.' '.$c->adrCin.' '.$c->vilCin) }}"
                         data-city="{{ $c->vilCin }}">
                        <span>{{ $c->nomCin }}</span>
                        <span>{{ $c->adrCin }}</span>
                        <span>{{ $c->vilCin }}</span>

                        <span style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
                        <button class="btn-small" type="button"
                                onclick="openEditCinema(
                                {{ $c->idCin }},
                                @js($c->nomCin),
                                @js($c->adrCin),
                                @js($c->vilCin),
                                @js($c->cpCin),
                                @js($c->maiCin),
                                @js($c->telCin)
                            )">
                            Modifier
                        </button>

                        <form method="POST" action="{{ route('admin.cinemas.destroy', $c->idCin) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn-small btn-danger" type="submit" onclick="return confirm('Supprimer ce cinéma ?')">
                                Supprimer
                            </button>
                        </form>
                    </span>
                    </div>
                @endforeach
            @else
                <div class="admin-empty">
                    <div class="empty-icon">🏢</div>
                    <p>Aucun cinéma</p>
                    <span>Ajoutez votre premier cinéma</span>
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL AJOUT CINÉMA --}}
    <div class="modal" id="modal-cinema-add">
        <div class="modal-content">
            <h3>Ajouter un cinéma</h3>

            <form method="POST" action="{{ route('admin.cinemas.store') }}">
                @csrf

                <label>Nom du cinéma</label>
                <input type="text" name="nomCin" required>

                <label>Adresse</label>
                <input type="text" name="adrCin">

                <label>Ville</label>
                <input type="text" name="vilCin" required>

                <label>Code postal</label>
                <input type="text" name="cpCin">

                <label>Email</label>
                <input type="email" name="maiCin">

                <label>Téléphone</label>
                <input type="text" name="telCin">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-cinema-add')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT CINÉMA --}}
    <div class="modal" id="modal-cinema-edit">
        <div class="modal-content">
            <h3>Modifier un cinéma</h3>

            <form id="formCinemaEdit" method="POST">
                @csrf
                @method('PUT')

                <label>Nom du cinéma</label>
                <input id="editNomCin" type="text" name="nomCin" required>

                <label>Adresse</label>
                <input id="editAdrCin" type="text" name="adrCin">

                <label>Ville</label>
                <input id="editVilCin" type="text" name="vilCin" required>

                <label>Code postal</label>
                <input id="editCpCin" type="text" name="cpCin">

                <label>Email</label>
                <input id="editMaiCin" type="email" name="maiCin">

                <label>Téléphone</label>
                <input id="editTelCin" type="text" name="telCin">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-cinema-edit')">Annuler</button>
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

        function filterCinemaCity(){
            const city = document.getElementById('cityFilter').value;
            document.querySelectorAll('.cinemaRow').forEach(row => {
                const ok = !city || row.getAttribute('data-city') === city;
                if (ok) {
                    // keep search filter applied too
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            // re-apply text search
            filterRows('cinemaRow', document.getElementById('searchCinema').value);
        }

        function openEditCinema(idCin, nomCin, adrCin, vilCin, cpCin, maiCin, telCin){
            document.getElementById('editNomCin').value = nomCin || '';
            document.getElementById('editAdrCin').value = adrCin || '';
            document.getElementById('editVilCin').value = vilCin || '';
            document.getElementById('editCpCin').value = cpCin || '';
            document.getElementById('editMaiCin').value = maiCin || '';
            document.getElementById('editTelCin').value = telCin || '';

            document.getElementById('formCinemaEdit').action = `/admin/G_cine_salle/${idCin}`;
            openModal('modal-cinema-edit');
        }
    </script>
@endsection
