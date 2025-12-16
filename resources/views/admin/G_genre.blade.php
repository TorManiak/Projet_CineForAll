@extends('layout')

@section('title', 'Admin - Genres')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Genres</h2>
            <button class="btn-add" onclick="document.getElementById('modal-genre').style.display='flex'">
                + Ajouter un genre
            </button>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Rechercher un genre...">
        </div>

        <div class="admin-table">
            <div class="table-header table-header-genre">
                <span>Nom du genre</span>
            </div>

            <div class="admin-empty">
                <div class="empty-icon">🎪</div>
                <p>Aucun genre</p>
                <span>Ajoutez votre premier genre de film</span>
            </div>
        </div>

    </div>

    {{-- MODAL AJOUT GENRE --}}
    <div class="modal" id="modal-genre">
        <div class="modal-content">
            <h3>Ajouter un genre</h3>

            <form>
                <label>Nom du genre</label>
                <input type="text" placeholder="Action, Drame, Comédie...">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel"
                            onclick="document.getElementById('modal-genre').style.display='none'">
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
