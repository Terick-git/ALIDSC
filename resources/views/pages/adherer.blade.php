@extends('layouts.app')

@section('title', 'Devenir adhérent')
@section('description', 'Rejoignez l\'ALIDSC — Membre simple ou membre actif. Faites votre demande en ligne.')

@section('content')

<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Adhésion</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Rejoignez <em>le mouvement.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            Choisissez votre catégorie, remplissez le formulaire, joignez votre pièce d'identité. Un responsable vous contactera pour finaliser votre adhésion.
        </p>
    </div>
</section>

<section style="padding: 4rem 0 8rem;">
    <div class="frame">
        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 5rem; align-items: start;">

            {{-- Catégories --}}
            <div>
                <h2 class="display-3" style="margin-bottom: 2rem;">Catégories</h2>

                <div style="padding: 1.5rem 0; border-bottom: 1px solid var(--line);">
                    <span class="meta" style="color: var(--blue); display:block; margin-bottom:.5rem;">Membre simple</span>
                    <p class="para">Intérêt marqué pour la cause. Accès à certaines informations et participation aux activités. Cotisation annuelle.</p>
                </div>

                <div style="padding: 1.5rem 0; border-bottom: 1px solid var(--line);">
                    <span class="meta" style="color: var(--blue); display:block; margin-bottom:.5rem;">Membre actif</span>
                    <p class="para">Participation à plus de 50 % des activités. Droit de vote en AG, accès complet à l'espace membre.</p>
                </div>

                <div style="margin-top: 2rem; padding: 1rem 1.25rem; background: var(--blue-light); border-left: 3px solid var(--blue);">
                    <p style="font-size: .875rem; margin: 0; color: var(--text);">
                        <strong>Note :</strong> Toute adhésion est soumise à validation humaine par le Bureau Exécutif avant tout paiement.
                    </p>
                </div>
            </div>

            {{-- Formulaire --}}
            <div>
                @if(session('success'))
                    <div style="padding: 1.25rem 1.5rem; background: #F0F5F0; border-left: 3px solid var(--green); margin-bottom: 2rem;">
                        <p style="color: var(--text); margin:0;">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div style="padding: 1.25rem 1.5rem; background: #FDF2F2; border-left: 3px solid #C53030; margin-bottom: 2rem;">
                        <ul style="margin:0; padding-left: 1.2rem; color: #C53030;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('adherer.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Nom *</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Prénom *</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Date de naissance *</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Téléphone *</label>
                            <input type="text" name="telephone" value="{{ old('telephone') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="meta" style="display:block; margin-bottom:.75rem;">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Établissement / Profession *</label>
                            <input type="text" name="etablissement" value="{{ old('etablissement') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville') }}" style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="meta" style="display:block; margin-bottom:.75rem;">Adresse *</label>
                        <input type="text" name="adresse" value="{{ old('adresse') }}" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Catégorie souhaitée *</label>
                            <select name="categorie" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                                <option value="simple" @selected(old('categorie')=='simple')>Membre simple</option>
                                <option value="actif"  @selected(old('categorie')=='actif')>Membre actif</option>
                            </select>
                        </div>
                        <div>
                            <label class="meta" style="display:block; margin-bottom:.75rem;">Cotisation proposée (FCFA)</label>
                            <input type="number" name="cotisation_proposee" value="{{ old('cotisation_proposee') }}" min="0" max="1000000" style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="meta" style="display:block; margin-bottom:.75rem;">Pièce d'identité * (PDF, JPG, PNG — max 5 Mo)</label>
                        <input type="file" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png" required style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:.9375rem;">
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="meta" style="display:block; margin-bottom:.75rem;">Motivation</label>
                        <textarea name="motivation" rows="4" style="width:100%; padding:.75rem 0; border:0; border-bottom:1px solid var(--line); background:transparent; font-size:1rem; outline:none; resize:vertical;">{{ old('motivation') }}</textarea>
                    </div>

                    <button type="submit" class="invitation-cta" style="cursor:pointer; background:none; border:0; border-bottom:1px solid var(--text);">
                        Envoyer ma demande
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:18px; height:18px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection