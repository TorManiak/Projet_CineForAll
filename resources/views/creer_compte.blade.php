@extends('layout')

@section('title', 'Inscription - CineForAll')

@section('content')
    <div class="auth-container">
        <div class="auth-card auth-card-large">
            <h2 class="auth-title">CineForAll</h2>
            <p class="auth-subtitle">Créer un compte</p>

            <form method="POST" action="#">
                @csrf

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-auth">
                    S’inscrire
                </button>
            </form>

            <p class="auth-footer">
                Déjà un compte ?
                <a href="{{ url('/connexion') }}">Connexion</a>
            </p>
        </div>
    </div>
@endsection
