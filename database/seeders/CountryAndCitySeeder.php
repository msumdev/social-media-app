<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountryAndCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '512M');

        $countries = json_decode(File::get(resource_path('example_data/countries_large.json')), true);

        foreach ($countries as $country) {
            Country::insert($country);
        }

        $cities = json_decode(File::get(resource_path('example_data/cities_large.json')), true);

        $chunkSize = 1000;
        $citiesChunks = array_chunk($cities, $chunkSize);

        foreach ($citiesChunks as $chunk) {
            DB::table('cities')->insert($chunk);
        }
    }
}
