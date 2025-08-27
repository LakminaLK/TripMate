<?php

namespace Database\Seeders;

use App\Models\EmergencyService;
use Illuminate\Database\Seeder;

class EmergencyServicesSeeder extends Seeder
{
    public function run()
    {
        $services = [
            // Colombo Region
            [
                'name' => 'National Hospital of Sri Lanka',
                'type' => 'hospital',
                'description' => 'The largest public hospital in Sri Lanka providing 24/7 emergency services.',
                'address' => 'E.W. Perera Mawatha, Colombo 10',
                'phone' => '011 2 691111',
                'emergency_phone' => '011 2 691111',
                'latitude' => 6.9214,
                'longitude' => 79.8641,
                'is_24_7' => true,
                'operating_hours' => null,
                'google_place_id' => 'ChIJ_______123',
            ],
            [
                'name' => 'Colombo Fort Police Station',
                'type' => 'police',
                'description' => 'Main police station in Colombo with rapid response capabilities.',
                'address' => 'Sir Baron Jayathilaka Mawatha, Colombo',
                'phone' => '011 2 422222',
                'emergency_phone' => '119',
                'latitude' => 6.9344,
                'longitude' => 79.8428,
                'is_24_7' => true,
                'operating_hours' => null,
                'google_place_id' => 'ChIJ_______456',
            ],
            [
                'name' => 'Colombo Fire Station',
                'type' => 'fire_station',
                'description' => 'Main fire station of Colombo with modern firefighting equipment and rescue services.',
                'address' => 'York Street, Colombo 01',
                'phone' => '011 2 422222',
                'emergency_phone' => '110',
                'latitude' => 6.9321,
                'longitude' => 79.8478,
                'is_24_7' => true,
                'operating_hours' => null,
                'google_place_id' => 'ChIJ_______789',
            ],
            [
                'name' => 'Union Pharmacy',
                'type' => 'pharmacy',
                'description' => '24/7 pharmacy providing essential medications and first aid supplies.',
                'address' => 'Union Place, Colombo 02',
                'phone' => '011 2 304060',
                'emergency_phone' => null,
                'latitude' => 6.9147,
                'longitude' => 79.8537,
                'is_24_7' => true,
                'operating_hours' => null,
                'google_place_id' => 'ChIJ_______321',
            ],
            [
                'name' => 'Suwa Seriya Ambulance Service',
                'type' => 'ambulance',
                'description' => 'Government ambulance service with island-wide coverage and advanced life support.',
                'address' => 'Colombo',
                'phone' => '1990',
                'emergency_phone' => '1990',
                'latitude' => 6.9271,
                'longitude' => 79.8612,
                'is_24_7' => true,
                'operating_hours' => null,
                'google_place_id' => 'ChIJ_______159',
            ],
            // Kandy Region
            [
                'name' => 'Teaching Hospital Kandy',
                'type' => 'hospital',
                'description' => 'One of the largest teaching hospitals in Sri Lanka with comprehensive emergency care.',
                'address' => 'William Gopallawa Mawatha, Kandy',
                'phone' => '081 2 233337',
                'emergency_phone' => '081 2 233337',
                'latitude' => 7.2906,
                'longitude' => 80.6337,
                'is_24_7' => true,
                'operating_hours' => null,
            ],
            [
                'name' => 'Kandy Police Station',
                'type' => 'police',
                'description' => 'Main police station in Kandy city.',
                'address' => 'DS Senanayake Veediya, Kandy',
                'phone' => '081 2 222222',
                'emergency_phone' => '119',
                'latitude' => 7.2925,
                'longitude' => 80.6346,
                'is_24_7' => true,
                'operating_hours' => null,
            ],

            // Galle Region
            [
                'name' => 'Teaching Hospital Karapitiya',
                'type' => 'hospital',
                'description' => 'Major teaching hospital serving the Southern Province.',
                'address' => 'Karapitiya, Galle',
                'phone' => '091 2 232276',
                'emergency_phone' => '091 2 232276',
                'latitude' => 6.0857,
                'longitude' => 80.2384,
                'is_24_7' => true,
                'operating_hours' => null,
            ],
            [
                'name' => 'Galle Police Station',
                'type' => 'police',
                'description' => 'Main police station in Galle city.',
                'address' => 'Wakwella Road, Galle',
                'phone' => '091 2 234333',
                'emergency_phone' => '119',
                'latitude' => 6.0367,
                'longitude' => 80.2169,
                'is_24_7' => true,
                'operating_hours' => null,
            ],

            // Anuradhapura Region
            [
                'name' => 'Teaching Hospital Anuradhapura',
                'type' => 'hospital',
                'description' => 'Major hospital serving the North Central Province.',
                'address' => 'Harischandra Mawatha, Anuradhapura',
                'phone' => '025 2 222261',
                'emergency_phone' => '025 2 222261',
                'latitude' => 8.3353,
                'longitude' => 80.4082,
                'is_24_7' => true,
                'operating_hours' => null,
            ],
            [
                'name' => 'Anuradhapura Police Station',
                'type' => 'police',
                'description' => 'Main police station in Anuradhapura.',
                'address' => 'Maithripala Senanayake Mawatha, Anuradhapura',
                'phone' => '025 2 222226',
                'emergency_phone' => '119',
                'latitude' => 8.3368,
                'longitude' => 80.4037,
                'is_24_7' => true,
                'operating_hours' => null,
            ],

            // Jaffna Region
            [
                'name' => 'Teaching Hospital Jaffna',
                'type' => 'hospital',
                'description' => 'Major teaching hospital serving the Northern Province.',
                'address' => 'Hospital Road, Jaffna',
                'phone' => '021 2 222261',
                'emergency_phone' => '021 2 222261',
                'latitude' => 9.6615,
                'longitude' => 80.0255,
                'is_24_7' => true,
                'operating_hours' => null,
            ],
            [
                'name' => 'Jaffna Police Station',
                'type' => 'police',
                'description' => 'Main police station in Jaffna.',
                'address' => 'Clock Tower Road, Jaffna',
                'phone' => '021 2 222222',
                'emergency_phone' => '119',
                'latitude' => 9.6627,
                'longitude' => 80.0179,
                'is_24_7' => true,
                'operating_hours' => null,
            ],

            // Negombo Region
            [
                'name' => 'District General Hospital Negombo',
                'type' => 'hospital',
                'description' => 'Major hospital serving the Negombo region.',
                'address' => 'Colombo Road, Negombo',
                'phone' => '031 2 222261',
                'emergency_phone' => '031 2 222261',
                'latitude' => 7.2089,
                'longitude' => 79.8390,
                'is_24_7' => true,
                'operating_hours' => null,
            ],

            // Batticaloa Region
            [
                'name' => 'Teaching Hospital Batticaloa',
                'type' => 'hospital',
                'description' => 'Major teaching hospital serving the Eastern Province.',
                'address' => 'Bar Road, Batticaloa',
                'phone' => '065 2 222261',
                'emergency_phone' => '065 2 222261',
                'latitude' => 7.7167,
                'longitude' => 81.7000,
                'is_24_7' => true,
                'operating_hours' => null,
            ],
        ];

        foreach ($services as $service) {
            EmergencyService::create($service);
        }
    }
}
