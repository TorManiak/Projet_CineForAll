@extends('layout')

@section('title', 'Connexion - CineForAll')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">CineForAll</h2>
            <p class="auth-subtitle">Connexion</p>

            <form method="POST" action="#">
                @csrf
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

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-auth">
                    Se connecter
                </button>
            </form>

            <a href="/créer_compte" class="auth-link">Créer un compte</a>
        </div>
    </div>
@endsection
