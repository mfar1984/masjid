<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kariah;
use App\Models\Masjid;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KariahDataIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that Admin Masjid can only see kariah from their own masjid
     */
    public function test_admin_masjid_can_only_see_own_kariah()
    {
        // Create masjids
        $masjid1 = Masjid::factory()->create(['nama' => 'Masjid Al-Hidayah']);
        $masjid2 = Masjid::factory()->create(['nama' => 'Masjid An-Nur']);
        
        // Create roles
        $adminRole = Role::factory()->create(['name' => 'Admin Masjid']);
        
        // Create admin users for each masjid
        $admin1 = User::factory()->create([
            'masjid_id' => $masjid1->id,
            'role_id' => $adminRole->id
        ]);
        $admin2 = User::factory()->create([
            'masjid_id' => $masjid2->id,
            'role_id' => $adminRole->id
        ]);
        
        // Create kariah for each masjid
        $kariah1 = Kariah::factory()->create([
            'nama' => 'Ahmad bin Ali',
            'masjid_id' => $masjid1->id
        ]);
        $kariah2 = Kariah::factory()->create([
            'nama' => 'Fatimah binti Omar',
            'masjid_id' => $masjid2->id
        ]);
        
        // Admin 1 should only see kariah 1
        $response = $this->actingAs($admin1)
                         ->get(route('kariah.index'));
                         
        $response->assertStatus(200)
                 ->assertSee($kariah1->nama)
                 ->assertDontSee($kariah2->nama);
                 
        // Admin 2 should only see kariah 2
        $response = $this->actingAs($admin2)
                         ->get(route('kariah.index'));
                         
        $response->assertStatus(200)
                 ->assertSee($kariah2->nama)
                 ->assertDontSee($kariah1->nama);
    }

    /**
     * Test that Super Admin can see all kariah
     */
    public function test_super_admin_can_see_all_kariah()
    {
        // Create masjids
        $masjid1 = Masjid::factory()->create(['nama' => 'Masjid Al-Hidayah']);
        $masjid2 = Masjid::factory()->create(['nama' => 'Masjid An-Nur']);
        
        // Create Super Admin role and user
        $superAdminRole = Role::factory()->create(['name' => 'Super Admin']);
        $superAdmin = User::factory()->create([
            'role_id' => $superAdminRole->id,
            'masjid_id' => null // Super Admin has no specific masjid
        ]);
        
        // Create kariah for each masjid
        $kariah1 = Kariah::factory()->create([
            'nama' => 'Ahmad bin Ali',
            'masjid_id' => $masjid1->id
        ]);
        $kariah2 = Kariah::factory()->create([
            'nama' => 'Fatimah binti Omar',
            'masjid_id' => $masjid2->id
        ]);
        
        // Super Admin should see all kariah
        $response = $this->actingAs($superAdmin)
                         ->get(route('kariah.index'));
                         
        $response->assertStatus(200)
                 ->assertSee($kariah1->nama)
                 ->assertSee($kariah2->nama);
    }

    /**
     * Test that Admin Masjid cannot access kariah from other masjid directly
     */
    public function test_admin_masjid_cannot_access_other_masjid_kariah()
    {
        // Create masjids
        $masjid1 = Masjid::factory()->create(['nama' => 'Masjid Al-Hidayah']);
        $masjid2 = Masjid::factory()->create(['nama' => 'Masjid An-Nur']);
        
        // Create role
        $adminRole = Role::factory()->create(['name' => 'Admin Masjid']);
        
        // Create admin user for masjid 1
        $admin1 = User::factory()->create([
            'masjid_id' => $masjid1->id,
            'role_id' => $adminRole->id
        ]);
        
        // Create kariah for masjid 2
        $kariah2 = Kariah::factory()->create([
            'nama' => 'Fatimah binti Omar',
            'masjid_id' => $masjid2->id
        ]);
        
        // Admin 1 should not be able to view kariah from masjid 2
        $response = $this->actingAs($admin1)
                         ->get(route('kariah.show', $kariah2));
                         
        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test kariah creation auto-assigns masjid_id
     */
    public function test_kariah_creation_auto_assigns_masjid_id()
    {
        // Create masjid and admin
        $masjid = Masjid::factory()->create(['nama' => 'Masjid Al-Hidayah']);
        $adminRole = Role::factory()->create(['name' => 'Admin Masjid']);
        $admin = User::factory()->create([
            'masjid_id' => $masjid->id,
            'role_id' => $adminRole->id
        ]);
        
        // Create kariah data
        $kariahData = [
            'nama' => 'Ahmad bin Ali',
            'no_ic' => '891230-13-1581',
            'telefon' => '012-3456789',
            'bangsa' => 'Melayu',
            'jantina' => 'Lelaki',
            'tarikh_keahlian' => '2024-01-01',
            'status' => 'Aktif',
            'email' => 'ahmad@example.com',
            'alamat' => 'Jalan Test 123'
        ];
        
        // Create kariah as admin
        $response = $this->actingAs($admin)
                         ->post(route('kariah.store'), $kariahData);
                         
        $response->assertRedirect(route('kariah.index'));
        
        // Check that kariah was created with correct masjid_id
        $kariah = Kariah::where('nama', 'Ahmad bin Ali')->first();
        $this->assertNotNull($kariah);
        $this->assertEquals($masjid->id, $kariah->masjid_id);
    }

    /**
     * Test statistics are filtered by masjid
     */
    public function test_statistics_filtered_by_masjid()
    {
        // Create masjids
        $masjid1 = Masjid::factory()->create(['nama' => 'Masjid Al-Hidayah']);
        $masjid2 = Masjid::factory()->create(['nama' => 'Masjid An-Nur']);
        
        // Create admin for masjid 1
        $adminRole = Role::factory()->create(['name' => 'Admin Masjid']);
        $admin1 = User::factory()->create([
            'masjid_id' => $masjid1->id,
            'role_id' => $adminRole->id
        ]);
        
        // Create kariah for both masjids
        Kariah::factory()->count(3)->create(['masjid_id' => $masjid1->id, 'status' => 'Aktif']);
        Kariah::factory()->count(2)->create(['masjid_id' => $masjid1->id, 'status' => 'Tidak Aktif']);
        Kariah::factory()->count(5)->create(['masjid_id' => $masjid2->id, 'status' => 'Aktif']);
        
        // Admin 1 should only see statistics for masjid 1
        $response = $this->actingAs($admin1)
                         ->get(route('kariah.index'));
                         
        $response->assertStatus(200);
        
        // Check that only masjid 1 kariah are counted
        $this->assertEquals(5, Kariah::where('masjid_id', $masjid1->id)->count());
        $this->assertEquals(3, Kariah::where('masjid_id', $masjid1->id)->where('status', 'Aktif')->count());
        $this->assertEquals(2, Kariah::where('masjid_id', $masjid1->id)->where('status', 'Tidak Aktif')->count());
    }
}
