<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Masjid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test roles
        $this->superAdminRole = Role::create([
            'name' => 'Super Admin',
            'permissions' => [
                'users' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
                'roles' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
                'masjids' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true, 'approve' => true],
            ]
        ]);
        
        $this->adminMasjidRole = Role::create([
            'name' => 'Admin Masjid',
            'permissions' => [
                'users' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
                'roles' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
            ]
        ]);

        // Create test masjid
        $this->testMasjid = Masjid::create([
            'nama' => 'Test Masjid',
            'alamat' => 'Test Address',
            'negeri' => 'Test State',
            'daerah' => 'Test District',
            'poskod' => '12345',
            'status' => 'active'
        ]);

        // Create test users
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'phone' => '0123456789',
            'password' => bcrypt('password'),
            'role_id' => $this->superAdminRole->id,
            'email_verified_at' => now()
        ]);

        $this->adminMasjid = User::create([
            'name' => 'Admin Masjid',
            'email' => 'admin@test.com',
            'phone' => '0123456788',
            'password' => bcrypt('password'),
            'role_id' => $this->adminMasjidRole->id,
            'masjid_id' => $this->testMasjid->id,
            'email_verified_at' => now()
        ]);

        $this->unverifiedUser = User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@test.com',
            'phone' => '0123456787',
            'password' => bcrypt('password'),
            'role_id' => $this->adminMasjidRole->id,
            'masjid_id' => $this->testMasjid->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function user_can_be_verified_manually()
    {
        $this->assertFalse($this->unverifiedUser->hasVerifiedEmail());
        
        $result = $this->unverifiedUser->markEmailAsVerified();
        
        $this->assertTrue($result);
        $this->assertTrue($this->unverifiedUser->fresh()->hasVerifiedEmail());
        $this->assertNotNull($this->unverifiedUser->fresh()->email_verified_at);
    }

    /** @test */
    public function user_can_be_unverified_manually()
    {
        // First verify the user
        $this->unverifiedUser->markEmailAsVerified();
        $this->assertTrue($this->unverifiedUser->fresh()->hasVerifiedEmail());
        
        // Then unverify
        $this->unverifiedUser->update(['email_verified_at' => null]);
        
        $this->assertFalse($this->unverifiedUser->fresh()->hasVerifiedEmail());
        $this->assertNull($this->unverifiedUser->fresh()->email_verified_at);
    }

    /** @test */
    public function super_admin_can_verify_any_user()
    {
        $this->actingAs($this->superAdmin);
        
        $response = $this->post(route('senarai-pengguna.verify', $this->unverifiedUser));
        
        $response->assertRedirect(route('senarai-pengguna.index'));
        $response->assertSessionHas('success');
        $this->assertTrue($this->unverifiedUser->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function admin_masjid_can_verify_users_from_same_masjid()
    {
        $this->actingAs($this->adminMasjid);
        
        $response = $this->post(route('senarai-pengguna.verify', $this->unverifiedUser));
        
        $response->assertRedirect(route('senarai-pengguna.index'));
        $response->assertSessionHas('success');
        $this->assertTrue($this->unverifiedUser->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function admin_masjid_cannot_verify_users_from_different_masjid()
    {
        // Create another masjid and user
        $otherMasjid = Masjid::create([
            'nama' => 'Other Masjid',
            'alamat' => 'Other Address',
            'negeri' => 'Other State',
            'daerah' => 'Other District',
            'poskod' => '54321',
            'status' => 'active'
        ]);

        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@test.com',
            'phone' => '0123456786',
            'password' => bcrypt('password'),
            'role_id' => $this->adminMasjidRole->id,
            'masjid_id' => $otherMasjid->id,
            'email_verified_at' => null
        ]);

        $this->actingAs($this->adminMasjid);
        
        $response = $this->post(route('senarai-pengguna.verify', $otherUser));
        
        $response->assertStatus(403);
        $this->assertFalse($otherUser->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function cannot_verify_already_verified_user()
    {
        // Verify user first
        $this->unverifiedUser->markEmailAsVerified();
        
        $this->actingAs($this->superAdmin);
        
        $response = $this->post(route('senarai-pengguna.verify', $this->unverifiedUser));
        
        $response->assertRedirect(route('senarai-pengguna.index'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function super_admin_can_unverify_any_user()
    {
        // Verify user first
        $this->unverifiedUser->markEmailAsVerified();
        
        $this->actingAs($this->superAdmin);
        
        $response = $this->post(route('senarai-pengguna.unverify', $this->unverifiedUser));
        
        $response->assertRedirect(route('senarai-pengguna.index'));
        $response->assertSessionHas('success');
        $this->assertFalse($this->unverifiedUser->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function user_cannot_unverify_themselves()
    {
        $this->actingAs($this->superAdmin);
        
        $response = $this->post(route('senarai-pengguna.unverify', $this->superAdmin));
        
        $response->assertRedirect(route('senarai-pengguna.index'));
        $response->assertSessionHas('error');
        $this->assertTrue($this->superAdmin->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function cannot_unverify_already_unverified_user()
    {
        $this->actingAs($this->superAdmin);
        
        $response = $this->post(route('senarai-pengguna.unverify', $this->unverifiedUser));
        
        $response->assertRedirect(route('senarai-pengguna.index'));
        $response->assertSessionHas('error');
    }
}
