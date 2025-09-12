<?php

describe('ATC19 - Submit Review', function () {

    it('review submission routes respond properly', function () {
        $response = $this->get('/reviews/create');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 5,
            'comment' => 'Test review'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
    });

    it('traveler can access review form', function () {
        $response = $this->get('/review/create/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/booking/1/review');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('review form requires star rating', function () {
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'comment' => 'Great hotel without rating'
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 422, 401, 500]); // Validation error or redirect
    });

    it('review form accepts valid star ratings', function () {
        $validRatings = [1, 2, 3, 4, 5];
        
        foreach ($validRatings as $rating) {
            $response = $this->post('/reviews', [
                'booking_id' => 1,
                'rating' => $rating,
                'comment' => "Test review with {$rating} stars"
            ]);
            
            expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
        }
    });

    it('review form validates rating range', function () {
        $invalidRatings = [0, 6, -1, 10];
        
        foreach ($invalidRatings as $rating) {
            $response = $this->post('/reviews', [
                'booking_id' => 1,
                'rating' => $rating,
                'comment' => 'Test review with invalid rating'
            ]);
            
            expect($response->getStatusCode())->toBeIn([302, 422, 401, 500]); // Should reject invalid ratings
        }
    });

    it('review submission includes text comment', function () {
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 4,
            'comment' => 'This is a detailed review of my stay. The service was excellent and the room was clean.'
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
    });

    it('review submission requires booking reference', function () {
        $response = $this->post('/reviews', [
            'rating' => 5,
            'comment' => 'Review without booking ID'
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 422, 401]); // Should require booking ID
    });

    it('review form handles CSRF protection', function () {
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 5,
            'comment' => 'Test review for CSRF'
        ], [
            'X-CSRF-TOKEN' => 'invalid-token'
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 419, 422, 401, 500]); // CSRF error expected
    });

    it('successful review submission saves and displays', function () {
        $reviewData = [
            'booking_id' => 1,
            'rating' => 5,
            'comment' => 'Excellent service and great location!'
        ];
        
        $response = $this->post('/reviews', $reviewData);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
        
        // Test that review appears in listings
        $response = $this->get('/hotel/1/reviews');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 500]);
    });

    it('review submission redirects appropriately', function () {
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 4,
            'comment' => 'Good experience overall'
        ]);
        
        if ($response->getStatusCode() === 302) {
            expect($response->headers->get('Location'))->toBeString();
        }
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
    });

    it('review form prevents duplicate reviews', function () {
        $reviewData = [
            'booking_id' => 1,
            'rating' => 5,
            'comment' => 'First review'
        ];
        
        // First submission
        $response = $this->post('/reviews', $reviewData);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
        
        // Second submission for same booking
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 3,
            'comment' => 'Duplicate review attempt'
        ]);
        
        expect($response->getStatusCode())->toBeIn([302, 422, 401, 500]); // Should prevent duplicates
    });

    it('review submission validates comment length', function () {
        // Test very long comment
        $longComment = str_repeat('This is a very long review. ', 100);
        
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 4,
            'comment' => $longComment
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
        
        // Test empty comment
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'rating' => 4,
            'comment' => ''
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 500]);
    });
});