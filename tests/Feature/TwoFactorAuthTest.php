<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected TwoFactorService $twoFactor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'admin']);
        $this->twoFactor = app(TwoFactorService::class);
    }

    public function test_page_setup_2fa_est_accessible_quand_non_active(): void
    {
        $this->actingAs($this->user)
            ->get(route('2fa.setup'))
            ->assertOk()
            ->assertSee('Activer la 2FA');
    }

    public function test_activation_2fa_avec_code_valide(): void
    {
        // Génère un secret et confirme directement
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->save();

        // Obtient le code TOTP actuel en utilisant la même méthode que le service
        $code = $this->generateTotpCode($secret);

        $response = $this->actingAs($this->user)
            ->post(route('2fa.setup.enable'), ['code' => $code]);

        $response->assertRedirect(route('2fa.recovery-codes'));

        $this->user->refresh();
        $this->assertTrue($this->user->hasTwoFactorEnabled());
        $this->assertNotNull($this->user->two_factor_confirmed_at);
        $this->assertCount(8, $this->user->getTwoFactorRecoveryCodes());
    }

    public function test_activation_2fa_avec_code_invalide(): void
    {
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->save();

        $this->actingAs($this->user)
            ->post(route('2fa.setup.enable'), ['code' => '000000'])
            ->assertSessionHasErrors('code');

        $this->user->refresh();
        $this->assertFalse($this->user->hasTwoFactorEnabled());
    }

    public function test_challenge_2fa_redirige_apres_login(): void
    {
        // Active 2FA
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->two_factor_confirmed_at = now();
        $this->user->save();

        // Accès au dashboard sans session 2FA → redirection
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertRedirect(route('2fa.challenge'));
    }

    public function test_challenge_2fa_avec_code_valide_donne_acces(): void
    {
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->two_factor_confirmed_at = now();
        $this->user->save();

        $code = $this->generateTotpCode($secret);

        $this->actingAs($this->user)
            ->post(route('2fa.challenge.verify'), ['code' => $code])
            ->assertRedirect(route('dashboard'));

        // La session est marquée vérifiée
        $this->assertEquals(
            $this->user->id,
            session('2fa_verified_user_id')
        );
    }

    public function test_challenge_2fa_avec_code_invalide_rejette(): void
    {
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->two_factor_confirmed_at = now();
        $this->user->save();

        $this->actingAs($this->user)
            ->post(route('2fa.challenge.verify'), ['code' => '000000'])
            ->assertSessionHasErrors('code');
    }

    public function test_code_de_secours_utilisable_une_fois(): void
    {
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->two_factor_confirmed_at = now();
        $this->user->setTwoFactorRecoveryCodes(['ABCD-1234', 'EFGH-5678']);
        $this->user->save();

        // Utilise le premier code
        $this->actingAs($this->user)
            ->post(route('2fa.challenge.verify'), ['code' => 'ABCD-1234'])
            ->assertRedirect(route('dashboard'));

        $this->user->refresh();
        $codes = $this->user->getTwoFactorRecoveryCodes();
        $this->assertNotContains('ABCD-1234', $codes);
        $this->assertContains('EFGH-5678', $codes);
    }

    public function test_desactivation_2fa_avec_bon_mot_de_passe(): void
    {
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->two_factor_confirmed_at = now();
        $this->user->save();

        $this->actingAs($this->user)
            ->post(route('2fa.disable'), ['password' => 'password'])
            ->assertRedirect(route('profile.edit'));

        $this->user->refresh();
        $this->assertFalse($this->user->hasTwoFactorEnabled());
        $this->assertNull($this->user->two_factor_secret);
    }

    public function test_desactivation_2fa_avec_mauvais_mot_de_passe(): void
    {
        $secret = $this->twoFactor->generateSecret();
        $this->user->setTwoFactorSecret($secret);
        $this->user->two_factor_confirmed_at = now();
        $this->user->save();

        $this->actingAs($this->user)
            ->post(route('2fa.disable'), ['password' => 'mauvais'])
            ->assertSessionHasErrors('password');

        $this->user->refresh();
        $this->assertTrue($this->user->hasTwoFactorEnabled());
    }

    /**
     * Génère un code TOTP pour un secret donné, en réutilisant
     * la même logique que TwoFactorService (RFC 6238).
     */
    protected function generateTotpCode(string $secret): string
    {
        // Reflection pour accéder à la méthode protégée generateCode
        $reflection = new \ReflectionClass($this->twoFactor);
        $method = $reflection->getMethod('generateCode');
        $method->setAccessible(true);

        return $method->invoke($this->twoFactor, $secret, time());
    }
}
