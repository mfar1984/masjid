<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Masjid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'jpg', 'png'];
        $extension = $this->faker->randomElement($extensions);
        $filename = $this->faker->slug() . '.' . $extension;

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'original_filename' => $filename,
            'file_path' => 'documents/' . $this->faker->uuid() . '/' . $filename,
            'file_extension' => $extension,
            'mime_type' => $this->getMimeType($extension),
            'file_size' => $this->faker->numberBetween(1024, 10485760), // 1KB to 10MB
            'file_hash' => $this->faker->sha256(),
            'version' => 1,
            'download_count' => $this->faker->numberBetween(0, 100),
            'is_starred' => $this->faker->boolean(20), // 20% chance of being starred
            'is_shared' => $this->faker->boolean(30), // 30% chance of being shared
            'folder_id' => null, // Will be set by relationship if needed
            'masjid_id' => Masjid::factory(),
            'created_by' => User::factory(),
            'updated_by' => null,
            'last_accessed_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Get MIME type for file extension
     */
    private function getMimeType(string $extension): string
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Indicate that the document is starred.
     */
    public function starred(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_starred' => true,
        ]);
    }

    /**
     * Indicate that the document is shared.
     */
    public function shared(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_shared' => true,
        ]);
    }

    /**
     * Indicate that the document is a PDF.
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'original_filename' => $this->faker->slug() . '.pdf',
        ]);
    }

    /**
     * Indicate that the document is an image.
     */
    public function image(): static
    {
        $extension = $this->faker->randomElement(['jpg', 'png', 'gif']);
        return $this->state(fn (array $attributes) => [
            'file_extension' => $extension,
            'mime_type' => 'image/' . ($extension === 'jpg' ? 'jpeg' : $extension),
            'original_filename' => $this->faker->slug() . '.' . $extension,
        ]);
    }
}
