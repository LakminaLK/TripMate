import { test, expect } from '@playwright/test';

test.describe('TripMate Hotel Booking Tests', () => {
  
  test('Tourist login and hotel booking flow', async ({ page }) => {
    // Navigate to login page
    await page.goto('/login');
    
    // Verify login form is visible
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    
    // Login as tourist
    await page.fill('input[name="email"]', 'tourist@test.com');
    await page.fill('input[name="password"]', 'password123');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL('/dashboard');
    await expect(page).toHaveURL('/dashboard');
    
    // Navigate to hotels
    await page.click('a[href="/hotels"]');
    await expect(page.locator('h1')).toContainText('Available Hotels');
    
    // Select first hotel
    await page.click('button:has-text("View Details"):first');
    
    // Book hotel room
    await page.click('button:has-text("Book Now")');
    await page.fill('input[name="check_in"]', '2025-12-01');
    await page.fill('input[name="check_out"]', '2025-12-05');
    await page.selectOption('select[name="guests"]', '2');
    
    // Proceed to payment
    await page.click('button:has-text("Continue to Payment")');
    await expect(page.locator('h2')).toContainText('Payment Details');
  });

  test('Admin dashboard access and hotel management', async ({ page }) => {
    // Navigate to admin login
    await page.goto('/admin/login');
    
    // Login as admin
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'admin123');
    await page.click('button:has-text("Sign In")');
    
    // Verify admin dashboard
    await page.waitForURL('/admin/dashboard');
    await expect(page.locator('h1')).toContainText('Admin Dashboard');
    
    // Navigate to hotel management
    await page.click('a:has-text("Hotels")');
    await expect(page.locator('h2')).toContainText('Hotel Management');
    
    // Check if hotels table is visible
    await expect(page.locator('table')).toBeVisible();
  });

  test('Responsive design - mobile view', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Navigate to homepage
    await page.goto('/');
    
    // Check mobile navigation
    await expect(page.locator('button[aria-label="Menu"]')).toBeVisible();
    
    // Test mobile menu
    await page.click('button[aria-label="Menu"]');
    await expect(page.locator('nav ul')).toBeVisible();
    
    // Test hotel cards responsiveness
    await page.goto('/hotels');
    const hotelCards = page.locator('.hotel-card');
    await expect(hotelCards.first()).toBeVisible();
  });
});