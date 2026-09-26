<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label for="current_password" class="block text-sm font-medium mb-1">Mot de passe actuel</label>
        <input id="current_password" type="password" name="current_password" autocomplete="current-password"
               class="w-full px-3 py-2 border rounded">
        @error('current_password', 'updatePassword')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1">Nouveau mot de passe</label>
        <input id="password" type="password" name="password" autocomplete="new-password"
               class="w-full px-3 py-2 border rounded">
        @error('password', 'updatePassword')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmer</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
               class="w-full px-3 py-2 border rounded">
        @error('password_confirmation', 'updatePassword')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">
            Modifier le mot de passe
        </button>
        @if (session('status') === 'password-updated')
            <span class="text-sm text-green-600">✓ Modifié</span>
        @endif
    </div>
</form>