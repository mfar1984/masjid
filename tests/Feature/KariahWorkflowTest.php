<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kariah;
use App\Models\Masjid;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KariahWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $masjid;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create Super Admin user
        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'role_id' => 1, // Super Admin role
        ]);

        // Create test masjid
        $this->masjid = Masjid::factory()->create([
            'nama' => 'Test Masjid',
            'kod_masjid' => 'TEST001',
        ]);
    }

    /** @test */
    public function super_admin_can_approve_kariah()
    {
        // Create pending kariah
        $kariah = Kariah::factory()->create([
            'nama' => 'Test Member',
            'status' => 'Menunggu',
            'masjid_id' => $this->masjid->id,
        ]);

        // Act as Super Admin
        $response = $this->actingAs($this->superAdmin)
            ->post(route('kariah.approve', $kariah), [
                'catatan_kelulusan' => 'Approved by test'
            ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $kariah->refresh();
        $this->assertEquals('Aktif', $kariah->status);
        $this->assertEquals($this->superAdmin->id, $kariah->diluluskan_oleh);
        $this->assertNotNull($kariah->tarikh_diluluskan);
    }

    /** @test */
    public function super_admin_can_reject_kariah()
    {
        // Create pending kariah
        $kariah = Kariah::factory()->create([
            'nama' => 'Test Member',
            'status' => 'Menunggu',
            'masjid_id' => $this->masjid->id,
        ]);

        // Act as Super Admin
        $response = $this->actingAs($this->superAdmin)
            ->post(route('kariah.reject', $kariah), [
                'reason' => 'Test rejection reason'
            ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $kariah->refresh();
        $this->assertEquals('Ditolak', $kariah->status);
        $this->assertStringContains('Test rejection reason', $kariah->catatan_kelulusan);
    }

    /** @test */
    public function super_admin_can_suspend_kariah()
    {
        // Create active kariah
        $kariah = Kariah::factory()->create([
            'nama' => 'Test Member',
            'status' => 'Aktif',
            'masjid_id' => $this->masjid->id,
        ]);

        // Act as Super Admin
        $response = $this->actingAs($this->superAdmin)
            ->post(route('kariah.suspend', $kariah));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $kariah->refresh();
        $this->assertEquals('Digantung', $kariah->status);
        $this->assertEquals($this->superAdmin->id, $kariah->suspended_by);
        $this->assertNotNull($kariah->suspended_at);
    }

    /** @test */
    public function super_admin_can_reactivate_kariah()
    {
        // Create suspended kariah
        $kariah = Kariah::factory()->create([
            'nama' => 'Test Member',
            'status' => 'Digantung',
            'masjid_id' => $this->masjid->id,
            'suspended_at' => now(),
            'suspended_by' => $this->superAdmin->id,
        ]);

        // Act as Super Admin
        $response = $this->actingAs($this->superAdmin)
            ->post(route('kariah.reactivate', $kariah));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $kariah->refresh();
        $this->assertEquals('Aktif', $kariah->status);
        $this->assertNull($kariah->suspended_by);
        $this->assertNull($kariah->suspended_at);
    }

    /** @test */
    public function workflow_buttons_display_correctly_based_on_status()
    {
        // Create kariah with different statuses
        $pendingKariah = Kariah::factory()->create([
            'status' => 'Menunggu',
            'masjid_id' => $this->masjid->id,
        ]);

        $activeKariah = Kariah::factory()->create([
            'status' => 'Aktif',
            'masjid_id' => $this->masjid->id,
        ]);

        $suspendedKariah = Kariah::factory()->create([
            'status' => 'Digantung',
            'masjid_id' => $this->masjid->id,
        ]);

        // Visit kariah index page
        $response = $this->actingAs($this->superAdmin)
            ->get(route('kariah.index'));

        $response->assertStatus(200);
        
        // Check that action-icons component is used
        $response->assertSee('x-action-icons');
        
        // Check that modals are included
        $response->assertSee('x-approve-modal');
        $response->assertSee('x-reject-modal');
        $response->assertSee('x-suspend-modal');
        $response->assertSee('x-unsuspend-modal');
    }
}
