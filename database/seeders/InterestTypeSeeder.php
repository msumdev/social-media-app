<?php

namespace Database\Seeders;

use App\Models\User\InterestType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class InterestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interestTypes = json_decode(File::get(resource_path('example_data/interest_types.json')), true);

        foreach ($interestTypes as $interestType) {
            InterestType::create([
                'label' => $interestType['label'],
            ]);
        }
    }
}
