<?php

namespace Database\Seeders;

use App\Models\User\SexualityType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SexualityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sexes = json_decode(File::get(resource_path('example_data/sexualities.json')), true);

        foreach ($sexes as $sex) {
            SexualityType::create([
                'label' => $sex['label'],
            ]);
        }
    }
}
