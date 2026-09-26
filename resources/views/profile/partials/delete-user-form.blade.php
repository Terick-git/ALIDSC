<p class="text-sm text-gray-600 mb-4">
    Une fois votre compte supprimé, toutes vos données seront définitivement effacées. Cette action est irréversible.
</p>

<button type="button"
        onclick="document.getElementById('deleteUserForm').classList.toggle('hidden')"
        class="px-5 py-2 bg-red-600 text-white rounded text-sm font-medium">
    Supprimer mon compte
</button>

<div id="deleteUserForm" class="hidden mt-4 p-4 bg-red-50 rounded">
    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-3">
        @csrf
        @method('DELETE')

        <div>
            <label for="delete_password" class="block text-sm font-medium mb-1">
                Confirmez votre mot de passe pour continuer
            </label>
            <input id="delete_password" type="password" name="password" placeholder="Mot de passe"
                   class="w-full px-3 py-2 border rounded">
            @error('password', 'userDeletion')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded text-sm font-medium">
                Confirmer la suppression
            </button>
            <button type="button"
                    onclick="document.getElementById('deleteUserForm').classList.add('hidden')"
                    class="px-5 py-2 border rounded text-sm">
                Annuler
            </button>
        </div>
    </form>
</div>