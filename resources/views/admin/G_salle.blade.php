@extends('layout')

@section('title', 'Admin - Salles')

@section('admin_header')
@endsection

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
            <h2>Salles - {{ $cinema->nomCin }}</h2>

            <div class="admin-header-actions">
                <a href="{{route('admin.cinemas.store') }}" class="btn-action btn-edit">Retour</a>
                <button type="button" class="btn-add" onclick="openModal('modal-add-salle')">+ Ajouter une salle</button>
            </div>
        </div>

        <div class="admin-table">
            <div class="table-header">
                <div class="th">Nom</div>
                <div class="th">Places</div>
                <div class="th">Type</div>
                <div class="th th-actions">Actions</div>
            </div>

            <div class="table-body">
                @forelse($salles as $salle)
                    <div class="table-row">
                        <div class="td">{{ $salle->nomSal }}</div>
                        <div class="td">{{ $salle->nbSie }}</div>
                        <div class="td">{{ $salle->typSal }}</div>

                        <div class="td td-actions">
                            <div class="actions-group">
                                <button
                                    type="button"
                                    class="btn-action btn-edit"
                                    data-id="{{ $salle->idSal }}"
                                    data-nom="{{ e($salle->nomSal) }}"
                                    data-places="{{ $salle->nbSie }}"
                                    data-type="{{ e($salle->typSal) }}"
                                    onclick="openEditSalle(this)"
                                >
                                    Modifier
                                </button>

                                <form method="POST"
                                      action="{{ route('admin.salles.destroy', ['idSal' => $salle->idSal]) }}"
                                      onsubmit="return confirm('Supprimer cette salle ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="admin-empty">
                        <p>Aucune salle trouvée.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL AJOUT --}}
    <div class="modal" id="modal-add-salle" style="display:none;">
        <div class="modal-content modal-content-salle">
            <h3>Ajouter une salle</h3>

            <form method="POST" action="{{ route('admin.salles.store', ['idCin' => $cinema->idCin]) }}">
                @csrf

                <label>Nom de la salle</label>
                <input
                    type="text"
                    name="nomSal"
                    value="{{ old('nomSal') }}"
                    placeholder="Ex : Salle 1"
                    required
                    maxlength="50"
                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                    title="Seulement des lettres, chiffres, espaces et tirets"
                >

                <label>Nombre de places</label>
                <input
                    type="number"
                    name="nbSie"
                    min="1"
                    max="500"
                    step="1"
                    value="{{ old('nbSie') }}"
                    placeholder="Ex : 120"
                    required
                    inputmode="numeric"
                >

                <label>Type de salle</label>
                <select name="typSal" required>
                    <option value="">Choisir un type</option>
                    @foreach($typesSalle as $type)
                        <option value="{{ $type }}" {{ old('typSal') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-add-salle')">Annuler</button>
                    <button type="submit" class="btn-confirm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL MODIFICATION --}}
    <div class="modal" id="modal-edit-salle" style="display:none;">
        <div class="modal-content modal-content-salle">
            <h3>Modifier une salle</h3>

            <form id="editSalleForm" method="POST" action="">
                @csrf
                @method('PUT')

                <label>Nom de la salle</label>
                <input
                    type="text"
                    id="edit_nomSal"
                    name="nomSal"
                    placeholder="Ex : Salle 1"
                    required
                    maxlength="50"
                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                    title="Seulement des lettres, chiffres, espaces et tirets"
                >

                <label>Nombre de places</label>
                <input
                    type="number"
                    id="edit_nbSie"
                    name="nbSie"
                    min="1"
                    max="500"
                    step="1"
                    placeholder="Ex : 120"
                    required
                    inputmode="numeric"
                >

                <label>Type de salle</label>
                <select id="edit_typSal" name="typSal" required>
                    <option value="">Choisir un type</option>
                    @foreach($typesSalle as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-edit-salle')">Annuler</button>
                    <button type="submit" class="btn-confirm">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = 'flex';
            }
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = 'none';
            }
        }

        function sanitizeSalleName(value) {
            return value.replace(/[^A-Za-z0-9À-ÿ\s\-]/g, '');
        }

        function sanitizeNumbersOnly(value) {
            return value.replace(/[^0-9]/g, '');
        }

        function openEditSalle(button) {
            const idSal = button.dataset.id;
            const nomSal = button.dataset.nom || '';
            const nbSie = button.dataset.places || '';
            const typSal = button.dataset.type || '';

            document.getElementById('edit_nomSal').value = nomSal;
            document.getElementById('edit_nbSie').value = nbSie;
            document.getElementById('edit_typSal').value = typSal;

            document.getElementById('editSalleForm').action = "{{ url('/admin/salles') }}/" + idSal;

            openModal('modal-edit-salle');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const roomNameInputs = document.querySelectorAll('input[name="nomSal"], #edit_nomSal');

            roomNameInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    this.value = sanitizeSalleName(this.value);
                });
            });

            const numberInputs = document.querySelectorAll('input[name="nbSie"], #edit_nbSie');

            numberInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    this.value = sanitizeNumbersOnly(this.value);

                    if (this.value !== '') {
                        const numericValue = parseInt(this.value, 10);

                        if (numericValue > 500) {
                            this.value = 500;
                        }

                        if (numericValue < 1) {
                            this.value = 1;
                        }
                    }
                });
            });
        });

        window.addEventListener('click', function (e) {
            const addModal = document.getElementById('modal-add-salle');
            const editModal = document.getElementById('modal-edit-salle');

            if (e.target === addModal) {
                closeModal('modal-add-salle');
            }

            if (e.target === editModal) {
                closeModal('modal-edit-salle');
            }
        });

        @if($errors->any())
        openModal('modal-add-salle');
        @endif
    </script>
@endsection
