<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = array(
            array('name' => 'Taplejung', 'province_id' => '1'),
            array('name' => 'Panchthar', 'province_id' => '1'),
            array('name' => 'Ilam', 'province_id' => '1'),
            array('name' => 'Jhapa', 'province_id' => '1'),
            array('name' => 'Sankhuwasabha', 'province_id' => '1'),
            array('name' => 'Tehrathum', 'province_id' => '1'),
            array('name' => 'Bhojpur', 'province_id' => '1'),
            array('name' => 'Dhankuta', 'province_id' => '1'),
            array('name' => 'Morang', 'province_id' => '1'),
            array('name' => 'Sunsari', 'province_id' => '1'),
            array('name' => 'Solukhumbu', 'province_id' => '1'),
            array('name' => 'Khotang', 'province_id' => '1'),
            array('name' => 'Okhaldhunga', 'province_id' => '1'),
            array('name' => 'Udayapur', 'province_id' => '1'),
            array('name' => 'Saptari', 'province_id' => '2'),
            array('name' => 'Siraha', 'province_id' => '2'),
            array('name' => 'Dolakha', 'province_id' => '3'),
            array('name' => 'Ramechhap', 'province_id' => '3'),
            array('name' => 'Sindhuli', 'province_id' => '3'),
            array('name' => 'Dhanusha', 'province_id' => '2'),
            array('name' => 'Mahottari', 'province_id' => '2'),
            array('name' => 'Sarlahi', 'province_id' => '2'),
            array('name' => 'Sindhupalchowk', 'province_id' => '3'),
            array('name' => 'Kavrepalanchowk', 'province_id' => '3'),
            array('name' => 'Bhaktapur', 'province_id' => '3'),
            array('name' => 'Lalitpur', 'province_id' => '3'),
            array('name' => 'Kathmandu', 'province_id' => '3'),
            array('name' => 'Rasuwa', 'province_id' => '3'),
            array('name' => 'Nuwakot', 'province_id' => '3'),
            array('name' => 'Dhading', 'province_id' => '3'),
            array('name' => 'Rautahat', 'province_id' => '2'),
            array('name' => 'Makawanpur', 'province_id' => '3'),
            array('name' => 'Bara', 'province_id' => '2'),
            array('name' => 'Parsa', 'province_id' => '2'),
            array('name' => 'Chitwan', 'province_id' => '3'),
            array('name' => 'Gorkha', 'province_id' => '4'),
            array('name' => 'Lamjung', 'province_id' => '4'),
            array('name' => 'Tanahun', 'province_id' => '4'),
            array('name' => 'Manang', 'province_id' => '4'),
            array('name' => 'Kaski', 'province_id' => '4'),
            array('name' => 'Syangja', 'province_id' => '4'),
            array('name' => 'Nawalparasi (East)', 'province_id' => '4'),
            array('name' => 'Palpa', 'province_id' => '5'),
            array('name' => 'Rupandehi', 'province_id' => '5'),
            array('name' => 'Gulmi', 'province_id' => '5'),
            array('name' => 'Kapilvastu', 'province_id' => '5'),
            array('name' => 'Arghakhanchi', 'province_id' => '5'),
            array('name' => 'Mustang', 'province_id' => '4'),
            array('name' => 'Myagdi', 'province_id' => '4'),
            array('name' => 'Parbat', 'province_id' => '4'),
            array('name' => 'Baglung', 'province_id' => '4'),
            array('name' => 'Pyuthan', 'province_id' => '5'),
            array('name' => 'Rukum West ', 'province_id' => '6'),
            array('name' => 'Rolpa', 'province_id' => '5'),
            array('name' => 'Dang', 'province_id' => '5'),
            array('name' => 'Salyan', 'province_id' => '6'),
            array('name' => 'Jajarkot', 'province_id' => '6'),
            array('name' => 'Banke', 'province_id' => '5'),
            array('name' => 'Bardiya', 'province_id' => '5'),
            array('name' => 'Surkhet', 'province_id' => '6'),
            array('name' => 'Dailekh', 'province_id' => '6'),
            array('name' => 'Dolpa', 'province_id' => '6'),
            array('name' => 'Jumla', 'province_id' => '6'),
            array('name' => 'Kalikot', 'province_id' => '6'),
            array('name' => 'Mugu', 'province_id' => '6'),
            array('name' => 'Humla', 'province_id' => '6'),
            array('name' => 'Bajura', 'province_id' => '7'),
            array('name' => 'Achham', 'province_id' => '7'),
            array('name' => 'Kailali', 'province_id' => '7'),
            array('name' => 'Doti', 'province_id' => '7'),
            array('name' => 'Bajhang', 'province_id' => '7'),
            array('name' => 'Darchula', 'province_id' => '7'),
            array('name' => 'Baitadi', 'province_id' => '7'),
            array('name' => 'Dadeldhura', 'province_id' => '7'),
            array('name' => 'Kanchanpur', 'province_id' => '7'),
            array('name' => 'Nawalparasi (West)', 'province_id' => '5'),
            array('name' => 'Rukum East ', 'province_id' => '5')
        );
        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
