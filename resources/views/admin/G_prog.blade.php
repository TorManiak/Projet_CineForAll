@extends('layout')

@section('title', 'Admin - Programmation')

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
            <h2>Programmation</h2>
            <button class="btn-add" type="button" onclick="openModal('modal-programmation-add')">
                + Ajouter une séance
            </button>
        </div>

        <div class="admin-filters admin-programmation-filters">
            <div class="date-selector">
                <a class="btn-action" href="{{ route('admin.prog', ['date' => $prevDate, 'cinema' => $cinema]) }}">&lt;</a>

                <span>
                {{ \Carbon\Carbon::parse($date)->locale('fr')->translatedFormat('l j F Y') }}
            </span>

                <a class="btn-action" href="{{ route('admin.prog', ['date' => $nextDate, 'cinema' => $cinema]) }}">&gt;</a>
            </div>

            <form method="GET" action="{{ route('admin.prog') }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <select name="cinema" onchange="this.form.submit()">
                    <option value="all" {{ $cinema === 'all' ? 'selected' : '' }}>Tous les cinémas</option>
                    @foreach($cinemas ?? [] as $c)
                        <option value="{{ $c->idCin }}" {{ (string)$cinema === (string)$c->idCin ? 'selected' : '' }}>
                            {{ $c->nomCin }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(($seances ?? collect())->count() === 0)
            <div class="admin-empty">
                <div class="empty-icon">📅</div>
                <p>Aucune séance</p>
                <span>Aucune séance programmée pour cette date</span>
            </div>
        @else
            <div class="admin-table">
                <div class="table-header">
                    <div class="th">Heure</div>
                    <div class="th">Film</div>
                    <div class="th">Cinéma</div>
                    <div class="th">Salle</div>
                    <div class="th">Langue</div>
                    <div class="th">Prix</div>
                    <div class="th">Actions</div>
                </div>

                <div class="table-body">
                    @foreach($seances as $s)
                        @php
                            $dt = $s->datHeuSea ? \Carbon\Carbon::parse($s->datHeuSea) : null;
                            $rowDate = $dt ? $dt->toDateString() : $date;
                            $rowTime = $dt ? $dt->format('H:i') : '19:00';
                        @endphp

                        <div class="table-row">
                            <div class="td">{{ $rowTime }}</div>
                            <div class="td">{{ $s->film_title }}</div>
                            <div class="td">{{ $s->cinema_name ?? '-' }}</div>
                            <div class="td">{{ $s->salle_name ?? 'Aucune salle' }}</div>
                            <div class="td">{{ $s->langue_name ?? '-' }}</div>
                            <div class="td">{{ number_format((float)$s->priSea, 2, ',', ' ') }} €</div>

                            <div class="td">
                                <button
                                    type="button"
                                    class="btn-action btn-edit js-edit-acteur"
                                    onclick="openEditModal({
                                    idSea: {{ (int)$s->idSea }},
                                    idFil: {{ (int)$s->idFil }},
                                    idCin: {{ (int)$s->idCin }},
                                    idSal: {{ $s->idSal ? (int)$s->idSal : 'null' }},
                                    idLan: {{ $s->idLan ? (int)$s->idLan : 'null' }},
                                    date: '{{ $rowDate }}',
                                    time: '{{ $rowTime }}',
                                    priSea: '{{ (string)$s->priSea }}'
                                })"
                                >
                                    Modifier
                                </button>

                                <form method="POST" action="{{ route('admin.prog.destroy', ['idSea' => $s->idSea]) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Supprimer cette séance ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- MODAL AJOUT --}}
    <div class="modal" id="modal-programmation-add" style="display:none;">
        <div class="modal-content modal-large">
            <h3>Ajouter une séance</h3>

            <form method="POST" action="{{ route('admin.prog.store') }}">
                @csrf

                <label>Film</label>
                <select name="idFil" required>
                    <option value="">Choisir un film</option>
                    @foreach($films ?? [] as $f)
                        <option value="{{ $f->idFil }}" {{ old('idFil') == $f->idFil ? 'selected' : '' }}>
                            {{ $f->nomFil }}
                        </option>
                    @endforeach
                </select>

                <label>Cinéma</label>
                <select name="idCin" id="add_idCin" required onchange="refreshSalles('add_idCin','add_idSal', null)">
                    <option value="">Choisir un cinéma</option>
                    @foreach($cinemas ?? [] as $c)
                        <option value="{{ $c->idCin }}" {{ old('idCin') == $c->idCin ? 'selected' : '' }}>
                            {{ $c->nomCin }}
                        </option>
                    @endforeach
                </select>

                <label>Salle</label>
                <select name="idSal" id="add_idSal">
                    <option value="">Aucune salle</option>
                </select>

                <label>Date</label>
                <input type="date" name="date" required value="{{ old('date', $date) }}">

                <label>Heure</label>
                <input type="time" name="time" required value="{{ old('time', '19:00') }}">

                <label>Langue</label>
                <select name="idLan" id="add_idLan">
                    <option value="">-</option>
                    @foreach($langues ?? [] as $l)
                        <option value="{{ $l->idLan }}" {{ old('idLan') == $l->idLan ? 'selected' : '' }}>
                            {{ $l->langue }}
                        </option>
                    @endforeach
                </select>

                <label>Prix</label>
                <input type="number" step="0.01" min="0" name="priSea" required value="{{ old('priSea', '13.90') }}">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-programmation-add')">
                        Annuler
                    </button>
                    <button type="submit" class="btn-confirm">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal" id="modal-programmation-edit" style="display:none;">
        <div class="modal-content modal-large">
            <h3>Modifier une séance</h3>

            <form method="POST" id="editForm" action="">
                @csrf
                @method('PUT')

                <label>Film</label>
                <select name="idFil" id="edit_idFil" required>
                    <option value="">Choisir un film</option>
                    @foreach($films ?? [] as $f)
                        <option value="{{ $f->idFil }}">{{ $f->nomFil }}</option>
                    @endforeach
                </select>

                <label>Cinéma</label>
                <select name="idCin" id="edit_idCin" required onchange="refreshSalles('edit_idCin','edit_idSal', null)">
                    <option value="">Choisir un cinéma</option>
                    @foreach($cinemas ?? [] as $c)
                        <option value="{{ $c->idCin }}">{{ $c->nomCin }}</option>
                    @endforeach
                </select>

                <label>Salle</label>
                <select name="idSal" id="edit_idSal">
                    <option value="">Aucune salle</option>
                </select>

                <label>Date</label>
                <input type="date" name="date" id="edit_date" required>

                <label>Heure</label>
                <input type="time" name="time" id="edit_time" required>

                <label>Langue</label>
                <select name="idLan" id="edit_idLan">
                    <option value="">-</option>
                    @foreach($langues ?? [] as $l)
                        <option value="{{ $l->idLan }}">{{ $l->langue }}</option>
                    @endforeach
                </select>

                <label>Prix</label>
                <input type="number" step="0.01" min="0" name="priSea" id="edit_priSea" required>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-programmation-edit')">
                        Annuler
                    </button>
                    <button type="submit" class="btn-confirm">
                        Enregistrer
                    </button>
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

        async function refreshSalles(cinemaSelectId, salleSelectId, selectedSalleId) {
            const cinSel = document.getElementById(cinemaSelectId);
            const salSel = document.getElementById(salleSelectId);
            if (!cinSel || !salSel) return;

            const idCin = cinSel.value;

            // reset
            salSel.innerHTML = '';
            const optNone = document.createElement('option');
            optNone.value = '';
            optNone.textContent = 'Aucune salle';
            salSel.appendChild(optNone);

            if (!idCin) return;

            try {
                const url = "{{ route('admin.prog.salles') }}" + "?cinema=" + encodeURIComponent(idCin);
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                (data || []).forEach(row => {
                    const opt = document.createElement('option');
                    opt.value = row.idSal;
                    opt.textContent = row.nomSal;
                    salSel.appendChild(opt);
                });

                if (selectedSalleId) {
                    salSel.value = String(selectedSalleId);
                }
            } catch (e) {
                // silencieux
            }
        }

        function openEditModal(payload) {
            // action du form
            const form = document.getElementById('editForm');
            form.action = "{{ url('/admin/G_prog') }}/" + payload.idSea;

            // fill
            document.getElementById('edit_idFil').value = String(payload.idFil);
            document.getElementById('edit_idCin').value = String(payload.idCin);
            document.getElementById('edit_date').value = payload.date;
            document.getElementById('edit_time').value = payload.time;
            document.getElementById('edit_priSea').value = String(payload.priSea);

            const lan = document.getElementById('edit_idLan');
            if (lan) lan.value = payload.idLan ? String(payload.idLan) : '';

            // refresh salles selon cinéma + sélection salle
            refreshSalles('edit_idCin', 'edit_idSal', payload.idSal);

            openModal('modal-programmation-edit');
        }

        // Si validation errors => ré-ouvre modal ajout et recharge salles si un cinéma était sélectionné
        @if($errors->any())
        openModal('modal-programmation-add');
        setTimeout(() => {
            const idCin = document.getElementById('add_idCin')?.value;
            const selectedSal = "{{ old('idSal') }}";
            if (idCin) refreshSalles('add_idCin', 'add_idSal', selectedSal ? selectedSal : null);
        }, 50);
        @endif

        // init modal ajout: si cinéma pré-rempli (old)
        setTimeout(() => {
            const idCin = document.getElementById('add_idCin')?.value;
            const selectedSal = "{{ old('idSal') }}";
            if (idCin) refreshSalles('add_idCin', 'add_idSal', selectedSal ? selectedSal : null);
        }, 50);
    </script>
@endsection
