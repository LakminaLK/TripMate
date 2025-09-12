# TripMate Automated Testing Guide

## Overview
This guide covers automated testing setup for the TripMate Laravel application using **Pest PHP** testing framework.

## Testing Structure

```
tests/
├── Feature/           # End-to-end tests that hit actual routes
├── Unit/             # Isolated tests for individual components  
├── Pest.php          # Pest configuration
├── TestCase.php      # Base test case with helpers
└── UnitTestCase.php  # Base for pure unit tests
```

## Test Types

### 1. Unit Tests
- Test individual models, services, and utilities
- Don't require database or Laravel app
- Fast execution
- Focus on single functions/methods

### 2. Feature Tests  
- Test complete user workflows
- Hit actual routes and controllers
- Use real database (SQLite in memory)
- Test authentication, validation, business logic

### 3. Integration Tests
- Test interactions between components
- Database relationships and queries
- External API integrations

## Running Tests

### Basic Commands
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run specific test file
php artisan test tests/Feature/TouristAuthenticationTest.php

# Run with coverage (requires Xdebug)
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel
```

### Filter Tests
```bash
# Run tests matching pattern
php artisan test --filter="authentication"

# Run tests in specific group
php artisan test --group=booking
```

## Test Database Setup

### Environment Configuration
```env
# In .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Migration Issue Fix
To fix the duplicate column issue:

1. **Option A: Remove problematic migration**
```bash
# Delete or rename the duplicate migration file
rm database/migrations/2025_09_09_202537_add_missing_columns_to_bookings_table.php
```

2. **Option B: Use fresh database for tests**
```bash
# Add to your TestCase.php
use Illuminate\Foundation\Testing\DatabaseMigrations;

// This recreates the entire database for each test
```

## Example Test Implementations

### Model Testing Example
```php
// tests/Unit/TouristModelTest.php
it('has correct fillable attributes', function () {
    $tourist = new Tourist();
    $expected = ['name', 'email', 'mobile', 'password', 'otp', 'otp_verified', 'location'];
    expect($tourist->getFillable())->toBe($expected);
});
```

### Feature Testing Example
```php
// tests/Feature/TouristAuthenticationTest.php
it('can register a new tourist', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/register', $userData);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('tourists', ['email' => 'john@example.com']);
});
```

## Testing Best Practices

### 1. Test Organization
- One test file per feature/model
- Group related tests using `describe()`
- Use descriptive test names

### 2. Test Data
- Use factories for creating test data
- Keep tests isolated (each test should work independently)
- Use `RefreshDatabase` trait for feature tests

### 3. Assertions
```php
// Database assertions
$this->assertDatabaseHas('table', ['column' => 'value']);
$this->assertDatabaseMissing('table', ['column' => 'value']);

// Authentication assertions
$this->assertAuthenticated();
$this->assertGuest();

// Response assertions
$response->assertStatus(200);
$response->assertRedirect('/dashboard');
$response->assertSee('Welcome');
$response->assertSessionHasErrors(['email']);
```

## Model Factories

Create factories for consistent test data:

```php
// database/factories/TouristFactory.php
public function definition(): array
{
    return [
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'password' => Hash::make('password'),
        'mobile' => $this->faker->phoneNumber(),
        'location' => $this->faker->city(),
        'otp_verified' => true,
    ];
}
```

## Key Testing Areas for TripMate

### 1. Authentication & Authorization
- Tourist registration/login
- Admin authentication
- Password reset flows
- Email verification

### 2. Hotel Booking System
- Hotel search and filtering
- Booking creation and validation
- Payment processing
- Booking cancellation

### 3. Activity Management
- Activity browsing
- Location-based filtering
- Difficulty and category filtering

### 4. Profile Management
- Profile updates
- Photo uploads
- Password changes

### 5. API Endpoints
- REST API responses
- Authentication middleware
- Input validation
- Error handling

## Continuous Integration

### GitHub Actions Setup
Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mbstring, pdo, sqlite, pdo_sqlite
        
    - name: Copy environment file
      run: cp .env.example .env.testing
      
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress
      
    - name: Generate key
      run: php artisan key:generate --env=testing
      
    - name: Run tests
      run: php artisan test
```

## Performance Testing

### Load Testing with Artillery
```bash
# Install Artillery
npm install -g artillery

# Create test script (artillery.yml)
config:
  target: 'http://localhost:8000'
  phases:
    - duration: 60
      arrivalRate: 10

scenarios:
  - name: "Browse hotels"
    requests:
      - get:
          url: "/tourist/explore"
```

### Database Query Optimization
```php
// In tests, check for N+1 queries
it('avoids N+1 queries when loading bookings', function () {
    $tourist = Tourist::factory()->create();
    Booking::factory()->count(10)->create(['tourist_id' => $tourist->id]);
    
    DB::enableQueryLog();
    
    $response = $this->actingAs($tourist, 'tourist')
                     ->get('/tourist/bookings');
    
    $queries = DB::getQueryLog();
    expect(count($queries))->toBeLessThan(5); // Should be efficient
});
```

## Security Testing

### Test Authentication
```php
it('requires authentication for protected routes', function () {
    $response = $this->get('/tourist/profile');
    $response->assertRedirect('/login');
});

it('prevents CSRF attacks', function () {
    $response = $this->post('/tourist/booking', []);
    $response->assertStatus(419); // CSRF token mismatch
});
```

### Test Authorization
```php
it('prevents users from accessing others data', function () {
    $tourist1 = Tourist::factory()->create();
    $tourist2 = Tourist::factory()->create();
    $booking = Booking::factory()->create(['tourist_id' => $tourist2->id]);
    
    $response = $this->actingAs($tourist1, 'tourist')
                     ->get("/tourist/booking/{$booking->id}");
                     
    $response->assertForbidden();
});
```

## Debugging Tests

### Common Issues
1. **Database state bleeding between tests**
   - Ensure `RefreshDatabase` trait is used
   - Check for static variables or caches

2. **Timezone issues**
   - Set consistent timezone in tests
   - Use Carbon::setTestNow() for time-dependent tests

3. **File uploads in tests**
   - Use `UploadedFile::fake()` for testing
   - Clean up uploaded files after tests

### Debugging Commands
```bash
# Run specific test with verbose output
php artisan test tests/Feature/BookingTest.php --verbose

# Debug test with dd() or dump()
# Add in your test:
dd($response->getContent());
dump($response->status());
```

## Coverage Reports

Generate test coverage reports to identify untested code:

```bash
# Requires Xdebug
php artisan test --coverage-html coverage/

# View coverage in browser
open coverage/index.html
```

## Conclusion

This testing setup provides:
- ✅ Comprehensive test coverage
- ✅ Fast feedback loop
- ✅ Automated CI/CD pipeline
- ✅ Security testing
- ✅ Performance monitoring

Start with the most critical features (authentication, booking) and gradually expand test coverage.