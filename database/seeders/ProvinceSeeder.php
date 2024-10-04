<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            "Koshi Province",
            "Madhesh Province",
            "Bagmati Province",
            "Gandaki Province",
            "Lumbini Province",
            "Karnali Province",
            "Sudurpaschim Province"
        ];
        foreach ($provinces as $province) {
            Province::create(["name" => $province]);
        }
    }
}
