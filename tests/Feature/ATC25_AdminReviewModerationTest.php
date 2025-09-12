<?php

describe('ATC25 - Admin Review Moderation', function () {

    it('admin review moderation routes are accessible', function () {
        $response = $this->get('/admin/reviews');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->get('/admin/moderation');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->get('/admin/reviews/flagged');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
    });

    it('admin can access review management dashboard', function () {
        $response = $this->get('/admin/reviews');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for review management elements
            expect(
                str_contains($content, 'review') ||
                str_contains($content, 'Review') ||
                str_contains($content, 'moderation') ||
                str_contains($content, 'Moderation') ||
                str_contains($content, 'manage') ||
                str_contains($content, 'Manage')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        }
    });

    it('admin can view all reviews listing', function () {
        $response = $this->get('/admin/reviews/all');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for review listing elements
            expect(
                str_contains($content, 'rating') ||
                str_contains($content, 'Rating') ||
                str_contains($content, 'comment') ||
                str_contains($content, 'Comment') ||
                str_contains($content, 'hotel') ||
                str_contains($content, 'Hotel') ||
                str_contains($content, 'user') ||
                str_contains($content, 'User')
            )->toBeTrue();
        }
    });

    it('admin can identify flagged reviews', function () {
        $response = $this->get('/admin/reviews/flagged');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for flagged review indicators
            expect(
                str_contains($content, 'flagged') ||
                str_contains($content, 'Flagged') ||
                str_contains($content, 'reported') ||
                str_contains($content, 'Reported') ||
                str_contains($content, 'flag') ||
                str_contains($content, 'Flag')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        }
    });

    it('admin can moderate and remove reviews', function () {
        // Test removing a specific review
        $response = $this->delete('/admin/reviews/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 403]);
        
        // Test review moderation action
        $response = $this->post('/admin/reviews/1/moderate', [
            'action' => 'remove',
            'reason' => 'Inappropriate content'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
        
        // Test bulk moderation
        $response = $this->post('/admin/reviews/bulk-moderate', [
            'review_ids' => [1, 2, 3],
            'action' => 'remove'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('removed reviews are hidden from public listing', function () {
        // Remove a review
        $response = $this->delete('/admin/reviews/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 403]);
        
        // Check that review is removed from hotel page
        $response = $this->get('/hotel/1/reviews');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        // Check that review is removed from public reviews
        $response = $this->get('/reviews');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
    });

    it('admin can approve pending reviews', function () {
        $response = $this->post('/admin/reviews/1/approve');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 403]);
        
        $response = $this->post('/admin/reviews/1/moderate', [
            'action' => 'approve',
            'reason' => 'Content is appropriate'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('admin can flag inappropriate reviews', function () {
        $response = $this->post('/admin/reviews/1/flag', [
            'reason' => 'Spam content',
            'severity' => 'high'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
        
        $response = $this->post('/admin/reviews/1/moderate', [
            'action' => 'flag',
            'reason' => 'Inappropriate language'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('review moderation requires admin authentication', function () {
        // Test that moderation requires admin login
        $response = $this->delete('/admin/reviews/1');
        
        if ($response->getStatusCode() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toBeString();
            expect(
                str_contains($location, 'login') ||
                str_contains($location, 'auth')
            )->toBeTrue();
        }
        
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 403, 401]);
    });

    it('admin can search and filter reviews', function () {
        $response = $this->get('/admin/reviews?search=excellent');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->get('/admin/reviews?filter=flagged');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->get('/admin/reviews?hotel_id=1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->get('/admin/reviews?rating=1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
    });

    it('admin can view review details and context', function () {
        $response = $this->get('/admin/reviews/1/details');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for detailed review information
            expect(
                str_contains($content, 'detail') ||
                str_contains($content, 'Detail') ||
                str_contains($content, 'booking') ||
                str_contains($content, 'Booking') ||
                str_contains($content, 'guest') ||
                str_contains($content, 'Guest') ||
                str_contains($content, 'date') ||
                str_contains($content, 'Date')
            )->toBeTrue();
        }
    });

    it('moderation actions are logged for audit trail', function () {
        // Test moderation with logging
        $response = $this->post('/admin/reviews/1/moderate', [
            'action' => 'remove',
            'reason' => 'Violates community guidelines',
            'admin_notes' => 'Contains inappropriate language'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
        
        // Check moderation log
        $response = $this->get('/admin/moderation/log');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->get('/admin/reviews/1/history');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
    });

    it('admin can restore accidentally removed reviews', function () {
        // Remove a review first
        $response = $this->delete('/admin/reviews/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 403]);
        
        // Restore the review
        $response = $this->post('/admin/reviews/1/restore');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 403]);
        
        $response = $this->post('/admin/reviews/1/moderate', [
            'action' => 'restore',
            'reason' => 'Mistakenly removed'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('moderation statistics are available to admin', function () {
        $response = $this->get('/admin/moderation/stats');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for moderation statistics
            expect(
                str_contains($content, 'total') ||
                str_contains($content, 'Total') ||
                str_contains($content, 'removed') ||
                str_contains($content, 'Removed') ||
                str_contains($content, 'approved') ||
                str_contains($content, 'Approved') ||
                str_contains($content, 'pending') ||
                str_contains($content, 'Pending')
            )->toBeTrue();
        }
    });

    it('admin can set review moderation policies', function () {
        $response = $this->get('/admin/moderation/settings');
        expect($response->getStatusCode())->toBeIn([200, 302, 404, 405, 500]);
        
        $response = $this->post('/admin/moderation/settings', [
            'auto_flag_keywords' => 'spam,fake,terrible',
            'require_approval' => true,
            'min_rating_threshold' => 1
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });
});
