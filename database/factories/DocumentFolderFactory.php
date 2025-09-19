<?php

namespace Database\Factories;

use App\Models\DocumentFolder;
use App\Models\Masjid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentFolder>
 */
class DocumentFolderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#84CC16', '#F97316'];

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->optional()->sentence(),
            'color' => $this->faker->randomElement($colors),
            'path' => '', // Will be calculated after creation
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_starred' => $this->faker->boolean(15), // 15% chance of being starred
            'is_shared' => $this->faker->boolean(20), // 20% chance of being shared
            'parent_folder_id' => null, // Will be set by relationship if needed
            'masjid_id' => Masjid::factory(),
            'created_by' => User::factory(),
            'updated_by' => null,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Indicate that the folder is starred.
     */
    public function starred(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_starred' => true,
        ]);
    }

    /**
     * Indicate that the folder is shared.
     */
    public function shared(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_shared' => true,
        ]);
    }

    /**
     * Create a subfolder.
     */
    public function subfolder(DocumentFolder $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_folder_id' => $parent->id,
            'masjid_id' => $parent->masjid_id,
        ]);
    }
}
