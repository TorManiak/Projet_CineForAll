@extends('layout')

@section('title', 'Admin - Cinémas')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Cinémas</h2>
            <button class="btn-add" onclick="document.getElementById('modal-cinema').style.display='flex'">
                + Ajouter un cinéma
            </button>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Rechercher un cinéma...">
            <select>
                <option>Toutes les villes</option>
            </select>
        </div>

        <div class="admin-table">
            <div class="table-header table-header-cinema">
                <span>Nom</span>
                <span>Adresse</span>
                <span>Ville</span>
            </div>

            <div class="admin-empty">
                <div class="empty-icon">🏢</div>
                <p>Aucun cinéma</p>
                <span>Ajoutez votre premier cinéma</span>
            </div>
        </div>

    </div>

    {{-- MODAL AJOUT CINÉMA --}}
    <div class="modal" id="modal-cinema">
        <div class="modal-content">
            <h3>Ajouter un cinéma</h3>

            <form>
                <label>Nom du cinéma</label>
                <input type="text">

                <label>Adresse</label>
                <input type="text">

                <label>Ville</label>
                <input type="text">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel"
                            onclick="document.getElementById('modal-cinema').style.display='none'">
                        Annuler
                    </button>
                    <button type="submit" class="btn-confirm">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
