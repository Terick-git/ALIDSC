@extends('layouts.admin')

@section('title', 'Modifier — '.$adherent->nom_complet)

@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Modifier l'adhérent</h1>
    <p class="text-sm text-gray-500">
        {{ $adherent->nom_complet }} · Inscrit le {{ $adherent->created_at->format('d/m/Y') }}
    </p>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.adherents.update', $adherent) }}"
      class="space-y-6 max-w-4xl">
    @csrf
    @method('PUT')

    {{-- Identité --}}
    <div class="bg-white p-6 rounded border space-y-4">
        <h2 class="font-display text-lg text-[#10263B] border-b pb-2">Identité</h2>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Nom *</label>
                <input type="text" name="nom" value="{{ old('nom', $adherent->nom) }}" required
                       class="w-full px-3 py-2 border rounded">
                @error('nom')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Prénom *</label>
                <input type="text" name="prenom" value="{{ old('prenom', $adherent->prenom) }}" required
                       class="w-full px-3 py-2 border rounded">
                @error('prenom')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Date de naissance</label>
                <input type="date" name="date_naissance"
                       value="{{ old('date_naissance', $adherent->date_naissance?->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Catégorie *</label>
                <select name="categorie" required class="w-full px-3 py-2 border rounded">
                    <option value="simple" @selected(old('categorie', $adherent->categorie)==='simple')>Membre simple</option>
                    <option value="actif"  @selected(old('categorie', $adherent->categorie)==='actif')>Membre actif</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="bg-white p-6 rounded border space-y-4">
        <h2 class="font-display text-lg text-[#10263B] border-b pb-2">Contact</h2>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $adherent->email) }}" required
                       class="w-full px-3 py-2 border rounded">
                @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Téléphone *</label>
                <input type="text" name="telephone" value="{{ old('telephone', $adherent->telephone) }}" required
                       class="w-full px-3 py-2 border rounded">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Ville</label>
                <input type="text" name="ville" value="{{ old('ville', $adherent->ville) }}"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Profession</label>
                <input type="text" name="profession" value="{{ old('profession', $adherent->profession) }}"
                       class="w-full px-3 py-2 border rounded">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Adresse</label>
            <input type="text" name="adresse" value="{{ old('adresse', $adherent->adresse) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Établissement</label>
            <input type="text" name="etablissement" value="{{ old('etablissement', $adherent->etablissement) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    {{-- Cotisation --}}
    <div class="bg-white p-6 rounded border space-y-4">
        <h2 class="font-display text-lg text-[#10263B] border-b pb-2">Cotisation</h2>

        <div class="grid grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Cotisation proposée (FCFA)</label>
                <input type="number" name="cotisation_proposee" min="0"
                       value="{{ old('cotisation_proposee', $adherent->cotisation_proposee) }}"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Montant retenu (FCFA)</label>
                <input type="number" name="cotisation_montant" min="0"
                       value="{{ old('cotisation_montant', $adherent->cotisation_montant) }}"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Statut cotisation *</label>
                <select name="statut_cotisation" required class="w-full px-3 py-2 border rounded">
                    @foreach(['en_attente' => 'En attente', 'a_jour' => 'À jour', 'expire' => 'Expiré'] as $k => $v)
                        <option value="{{ $k }}" @selected(old('statut_cotisation', $adherent->statut_cotisation)===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Date dernier paiement</label>
                <input type="date" name="date_dernier_paiement"
                       value="{{ old('date_dernier_paiement', $adherent->date_dernier_paiement?->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mode de paiement</label>
                <select name="mode_paiement" class="w-full px-3 py-2 border rounded">
                    <option value="">—</option>
                    @foreach(['mobile_money' => 'Mobile Money', 'especes' => 'Espèces', 'virement' => 'Virement', 'autre' => 'Autre'] as $k => $v)
                        <option value="{{ $k }}" @selected(old('mode_paiement', $adherent->mode_paiement)===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Statut demande *</label>
                <select name="statut_demande" required class="w-full px-3 py-2 border rounded">
                    @foreach(\App\Models\Adherent::statutsDemande() as $k => $v)
                        <option value="{{ $k }}" @selected(old('statut_demande', $adherent->statut_demande)===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Motif de refus (si refusé)</label>
            <textarea name="motif_refus" rows="2" maxlength="2000"
                      class="w-full px-3 py-2 border rounded">{{ old('motif_refus', $adherent->motif_refus) }}</textarea>
        </div>
    </div>

    {{-- Notes internes --}}
    <div class="bg-white p-6 rounded border space-y-4">
        <h2 class="font-display text-lg text-[#10263B] border-b pb-2">Notes internes</h2>
        <textarea name="notes" rows="3" maxlength="2000"
                  class="w-full px-3 py-2 border rounded"
                  placeholder="Notes visibles uniquement par l'administration...">{{ old('notes', $adherent->notes) }}</textarea>
    </div>

    {{-- Options --}}
    <div class="bg-white p-6 rounded border space-y-3">
        <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" name="actif" value="1" @checked(old('actif', $adherent->actif)) class="rounded">
            Compte actif (peut se connecter à l'espace adhérent)
        </label>

        @if($adherent->piece_identite)
            <div class="pt-2 border-t">
                <a href="{{ route('admin.adherents.piece', $adherent) }}"
                   class="text-sm text-blue-600 hover:underline">
                    📎 Télécharger la pièce d'identité
                </a>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">
            Enregistrer
        </button>
        <a href="{{ route('admin.adherents.show', $adherent) }}" class="px-5 py-2 border rounded text-sm">
            Annuler
        </a>

        <form method="POST" action="{{ route('admin.adherents.destroy', $adherent) }}"
              onsubmit="return confirm('Supprimer définitivement cet adhérent ?')" class="ml-auto">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:underline">
                Supprimer l'adhérent
            </button>
        </form>
    </div>
</form>

@endsection