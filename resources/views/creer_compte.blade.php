@extends('layout')

@section('title', 'Inscription - CineForAll')

@section('content')
    <div class="auth-container">
        <div class="auth-card auth-card-large">
            <h2 class="auth-title">CineForAll</h2>
            <p class="auth-subtitle">Créer un compte</p>

            {{-- Affichage des erreurs globales --}}
            @if ($errors->any())
                <div class="alert-error" style="margin-bottom:12px;">
                    <ul style="margin:0; padding-left:18px; color: red">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Message success si tu en renvoies un --}}
            @if (session('success'))
                <div class="alert-success" style="margin-bottom:12px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- IMPORTANT : action ne doit PAS être "#" --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
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
