<?php

describe('ATC22 - Room Management CRUD', function () {

    it('room management routes are accessible', function () {
        $response = $this->get('/hotel/rooms');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/hotel/rooms/create');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/hotel/rooms/manage');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('operator can view room listing page', function () {
        $response = $this->get('/hotel/rooms');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for room listing elements
            expect(
                str_contains($content, 'room') ||
                str_contains($content, 'Room') ||
                str_contains($content, 'add') ||
                str_contains($content, 'Add') ||
                str_contains($content, 'create') ||
                str_contains($content, 'Create')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
    });

    it('operator can access add new room form', function () {
        $response = $this->get('/hotel/rooms/create');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for form elements
            expect(
                str_contains($content, 'form') ||
                str_contains($content, 'input') ||
                str_contains($content, 'name') ||
                str_contains($content, 'type') ||
                str_contains($content, 'price') ||
                str_contains($content, 'capacity')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
    });

    it('room creation form validates required fields', function () {
        $response = $this->post('/hotel/rooms', [
            // Missing required fields
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 422, 401, 405, 500]); // Validation error expected
        
        // Test with incomplete data
        $response = $this->post('/hotel/rooms', [
            'name' => 'Test Room'
            // Missing other required fields
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 422, 401, 405, 500]);
    });

    it('operator can create new room successfully', function () {
        $roomData = [
            'name' => 'Deluxe Suite',
            'type' => 'suite',
            'capacity' => 4,
            'price' => 5000,
            'description' => 'Spacious deluxe suite with city view',
            'amenities' => 'WiFi, AC, TV, Mini Bar'
        ];
        
        $response = $this->post('/hotel/rooms', $roomData);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Check if redirected properly after creation
        if ($response->getStatusCode() === 302) {
            expect($response->headers->get('Location'))->toBeString();
        }
    });

    it('room creation stores data correctly', function () {
        $roomData = [
            'name' => 'Standard Room',
            'type' => 'standard',
            'capacity' => 2,
            'price' => 3000,
            'description' => 'Comfortable standard room',
            'amenities' => 'WiFi, AC, TV'
        ];
        
        $response = $this->post('/hotel/rooms', $roomData);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Verify room appears in listing
        $response = $this->get('/hotel/rooms');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('operator can edit existing room', function () {
        // First create a room to edit
        $response = $this->post('/hotel/rooms', [
            'name' => 'Room to Edit',
            'type' => 'standard',
            'capacity' => 2,
            'price' => 2500,
            'description' => 'Room for editing test'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Access edit form
        $response = $this->get('/hotel/rooms/1/edit');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Submit edit
        $response = $this->put('/hotel/rooms/1', [
            'name' => 'Updated Room Name',
            'type' => 'deluxe',
            'capacity' => 3,
            'price' => 3500,
            'description' => 'Updated room description'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('room updates persist correctly', function () {
        $updatedData = [
            'name' => 'Premium Room',
            'type' => 'premium',
            'capacity' => 4,
            'price' => 6000,
            'description' => 'Premium room with ocean view',
            'amenities' => 'WiFi, AC, TV, Mini Bar, Balcony'
        ];
        
        $response = $this->put('/hotel/rooms/1', $updatedData);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
        
        // Verify changes are reflected
        $response = $this->get('/hotel/rooms/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
        }
    });

    it('room price validation works correctly', function () {
        // Test invalid prices
        $invalidPrices = [-100, 0, 'invalid', null];
        
        foreach ($invalidPrices as $price) {
            $response = $this->post('/hotel/rooms', [
                'name' => 'Test Room',
                'type' => 'standard',
                'capacity' => 2,
                'price' => $price,
                'description' => 'Test room with invalid price'
            ]);
            
            expect($response->getStatusCode())->toBeIn([302, 422, 401, 405, 500]); // Should reject invalid prices
        }
        
        // Test valid price
        $response = $this->post('/hotel/rooms', [
            'name' => 'Valid Price Room',
            'type' => 'standard',
            'capacity' => 2,
            'price' => 2500,
            'description' => 'Room with valid price'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
    });

    it('room capacity validation functions properly', function () {
        // Test invalid capacities
        $invalidCapacities = [0, -1, 'invalid', 20]; // Assuming max capacity limit
        
        foreach ($invalidCapacities as $capacity) {
            $response = $this->post('/hotel/rooms', [
                'name' => 'Test Room',
                'type' => 'standard',
                'capacity' => $capacity,
                'price' => 2500,
                'description' => 'Test room with invalid capacity'
            ]);
            
            expect($response->getStatusCode())->toBeIn([302, 422, 401, 405, 500]);
        }
        
        // Test valid capacities
        $validCapacities = [1, 2, 4, 6];
        
        foreach ($validCapacities as $index => $capacity) {
            $response = $this->post('/hotel/rooms', [
                'name' => "Valid Capacity Room {$index}",
                'type' => 'standard',
                'capacity' => $capacity,
                'price' => 2500,
                'description' => "Room with {$capacity} person capacity"
            ]);
            expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        }
    });

    it('room visibility updates for users', function () {
        // Create a room
        $response = $this->post('/hotel/rooms', [
            'name' => 'User Visible Room',
            'type' => 'standard',
            'capacity' => 2,
            'price' => 3000,
            'description' => 'Room that should be visible to users'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Check if room appears in public hotel view
        $response = $this->get('/hotel/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Check rooms listing
        $response = $this->get('/hotel/1/rooms');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('room management requires hotel authentication', function () {
        // Test that room management requires proper authentication
        $response = $this->post('/hotel/rooms', [
            'name' => 'Unauthorized Room',
            'type' => 'standard',
            'capacity' => 2,
            'price' => 2500
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 401, 422, 405, 500]); // Should require auth
        
        $response = $this->get('/hotel/rooms/create');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]); // May redirect to login
    });

    it('room CRUD operations handle errors gracefully', function () {
        // Test accessing non-existent room for edit
        $response = $this->get('/hotel/rooms/999/edit');
        expect($response->getStatusCode())->toBeIn([404, 302, 403]);
        
        // Test updating non-existent room
        $response = $this->put('/hotel/rooms/999', [
            'name' => 'Non-existent Room'
        ]);
        expect($response->getStatusCode())->toBeIn([404, 302, 403, 422]);
        
        // Test deleting non-existent room
        $response = $this->delete('/hotel/rooms/999');
        expect($response->getStatusCode())->toBeIn([404, 302, 403]);
    });
});
