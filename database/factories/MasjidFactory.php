<?php

namespace Database\Factories;

use App\Models\Masjid;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasjidFactory extends Factory
{
    protected $model = Masjid::class;

    public function definition(): array
    {
        return [
            'nama' => 'Masjid ' . $this->faker->words(2, true),
            'alamat' => $this->faker->address,
            'telefon' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'active',
            'kod_masjid' => strtoupper($this->faker->unique()->lexify('MSJ???')),
        ];
    }
}
