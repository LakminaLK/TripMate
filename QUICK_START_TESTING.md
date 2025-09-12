# Quick Start Testing Guide

## Immediate Testing (No Database Required)

Run these tests immediately - they don't require any database setup:

```bash
# Test basic application functionality
php artisan test tests/Unit/BasicApplicationTest.php

# Test basic routes
php artisan test tests/Feature/BasicRouteTest.php
```

## Expected Output

You should see something like:

```
   PASS  Tests\Unit\BasicApplicationTest
  ✓ has correct app name
  ✓ has correct timezone  
  ✓ can hash passwords correctly
  ✓ can validate email format
  ✓ can format Sri Lankan phone numbers
  ✓ can handle Sri Lankan currency formatting
  ✓ can validate booking date ranges
  ✓ can calculate booking totals
  ✓ can validate difficulty levels
  ✓ can validate activity categories
  ✓ can validate Sri Lankan coordinates

   PASS  Tests\Feature\BasicRouteTest
  ✓ can access home page
  ✓ can access tourist registration page
  ✓ can access tourist login page
  ✓ can access hotel registration page
  ✓ can access hotel login page
  ✓ can access admin login page
  ✓ redirects unauthorized users from protected routes
  ✓ returns 404 for non-existent routes
  ✓ can access activities page
  ✓ can access hotels page

  Tests:  21 passed
  Time:   0.5s
```

## Next Steps

1. **Start with these basic tests** to verify your testing setup works
2. **Fix the migration issue** using the MIGRATION_FIX.md guide
3. **Run the full test suite** with database tests after migration fix

## Common Issues

If tests fail, check:
- Laravel is properly installed
- PHP extensions are loaded
- Web server is running (if route tests fail)
- Routes are properly defined in your routes files

## Success Indicators

✅ **All basic tests pass** - Your Laravel installation is working
✅ **Route tests pass** - Your application routes are accessible  
✅ **No fatal errors** - Testing framework is properly configured

Once these pass, you're ready to tackle the database tests!