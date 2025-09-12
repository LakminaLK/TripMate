# Migration Conflict Fix

The duplicate column error is caused by:
- `2025_01_13_000000_create_bookings_table.php` creates `tourist_id` column
- `2025_09_09_202537_add_missing_columns_to_bookings_table.php` tries to add it again

## Solutions:

### Option 1: Remove Duplicate Migration (Recommended)
```bash
# Delete the problematic migration
rm database/migrations/2025_09_09_202537_add_missing_columns_to_bookings_table.php
```

### Option 2: Modify Migration to Check Column Existence
Edit `2025_09_09_202537_add_missing_columns_to_bookings_table.php`:

```php
public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        if (!Schema::hasColumn('bookings', 'tourist_id')) {
            $table->foreignId('tourist_id')->nullable()->constrained('tourists')->onDelete('cascade')->after('id');
        }
        // ... repeat for other columns
    });
}
```

### Option 3: Use Database Migrations for Tests
In your `TestCase.php`:

```php
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations; // This recreates DB for each test
}
```

### Quick Fix Command
```bash
# Reset all migrations
php artisan migrate:fresh

# Or for testing only
php artisan migrate:fresh --env=testing
```