<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\Adherent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        Storage::fake('private');
        Storage::fake('public');
    }

    // ═══════════════════════════════════════════════════════
    // MESSAGES
    // ═══════════════════════════════════════════════════════

    public function test_bulk_supprime_plusieurs_messages(): void
    {
        $messages = Message::factory()->count(3)->create();
        $autre    = Message::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.messages.bulk'), [
            'action' => 'delete',
            'ids'    => $messages->pluck('id')->implode(','),
        ])->assertRedirect();

        $this->assertDatabaseMissing('messages', ['id' => $messages[0]->id]);
        $this->assertDatabaseMissing('messages', ['id' => $messages[1]->id]);
        $this->assertDatabaseMissing('messages', ['id' => $messages[2]->id]);
        $this->assertDatabaseHas('messages', ['id' => $autre->id]);
    }

    public function test_bulk_marque_lus(): void
    {
        $messages = Message::factory()->count(3)->create(['lu' => false]);

        $this->actingAs($this->admin)->post(route('admin.messages.bulk'), [
            'action' => 'mark_read',
            'ids'    => $messages->pluck('id')->implode(','),
        ]);

        foreach ($messages as $m) {
            $this->assertTrue($m->fresh()->lu);
            $this->assertNotNull($m->fresh()->lu_le);
        }
    }

    public function test_bulk_marque_non_lus(): void
    {
        $messages = Message::factory()->count(2)->lu()->create();

        $this->actingAs($this->admin)->post(route('admin.messages.bulk'), [
            'action' => 'mark_unread',
            'ids'    => $messages->pluck('id')->implode(','),
        ]);

        foreach ($messages as $m) {
            $this->assertFalse($m->fresh()->lu);
            $this->assertNull($m->fresh()->lu_le);
        }
    }

        public function test_bulk_avec_ids_vides_renvoie_une_erreur_de_validation(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.messages.bulk'), ['action' => 'delete', 'ids' => ''])
            ->assertSessionHasErrors('ids');
    }

    public function test_bulk_avec_ids_non_numeriques_renvoie_une_erreur(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.messages.bulk'), ['action' => 'delete', 'ids' => 'abc,def'])
            ->assertSessionHas('error');
    }

    public function test_bulk_refuse_action_inconnue(): void
    {
        $msg = Message::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('admin.messages.bulk'), ['action' => 'frobnicate', 'ids' => (string) $msg->id])
            ->assertSessionHasErrors('action');
    }

    // ═══════════════════════════════════════════════════════
    // ADHÉRENTS
    // ═══════════════════════════════════════════════════════

    public function test_bulk_supprime_plusieurs_adherents(): void
    {
        $adherents = Adherent::factory()->count(3)->create();
        $autre     = Adherent::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.adherents.bulk'), [
            'action' => 'delete',
            'ids'    => $adherents->pluck('id')->implode(','),
        ])->assertRedirect();

        $this->assertDatabaseCount('adherents', 1);
        $this->assertDatabaseHas('adherents', ['id' => $autre->id]);
    }

    public function test_bulk_export_renvoie_csv(): void
    {
        $adherents = Adherent::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)->post(route('admin.adherents.bulk'), [
            'action' => 'export',
            'ids'    => $adherents->pluck('id')->implode(','),
        ]);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    // ═══════════════════════════════════════════════════════
    // ACTUALITÉS
    // ═══════════════════════════════════════════════════════

    public function test_bulk_publie_plusieurs_actualites(): void
    {
        $actualites = Actualite::factory()->count(3)->create([
            'publie'    => false,
            'publie_le' => null,
        ]);

        $this->actingAs($this->admin)->post(route('admin.actualites.bulk'), [
            'action' => 'publish',
            'ids'    => $actualites->pluck('id')->implode(','),
        ]);

        foreach ($actualites as $a) {
            $this->assertTrue($a->fresh()->publie);
            $this->assertNotNull($a->fresh()->publie_le);
        }
    }

    public function test_bulk_depublie_plusieurs_actualites(): void
    {
        $actualites = Actualite::factory()->count(2)->create(['publie' => true]);

        $this->actingAs($this->admin)->post(route('admin.actualites.bulk'), [
            'action' => 'unpublish',
            'ids'    => $actualites->pluck('id')->implode(','),
        ]);

        foreach ($actualites as $a) {
            $this->assertFalse($a->fresh()->publie);
        }
    }

    public function test_bulk_supprime_plusieurs_actualites(): void
    {
        $actualites = Actualite::factory()->count(3)->create();

        $this->actingAs($this->admin)->post(route('admin.actualites.bulk'), [
            'action' => 'delete',
            'ids'    => $actualites->pluck('id')->implode(','),
        ]);

        $this->assertDatabaseCount('actualites', 0);
    }

    // ═══════════════════════════════════════════════════════
    // SÉCURITÉ
    // ═══════════════════════════════════════════════════════

    public function test_non_admin_ne_peut_pas_faire_de_bulk(): void
    {
        $user = User::factory()->create(['role' => 'membre']);
        $msg  = Message::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.messages.bulk'), ['action' => 'delete', 'ids' => (string) $msg->id])
            ->assertForbidden();

        $this->assertDatabaseHas('messages', ['id' => $msg->id]);
    }

    public function test_visiteur_ne_peut_pas_faire_de_bulk(): void
    {
        $msg = Message::factory()->create();

        $this->post(route('admin.messages.bulk'), ['action' => 'delete', 'ids' => (string) $msg->id])
            ->assertRedirect(route('login'));
    }
}
