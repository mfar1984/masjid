<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Admin Masjid', 'Moderator', 'Staf']),
            'description' => $this->faker->sentence,
            'permissions' => [
                'kariah' => [
                    'create' => '1',
                    'read' => '1',
                    'update' => '1',
                    'delete' => '1'
                ]
            ],
            'is_system_role' => false,
            'masjid_id' => null,
        ];
    }
}
