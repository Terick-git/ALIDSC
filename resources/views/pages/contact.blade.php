@extends('layouts.app')

@section('title', 'Contact')
@section('description', 'Contactez l\'ALIDSC à Maroua — adresse, téléphone, email et formulaire.')

@section('content')

{{-- EN-TÊTE --}}
<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Contact</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Écrivez-nous. <em>Parlons.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            Une question, un partenariat, une envie de vous engager, une demande d'information — nous vous répondons.
        </p>
    </div>
</section>

{{-- COORDONNÉES + FORMULAIRE --}}
<section style="padding: 4rem 0 10rem;">
    <div class="frame">
        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 6rem; align-items: start;">

            {{-- COORDONNÉES --}}
            <div>
                <div style="padding-bottom: 2rem; border-bottom: 1px solid var(--line); margin-bottom: 2rem;">
                    <span class="meta" style="display:block; margin-bottom: 1rem; color: var(--blue);">Siège social</span>
                    <p class="para">
                        Maroua
                        Extrême-Nord
                        Cameroun
                    </p>
                </div>

                <div style="padding-bottom: 2rem; border-bottom: 1px solid var(--line); margin-bottom: 2rem;">
                    <span class="meta" style="display:block; margin-bottom: 1rem; color: var(--blue);">Téléphone</span>
                    <p class="para">
                        <a href="tel:+237690856383" style="display:block;">690 856 383</a>
                        <a href="tel:+237696118592" style="display:block;">696 118 592</a>
                    </p>
                </div>

                <div style="padding-bottom: 2rem; border-bottom: 1px solid var(--line); margin-bottom: 2rem;">
                    <span class="meta" style="display:block; margin-bottom: 1rem; color: var(--blue);">Email</span>
                    <p class="para">
                        <a href="mailto:contact@alidsc.org">contact@alidsc.org</a>
                    </p>
                </div>

                <div>
                    <span class="meta" style="display:block; margin-bottom: 1rem; color: var(--blue);">WhatsApp</span>
                    <p class="para">
                        <a href="https://wa.me/237696506940" target="_blank">Discuter sur WhatsApp →</a>
                    </p>
                </div>
            </div>

            {{-- FORMULAIRE --}}
            <div>
                @if(session('success'))
                    <div style="padding: 1.25rem 1.5rem; background: #F0F5F0; border-left: 3px solid var(--green); margin-bottom: 2rem;">
                        <p style="color: var(--text); font-size: 0.9375rem;">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label for="nom" style="display:block; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.75rem;">Nom complet *</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                style="width: 100%; padding: 0.75rem 0; font-family: var(--body); font-size: 1rem; color: var(--text); background: transparent; border: 0; border-bottom: 1px solid var(--line); outline: none; transition: border-color 200ms;"
                                onfocus="this.style.borderColor='var(--blue)'"
                                onblur="this.style.borderColor='var(--line)'">
                            @error('nom') <span style="display:block; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--blue);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" style="display:block; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.75rem;">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                style="width: 100%; padding: 0.75rem 0; font-family: var(--body); font-size: 1rem; color: var(--text); background: transparent; border: 0; border-bottom: 1px solid var(--line); outline: none; transition: border-color 200ms;"
                                onfocus="this.style.borderColor='var(--blue)'"
                                onblur="this.style.borderColor='var(--line)'">
                            @error('email') <span style="display:block; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--blue);">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label for="telephone" style="display:block; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.75rem;">Téléphone</label>
                            <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                style="width: 100%; padding: 0.75rem 0; font-family: var(--body); font-size: 1rem; color: var(--text); background: transparent; border: 0; border-bottom: 1px solid var(--line); outline: none;"
                                onfocus="this.style.borderColor='var(--blue)'"
                                onblur="this.style.borderColor='var(--line)'">
                        </div>

                        <div>
                            <label for="sujet" style="display:block; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.75rem;">Sujet *</label>
                            <input type="text" id="sujet" name="sujet" value="{{ old('sujet') }}" required
                                style="width: 100%; padding: 0.75rem 0; font-family: var(--body); font-size: 1rem; color: var(--text); background: transparent; border: 0; border-bottom: 1px solid var(--line); outline: none;"
                                onfocus="this.style.borderColor='var(--blue)'"
                                onblur="this.style.borderColor='var(--line)'">
                            @error('sujet') <span style="display:block; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--blue);">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label for="contenu" style="display:block; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.75rem;">Votre message *</label>
                        <textarea id="contenu" name="contenu" rows="6" required
                            style="width: 100%; padding: 0.75rem 0; font-family: var(--body); font-size: 1rem; color: var(--text); background: transparent; border: 0; border-bottom: 1px solid var(--line); outline: none; resize: vertical; line-height: 1.6;"
                            onfocus="this.style.borderColor='var(--blue)'"
                            onblur="this.style.borderColor='var(--line)'">{{ old('contenu') }}</textarea>
                        @error('contenu') <span style="display:block; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--blue);">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="invitation-cta" style="cursor: pointer;">
                        Envoyer le message
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>

                    <p style="margin-top: 2rem; font-size: 0.8125rem; color: var(--text-muted);">
                        Vos données sont traitées de manière confidentielle. Consultez notre
                        <a href="{{ route('legal.confidentialite') }}" style="color: var(--blue); border-bottom: 1px solid var(--blue);">politique de confidentialité</a>.
                    </p>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection