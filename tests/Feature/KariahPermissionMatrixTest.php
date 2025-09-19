<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Masjid;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KariahPermissionMatrixTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that kariah module appears in permission matrix
     */
    public function test_kariah_module_appears_in_permission_matrix()
    {
        // Create Super Admin
        $superAdminRole = Role::factory()->create(['name' => 'Super Admin']);
        $superAdmin = User::factory()->create([
            'role_id' => $superAdminRole->id,
            'masjid_id' => null
        ]);

        // Access role create page
        $response = $this->actingAs($superAdmin)
                         ->get(route('senarai-kumpulan.create'));

        $response->assertStatus(200)
                 ->assertSee('Ahli Kariah') // Module name
                 ->assertSee('Tambah') // Create action
                 ->assertSee('Lihat') // Read action
                 ->assertSee('Kemaskini') // Update action
                 ->assertSee('Padam') // Delete action
                 ->assertSee('Terima') // Approve action
                 ->assertSee('Tolak') // Reject action
                 ->assertSee('Gantung') // Suspend action
                 ->assertSee('Aktifkan'); // Reactivate action
    }

    /**
     * Test that kariah has all 8 checkbox actions
     */
    public function test_kariah_has_all_eight_checkbox_actions()
    {
        // Create Super Admin
        $superAdminRole = Role::factory()->create(['name' => 'Super Admin']);
        $superAdmin = User::factory()->create([
            'role_id' => $superAdminRole->id,
            'masjid_id' => null
        ]);

        // Access role create page
        $response = $this->actingAs($superAdmin)
                         ->get(route('senarai-kumpulan.create'));

        $response->assertStatus(200);

        // Check that all 8 kariah permission checkboxes exist
        $expectedCheckboxes = [
            'permissions[kariah][create]',
            'permissions[kariah][read]',
            'permissions[kariah][update]',
            'permissions[kariah][delete]',
            'permissions[kariah][approve]',
            'permissions[kariah][reject]',
            'permissions[kariah][suspend]',
            'permissions[kariah][reactivate]',
        ];

        foreach ($expectedCheckboxes as $checkboxName) {
            $response->assertSee('name="' . $checkboxName . '"', false);
        }
    }

    /**
     * Test creating role with kariah permissions
     */
    public function test_create_role_with_kariah_permissions()
    {
        // Create Super Admin
        $superAdminRole = Role::factory()->create(['name' => 'Super Admin']);
        $superAdmin = User::factory()->create([
            'role_id' => $superAdminRole->id,
            'masjid_id' => null
        ]);

        // Create role with kariah permissions
        $roleData = [
            'name' => 'Kariah Manager',
            'description' => 'Manages kariah members',
            'permissions' => [
                'kariah' => [
                    'create' => '1',
                    'read' => '1',
                    'update' => '1',
                    'delete' => '1',
                    'approve' => '1',
                    'reject' => '1',
                    'suspend' => '1',
                    'reactivate' => '1',
                ]
            ]
        ];

        $response = $this->actingAs($superAdmin)
                         ->post(route('senarai-kumpulan.store'), $roleData);

        $response->assertRedirect(route('senarai-kumpulan.index'));

        // Verify role was created with correct permissions
        $role = Role::where('name', 'Kariah Manager')->first();
        $this->assertNotNull($role);
        $this->assertEquals('1', $role->permissions['kariah']['create']);
        $this->assertEquals('1', $role->permissions['kariah']['read']);
        $this->assertEquals('1', $role->permissions['kariah']['update']);
        $this->assertEquals('1', $role->permissions['kariah']['delete']);
        $this->assertEquals('1', $role->permissions['kariah']['approve']);
        $this->assertEquals('1', $role->permissions['kariah']['reject']);
        $this->assertEquals('1', $role->permissions['kariah']['suspend']);
        $this->assertEquals('1', $role->permissions['kariah']['reactivate']);
    }

    /**
     * Test that kariah is in workflow modules (has workflow actions)
     */
    public function test_kariah_is_workflow_module()
    {
        $roleController = new \App\Http\Controllers\RoleController();
        $reflection = new \ReflectionClass($roleController);
        
        $method = $reflection->getMethod('getWorkflowModules');
        $method->setAccessible(true);
        $workflowModules = $method->invoke($roleController);

        $this->assertContains('kariah', $workflowModules);
    }

    /**
     * Test that kariah module exists in available modules
     */
    public function test_kariah_module_exists()
    {
        $roleController = new \App\Http\Controllers\RoleController();
        $reflection = new \ReflectionClass($roleController);
        
        $method = $reflection->getMethod('getAvailableModules');
        $method->setAccessible(true);
        $modules = $method->invoke($roleController);

        $this->assertArrayHasKey('kariah', $modules);
        $this->assertEquals('Ahli Kariah', $modules['kariah']);
    }
}
