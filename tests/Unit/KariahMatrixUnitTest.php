<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\RoleController;
use ReflectionClass;

class KariahMatrixUnitTest extends TestCase
{
    /**
     * Test that kariah module exists in available modules
     */
    public function test_kariah_module_exists_in_available_modules()
    {
        $roleController = new RoleController();
        $reflection = new ReflectionClass($roleController);
        
        $method = $reflection->getMethod('getAvailableModules');
        $method->setAccessible(true);
        $modules = $method->invoke($roleController);

        $this->assertArrayHasKey('kariah', $modules);
        $this->assertEquals('Ahli Kariah', $modules['kariah']);
    }

    /**
     * Test that kariah is in workflow modules (has workflow actions)
     */
    public function test_kariah_is_in_workflow_modules()
    {
        $roleController = new RoleController();
        $reflection = new ReflectionClass($roleController);
        
        $method = $reflection->getMethod('getWorkflowModules');
        $method->setAccessible(true);
        $workflowModules = $method->invoke($roleController);

        $this->assertContains('kariah', $workflowModules);
    }

    /**
     * Test that all 8 actions are available
     */
    public function test_all_eight_actions_available()
    {
        $roleController = new RoleController();
        $reflection = new ReflectionClass($roleController);
        
        $method = $reflection->getMethod('getAvailableActions');
        $method->setAccessible(true);
        $actions = $method->invoke($roleController);

        // Basic actions (4)
        $this->assertArrayHasKey('basic', $actions);
        $this->assertArrayHasKey('create', $actions['basic']);
        $this->assertArrayHasKey('read', $actions['basic']);
        $this->assertArrayHasKey('update', $actions['basic']);
        $this->assertArrayHasKey('delete', $actions['basic']);

        // Workflow actions (4)
        $this->assertArrayHasKey('workflow', $actions);
        $this->assertArrayHasKey('approve', $actions['workflow']);
        $this->assertArrayHasKey('reject', $actions['workflow']);
        $this->assertArrayHasKey('suspend', $actions['workflow']);
        $this->assertArrayHasKey('reactivate', $actions['workflow']);

        // Verify action names in Malay
        $this->assertEquals('Tambah', $actions['basic']['create']);
        $this->assertEquals('Lihat', $actions['basic']['read']);
        $this->assertEquals('Kemaskini', $actions['basic']['update']);
        $this->assertEquals('Padam', $actions['basic']['delete']);
        $this->assertEquals('Terima', $actions['workflow']['approve']);
        $this->assertEquals('Tolak', $actions['workflow']['reject']);
        $this->assertEquals('Gantung', $actions['workflow']['suspend']);
        $this->assertEquals('Aktifkan', $actions['workflow']['reactivate']);
    }

    /**
     * Test that kariah has both basic and workflow actions
     */
    public function test_kariah_has_both_basic_and_workflow_actions()
    {
        $roleController = new RoleController();
        $reflection = new ReflectionClass($roleController);
        
        // Get modules
        $modulesMethod = $reflection->getMethod('getAvailableModules');
        $modulesMethod->setAccessible(true);
        $modules = $modulesMethod->invoke($roleController);

        // Get workflow modules
        $workflowMethod = $reflection->getMethod('getWorkflowModules');
        $workflowMethod->setAccessible(true);
        $workflowModules = $workflowMethod->invoke($roleController);

        // Kariah should exist in modules
        $this->assertArrayHasKey('kariah', $modules);
        
        // Kariah should be in workflow modules (meaning it has workflow actions)
        $this->assertContains('kariah', $workflowModules);
    }

    /**
     * Test matrix structure for kariah
     */
    public function test_kariah_matrix_structure()
    {
        $roleController = new RoleController();
        $reflection = new ReflectionClass($roleController);
        
        $modulesMethod = $reflection->getMethod('getAvailableModules');
        $modulesMethod->setAccessible(true);
        $modules = $modulesMethod->invoke($roleController);

        $actionsMethod = $reflection->getMethod('getAvailableActions');
        $actionsMethod->setAccessible(true);
        $actions = $actionsMethod->invoke($roleController);

        $workflowMethod = $reflection->getMethod('getWorkflowModules');
        $workflowMethod->setAccessible(true);
        $workflowModules = $workflowMethod->invoke($roleController);

        // Kariah should have:
        // 1. Module entry
        $this->assertArrayHasKey('kariah', $modules);
        $this->assertEquals('Ahli Kariah', $modules['kariah']);

        // 2. Basic actions (4 checkboxes)
        $this->assertCount(4, $actions['basic']);

        // 3. Workflow actions (4 checkboxes) - because kariah is in workflow modules
        $this->assertCount(4, $actions['workflow']);
        $this->assertContains('kariah', $workflowModules);

        // Total: 8 checkboxes for kariah module
        $totalActions = count($actions['basic']) + count($actions['workflow']);
        $this->assertEquals(8, $totalActions);
    }
}
