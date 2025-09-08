<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard Room',
                'description' => 'Comfortable room with basic amenities, perfect for budget-conscious travelers',
                'icon' => 'fas fa-bed',
                'max_occupancy' => 2,
                'base_price' => 75.00,
                'sort_order' => 1
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Spacious room with enhanced amenities and modern furnishings',
                'icon' => 'fas fa-bed',
                'max_occupancy' => 2,
                'base_price' => 120.00,
                'sort_order' => 2
            ],
            [
                'name' => 'Twin Room',
                'description' => 'Room with two single beds, ideal for friends or colleagues traveling together',
                'icon' => 'fas fa-bed',
                'max_occupancy' => 2,
                'base_price' => 85.00,
                'sort_order' => 3
            ],
            [
                'name' => 'Family Room',
                'description' => 'Large room designed for families with additional space and amenities',
                'icon' => 'fas fa-users',
                'max_occupancy' => 4,
                'base_price' => 150.00,
                'sort_order' => 4
            ],
            [
                'name' => 'Junior Suite',
                'description' => 'Elegant suite with separate living area and upgraded amenities',
                'icon' => 'fas fa-crown',
                'max_occupancy' => 2,
                'base_price' => 200.00,
                'sort_order' => 5
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Luxurious suite with premium amenities and exceptional service',
                'icon' => 'fas fa-crown',
                'max_occupancy' => 3,
                'base_price' => 300.00,
                'sort_order' => 6
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'The ultimate luxury experience with lavish amenities and personalized service',
                'icon' => 'fas fa-gem',
                'max_occupancy' => 4,
                'base_price' => 500.00,
                'sort_order' => 7
            ],
            [
                'name' => 'Single Room',
                'description' => 'Cozy room designed for solo travelers with all essential amenities',
                'icon' => 'fas fa-user',
                'max_occupancy' => 1,
                'base_price' => 60.00,
                'sort_order' => 8
            ],
            [
                'name' => 'Accessible Room',
                'description' => 'Specially designed room with accessibility features for guests with disabilities',
                'icon' => 'fas fa-wheelchair',
                'max_occupancy' => 2,
                'base_price' => 85.00,
                'sort_order' => 9
            ],
            [
                'name' => 'Studio Room',
                'description' => 'Open-plan room with kitchenette, perfect for extended stays',
                'icon' => 'fas fa-home',
                'max_occupancy' => 2,
                'base_price' => 110.00,
                'sort_order' => 10
            ]
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}
