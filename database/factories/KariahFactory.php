<?php

namespace Database\Factories;

use App\Models\Kariah;
use App\Models\Masjid;
use Illuminate\Database\Eloquent\Factories\Factory;

class KariahFactory extends Factory
{
    protected $model = Kariah::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'no_ic' => $this->faker->unique()->numerify('######-##-####'),
            'telefon' => $this->faker->phoneNumber,
            'bangsa' => $this->faker->randomElement(['Melayu', 'Cina', 'India', 'Lain-lain']),
            'jantina' => $this->faker->randomElement(['Lelaki', 'Perempuan', 'Tidak Dinyatakan']),
            'tarikh_keahlian' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Aktif', 'Tidak Aktif']),
            'alamat' => $this->faker->address,
            'email' => $this->faker->optional()->safeEmail,
            'masjid_id' => Masjid::factory(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
