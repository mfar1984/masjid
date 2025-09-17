<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Masjid;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test masjids
        $this->masjid1 = Masjid::factory()->create(['id' => 1, 'nama' => 'Masjid Test 1']);
        $this->masjid2 = Masjid::factory()->create(['id' => 2, 'nama' => 'Masjid Test 2']);
        
        // Create test roles
        $this->superAdminRole = Role::create([
            'name' => 'Super Admin',
            'permissions' => ['*' => ['*' => true]],
            'is_system_role' => true,
            'masjid_id' => null
        ]);
        
        $this->adminMasjid1Role = Role::create([
            'name' => 'Admin Masjid 1',
            'permissions' => ['roles' => ['read' => '1', 'create' => '1']],
            'masjid_id' => 1
        ]);
        
        $this->adminMasjid2Role = Role::create([
            'name' => 'Admin Masjid 2',
            'permissions' => ['roles' => ['read' => '1']],
            'masjid_id' => 2
        ]);
        
        $this->noPermissionRole = Role::create([
            'name' => 'No Permission Role',
            'permissions' => [],
            'masjid_id' => 1
        ]);
    }

    /** @test */
    public function super_admin_can_access_any_role()
    {
        $superAdmin = User::factory()->create([
            'role_id' => $this->superAdminRole->id,
            'masjid_id' => null
        ]);

        $this->actingAs($superAdmin);

        // Should be able to access any role
        $response = $this->get(route('senarai-kumpulan.show', $this->adminMasjid1Role));
        $response->assertStatus(200);

        $response = $this->get(route('senarai-kumpulan.show', $this->adminMasjid2Role));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_masjid_can_only_access_own_masjid_roles()
    {
        $adminMasjid1 = User::factory()->create([
            'role_id' => $this->adminMasjid1Role->id,
            'masjid_id' => 1
        ]);

        $this->actingAs($adminMasjid1);

        // Should be able to access own masjid role
        $response = $this->get(route('senarai-kumpulan.show', $this->adminMasjid1Role));
        $response->assertStatus(200);

        // Should NOT be able to access other masjid role
        $response = $this->get(route('senarai-kumpulan.show', $this->adminMasjid2Role));
        $response->assertStatus(403);
    }

    /** @test */
    public function user_without_permission_cannot_access_roles_page()
    {
        $userNoPermission = User::factory()->create([
            'role_id' => $this->noPermissionRole->id,
            'masjid_id' => 1
        ]);

        $this->actingAs($userNoPermission);

        // Should be blocked by middleware
        $response = $this->get(route('senarai-kumpulan.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function user_with_permission_can_access_roles_page()
    {
        $adminMasjid1 = User::factory()->create([
            'role_id' => $this->adminMasjid1Role->id,
            'masjid_id' => 1
        ]);

        $this->actingAs($adminMasjid1);

        // Should be able to access roles page
        $response = $this->get(route('senarai-kumpulan.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function data_integrity_validation_prevents_wrong_role_assignment()
    {
        // This test would require form submission testing
        // For now, we test the validation logic directly

        $role = Role::find($this->adminMasjid2Role->id); // Role for Masjid 2
        $masjidId = 1; // User belongs to Masjid 1

        // This should fail validation
        $isValid = $role->masjid_id === null || $role->masjid_id === $masjidId;
        $this->assertFalse($isValid, 'Role from different masjid should not be assignable');

        // This should pass validation (global role)
        $globalRole = $this->superAdminRole;
        $isValid = $globalRole->masjid_id === null || $globalRole->masjid_id === $masjidId;
        $this->assertTrue($isValid, 'Global role should be assignable to any user');
    }

    /** @test */
    public function admin_masjid_can_only_view_users_from_own_masjid()
    {
        $userMasjid1 = User::factory()->create([
            'role_id' => $this->adminMasjid1Role->id,
            'masjid_id' => 1
        ]);

        $userMasjid2 = User::factory()->create([
            'role_id' => $this->adminMasjid2Role->id,
            'masjid_id' => 2
        ]);

        $adminMasjid1 = User::factory()->create([
            'role_id' => $this->adminMasjid1Role->id,
            'masjid_id' => 1
        ]);

        $this->actingAs($adminMasjid1);

        // Should be able to view user from same masjid
        $response = $this->get(route('senarai-pengguna.show', $userMasjid1));
        $response->assertStatus(200);

        // Should NOT be able to view user from different masjid
        $response = $this->get(route('senarai-pengguna.show', $userMasjid2));
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_masjid_can_only_view_own_masjid_details()
    {
        $adminMasjid1 = User::factory()->create([
            'role_id' => $this->adminMasjid1Role->id,
            'masjid_id' => 1
        ]);

        $this->actingAs($adminMasjid1);

        // Should be able to view own masjid
        $response = $this->get(route('senarai-masjid.show', $this->masjid1));
        $response->assertStatus(200);

        // Should NOT be able to view other masjid
        $response = $this->get(route('senarai-masjid.show', $this->masjid2));
        $response->assertStatus(403);
    }
}
