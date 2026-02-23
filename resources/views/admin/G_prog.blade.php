@extends('layout')

@section('title', 'Admin - Programmation')

@section('content')
    <div class="admin-container">

        <div class="admin-header">
            <h2>Programmation</h2>
            <button class="btn-add" onclick="document.getElementById('modal-programmation').style.display='flex'">
                + Ajouter une séance
            </button>
        </div>

        <div class="admin-filters admin-programmation-filters">
            <div class="date-selector">
                <button>&lt;</button>
                <span>Jeudi 27 novembre 2025</span>
                <button>&gt;</button>
            </div>

            <select>
                <option>Tous les cinémas</option>
            </select>
        </div>

        <div class="admin-empty">
            <div class="empty-icon">📅</div>
            <p>Aucune séance</p>
            <span>Aucune séance programmée pour cette date</span>
        </div>

    </div>

    {{-- MODAL AJOUT SÉANCE --}}
    <div class="modal" id="modal-programmation">
        <div class="modal-content modal-large">
            <h3>Ajouter une séance</h3>

            <form>
                <label>Film</label>
                <select>
                    <option>Choisir un film</option>
                    <option>Inception</option>
                    <option>Interstellar</option>
                </select>

                <label>Cinéma</label>
                <select>
                    <option>Choisir un cinéma</option>
                </select>

                <label>Salle</label>
                <select>
                    <option>Choisir une salle</option>
                </select>

                <label>Date</label>
                <input type="date">

                <label>Heure</label>
                <input type="time">

                <label>Langue</label>
                <select>
                    <option>VF</option>
                    <option>VOSTFR</option>
                </select>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel"
                            onclick="document.getElementById('modal-programmation').style.display='none'">
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
