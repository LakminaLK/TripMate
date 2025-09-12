<?php

// Simple route tests that don't require database
describe('Route Tests', function () {
    it('can access home page', function () {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    });

    it('can access tourist registration page', function () {
        $response = $this->get('/tourist/register');
        
        $response->assertStatus(200);
    });

    it('can access tourist login page', function () {
        $response = $this->get('/tourist/login');
        
        $response->assertStatus(200);
    });

    it('can access hotel registration page', function () {
        $response = $this->get('/hotel/register');
        
        $response->assertStatus(200);
    });

    it('can access hotel login page', function () {
        $response = $this->get('/hotel/login');
        
        $response->assertStatus(200);
    });

    it('can access admin login page', function () {
        $response = $this->get('/admin/login');
        
        $response->assertStatus(200);
    });

    it('redirects unauthorized users from protected routes', function () {
        $protectedRoutes = [
            '/tourist/dashboard',
            '/hotel/dashboard',
            '/admin/dashboard'
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect();
        }
    });

    it('returns 404 for non-existent routes', function () {
        $response = $this->get('/non-existent-route');
        
        $response->assertStatus(404);
    });

    it('can access activities page', function () {
        $response = $this->get('/activities');
        
        $response->assertStatus(200);
    });

    it('can access hotels page', function () {
        $response = $this->get('/hotels');
        
        $response->assertStatus(200);
    });
});