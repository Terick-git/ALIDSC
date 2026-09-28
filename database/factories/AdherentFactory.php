<?php

namespace Database\Factories;

use App\Models\Adherent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Adherent>
 */
class AdherentFactory extends Factory
{
    protected $model = Adherent::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->date('Y-m-d', '-18 years'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'telephone' => '6' . fake()->numerify('########'),
            'ville' => 'Maroua',
            'adresse' => fake()->streetAddress(),
            'etablissement' => fake()->company(),
            'categorie' => 'simple',
            'cotisation_proposee' => null,
            'date_adhesion' => now(),
            'cotisation_montant' => 5000,
            'statut_cotisation' => 'en_attente',
            'statut_demande' => 'en_attente',
            'motif_refus' => null,
            'piece_identite' => null,
            'date_dernier_paiement' => null,
            'mode_paiement' => null,
            'notes' => null,
            'actif' => false,
            'espace_actif' => false,
            'email_verified_at' => null,
            'derniere_connexion' => null,
            'remember_token' => null,
        ];
    }

    /**
     * Adhérent avec une demande en attente de validation.
     */
    public function enAttente(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut_demande' => 'en_attente',
            'espace_actif' => false,
            'actif' => false,
        ]);
    }

    /**
     * Adhérent accepté mais pas encore activé.
     */
    public function accepte(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut_demande' => 'acceptee',
            'statut_cotisation' => 'en_attente',
            'espace_actif' => false,
            'actif' => false,
        ]);
    }

    /**
     * Adhérent actif avec accès à l'espace membre.
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut_demande' => 'active',
            'statut_cotisation' => 'a_jour',
            'espace_actif' => true,
            'actif' => true,
            'date_dernier_paiement' => now(),
            'mode_paiement' => 'mobile_money',
        ]);
    }

    /**
     * Adhérent refusé avec un motif.
     */
    public function refuse(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut_demande' => 'refuse',
            'motif_refus' => 'Pièce d\'identité non lisible.',
            'espace_actif' => false,
            'actif' => false,
        ]);
    }

    /**
     * Adhérent dont la cotisation est expirée.
     */
    public function expire(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut_demande' => 'expiree',
            'statut_cotisation' => 'expire',
            'espace_actif' => false,
            'actif' => false,
            'date_dernier_paiement' => now()->subYears(2),
        ]);
    }

    /**
     * Adhérent "membre actif" (catégorie).
     */
    public function membreActif(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'actif',
        ]);
    }
}