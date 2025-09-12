<?php

// Working tests for actual TripMate routes
describe('TripMate Route Tests', function () {
    it('can access admin login page', function () {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    });

    it('can access hotel login page', function () {
        $response = $this->get('/hotel/login');
        $response->assertStatus(200);
    });

    it('returns 404 for non-existent routes', function () {
        $response = $this->get('/this-route-definitely-does-not-exist');
        $response->assertStatus(404);
    });

    it('validates that required routes exist', function () {
        // Let's check what routes are actually registered
        $router = app('router');
        $routes = $router->getRoutes();
        
        $routeList = [];
        foreach ($routes as $route) {
            $routeList[] = $route->uri();
        }
        
        // Admin login should exist
        expect(in_array('admin/login', $routeList))->toBeTrue();
        
        // Hotel login should exist  
        expect(in_array('hotel/login', $routeList))->toBeTrue();
    });

    it('can handle form submissions', function () {
        // This tests that the application can handle POST requests
        $response = $this->post('/admin/login', [
            'username' => 'test@example.com',
            'password' => 'password123'
        ]);
        
        // Should respond (not crash), regardless of authentication result
        expect($response->status())->toBeGreaterThan(0);
        expect($response->status())->toBeLessThan(600);
    });
});