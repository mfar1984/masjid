<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class SuperAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        
        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found!');
            return;
        }

        // Define all permissions for Super Admin
        $permissions = [
            'dashboard' => [
                'read' => '1'
            ],
            'masjids' => [
                'create' => '1',
                'read' => '1',
                'update' => '1',
                'delete' => '1',
                'approve' => '1',
                'reject' => '1',
                'suspend' => '1',
                'reactivate' => '1'
            ],
            'users' => [
                'create' => '1',
                'read' => '1',
                'update' => '1',
                'delete' => '1',
                'suspend' => '1',
                'reactivate' => '1'
            ],
            'roles' => [
                'create' => '1',
                'read' => '1',
                'update' => '1',
                'delete' => '1'
            ],
            'kariah' => [
                'create' => '1',
                'read' => '1',
                'update' => '1',
                'delete' => '1',
                'approve' => '1',
                'reject' => '1',
                'suspend' => '1',
                'reactivate' => '1'
            ],
            'documents' => [
                'create' => '1',
                'read' => '1',
                'update' => '1',
                'delete' => '1',
                'share' => '1'
            ],
            'settings' => [
                'read' => '1',
                'update' => '1'
            ],
            'integrations' => [
                'read' => '1',
                'update' => '1'
            ],
            'integrations_email' => [
                'read' => '1',
                'update' => '1'
            ],
            'integrations_weather' => [
                'read' => '1',
                'update' => '1'
            ],
            'integrations_api' => [
                'read' => '1',
                'update' => '1'
            ]
        ];

        // Update Super Admin role permissions
        $superAdminRole->permissions = $permissions;
        $superAdminRole->save();

        $this->command->info('Super Admin permissions updated successfully!');
        $this->command->info('✅ Kariah: All 8 permissions granted');
        $this->command->info('✅ Documents: All 5 permissions granted');
        $this->command->info('✅ Users: 6 permissions granted');
        $this->command->info('✅ Masjids: All 8 permissions granted');
        $this->command->info('✅ Roles: 4 permissions granted');
        $this->command->info('✅ Settings & Integrations: Read/Update permissions granted');
    }
}
