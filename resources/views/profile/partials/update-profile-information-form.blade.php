<form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <label for="name" class="block text-sm font-medium mb-1">Nom</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
               class="w-full px-3 py-2 border rounded">
        @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-1">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
               class="w-full px-3 py-2 border rounded">
        @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-3 bg-amber-50 border-l-4 border-amber-400 text-sm text-amber-900">
                Votre email n'est pas vérifié.
                <form method="POST" action="{{ route('verification.send') }}" class="inline">
                    @csrf
                    <button class="underline hover:no-underline">Cliquez ici pour renvoyer l'email</button>
                </form>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">
            Enregistrer
        </button>
        @if (session('status') === 'profile-updated')
            <span class="text-sm text-green-600">✓ Enregistré</span>
        @endif
    </div>
</form>