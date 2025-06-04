<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            'Male',
            'Female',
            'Non-binary',
            'Genderqueer',
            'Transgender',
            'Genderfluid',
            'Agender',
            'Bigender',
            'Androgynous',
            'Two-Spirit',
        ];

        foreach ($genders as $gender) {
            Gender::create([
                'label' => $gender,
            ]);
        }
    }
}
