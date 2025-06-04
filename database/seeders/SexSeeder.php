<?php

namespace Database\Seeders;

use App\Models\Sex;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sexes = json_decode(File::get(resource_path('example_data/sexes.json')), true);

        foreach ($sexes as $sex) {
            Sex::create([
                'label' => $sex['label'],
                'value' => $sex['value'],
            ]);
        }
    }
}
