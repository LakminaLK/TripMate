<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            // Connectivity
            ['name' => 'Free WiFi', 'icon' => 'fas fa-wifi', 'category' => 'connectivity'],
            ['name' => 'High-Speed Internet', 'icon' => 'fas fa-ethernet', 'category' => 'connectivity'],
            
            // Amenities
            ['name' => 'Swimming Pool', 'icon' => 'fas fa-swimming-pool', 'category' => 'amenities'],
            ['name' => 'Fitness Center', 'icon' => 'fas fa-dumbbell', 'category' => 'amenities'],
            ['name' => 'Spa Services', 'icon' => 'fas fa-spa', 'category' => 'amenities'],
            ['name' => 'Restaurant', 'icon' => 'fas fa-utensils', 'category' => 'amenities'],
            ['name' => 'Bar/Lounge', 'icon' => 'fas fa-cocktail', 'category' => 'amenities'],
            ['name' => 'Room Service', 'icon' => 'fas fa-bell', 'category' => 'amenities'],
            ['name' => 'Laundry Service', 'icon' => 'fas fa-tshirt', 'category' => 'amenities'],
            ['name' => 'Concierge Service', 'icon' => 'fas fa-concierge-bell', 'category' => 'amenities'],
            
            // Transportation
            ['name' => 'Free Parking', 'icon' => 'fas fa-parking', 'category' => 'transportation'],
            ['name' => 'Valet Parking', 'icon' => 'fas fa-car', 'category' => 'transportation'],
            ['name' => 'Airport Shuttle', 'icon' => 'fas fa-shuttle-van', 'category' => 'transportation'],
            
            // Business
            ['name' => 'Business Center', 'icon' => 'fas fa-briefcase', 'category' => 'business'],
            ['name' => 'Meeting Rooms', 'icon' => 'fas fa-users', 'category' => 'business'],
            ['name' => 'Conference Facilities', 'icon' => 'fas fa-chalkboard', 'category' => 'business'],
            
            // Family
            ['name' => 'Pet Friendly', 'icon' => 'fas fa-paw', 'category' => 'family'],
            ['name' => 'Kids Club', 'icon' => 'fas fa-child', 'category' => 'family'],
            ['name' => 'Babysitting Service', 'icon' => 'fas fa-baby', 'category' => 'family'],
            
            // Safety & Security
            ['name' => '24/7 Security', 'icon' => 'fas fa-shield-alt', 'category' => 'security'],
            ['name' => '24/7 Front Desk', 'icon' => 'fas fa-clock', 'category' => 'security'],
            ['name' => 'Safe Deposit Box', 'icon' => 'fas fa-lock', 'category' => 'security'],
            
            // Comfort
            ['name' => 'Air Conditioning', 'icon' => 'fas fa-snowflake', 'category' => 'comfort'],
            ['name' => 'Heating', 'icon' => 'fas fa-fire', 'category' => 'comfort'],
            ['name' => 'Non-Smoking Rooms', 'icon' => 'fas fa-ban', 'category' => 'comfort'],
            ['name' => 'Balcony/Terrace', 'icon' => 'fas fa-tree', 'category' => 'comfort'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
