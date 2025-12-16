@extends('layout')

@section('title', 'Admin - Films')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Films</h2>
            <button class="btn-add" onclick="document.getElementById('modal').style.display='flex'">
                + Ajouter un film
            </button>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Rechercher un film...">
            <select>
                <option>Toutes les années</option>
                <option>2025</option>
                <option>2024</option>
                <option>2023</option>
                <option>2022</option>
            </select>
            <select>
                <option>Tous les statuts</option>
                <option>En salle</option>
                <option>Pas en salle</option>
            </select>
        </div>

        <div class="admin-empty">
            <div class="empty-icon">🎬</div>
            <p>Aucun film</p>
            <span>Commencez par ajouter votre premier film au catalogue</span>
        </div>

    </div>

    {{-- MODAL --}}
    <div class="modal" id="modal">
        <div class="modal-content">
            <h3>Ajouter un film</h3>

            <form>
                <label>Titre du film</label>
                <input type="text">

                <label>Année</label>
                <input type="number">

                <label>Genres (séparés par des virgules)</label>
                <input type="text" placeholder="Action, Drame, Sci-fi">

                <label>Statut</label>
                <input type="text">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel"
                            onclick="document.getElementById('modal').style.display='none'">
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
