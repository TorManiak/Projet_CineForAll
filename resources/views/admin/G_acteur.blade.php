@extends('layout')

@section('title', 'Admin - Acteurs')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Acteurs</h2>
            <button class="btn-add" onclick="document.getElementById('modal-acteur').style.display='flex'">
                + Ajouter un acteur
            </button>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Rechercher un acteur...">
        </div>

        <div class="admin-table">
            <div class="table-header">
                <span>Photo</span>
                <span>Nom</span>
                <span>Prénom</span>
                <span>Date de naissance</span>
                <span>Nationalité</span>
            </div>

            <div class="admin-empty">
                <div class="empty-icon">🎭</div>
                <p>Aucun acteur</p>
                <span>Ajoutez votre premier acteur au catalogue</span>
            </div>
        </div>

    </div>

    {{-- MODAL AJOUT ACTEUR --}}
    <div class="modal" id="modal-acteur">
        <div class="modal-content">
            <h3>Ajouter un acteur</h3>

            <form>
                <label>Nom</label>
                <input type="text">

                <label>Prénom</label>
                <input type="text">

                <label>Date de naissance</label>
                <input type="date">

                <label>Nationalité</label>
                <input type="text" placeholder="Française">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel"
                            onclick="document.getElementById('modal-acteur').style.display='none'">
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
