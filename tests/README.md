# Testing Guide for Gravity Form Elementor Widget

This guide explains how to set up and run tests for the Gravity Form Elementor Widget plugin.

## Test Types

### 1. Unit Tests (`tests/unit/`)
- Test individual functions and methods in isolation
- Use Brain Monkey to mock WordPress functions
- Fast execution, no database required
- Located in `tests/unit/`

### 2. Integration Tests (`tests/integration/`)
- Test plugin functionality with WordPress environment
- Require WordPress test database
- Test actual plugin behavior in WordPress context
- Located in `tests/integration/`

## Setup Instructions

### Prerequisites
- PHP 7.4 or higher
- Composer
- MySQL/MariaDB (for integration tests)
- WordPress test environment

### 1. Install Dependencies

```bash
composer install
```

### 2. Set Up WordPress Test Environment

```bash
# Install WordPress test suite
# Usage: <db-name> <db-user> <db-pass> [db-host] [wp-version]
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

**Note:** Adjust database credentials according to your local setup.

### 3. Configure Test Database

Make sure you have a MySQL/MariaDB server running and create a test database:

```sql
CREATE DATABASE wordpress_test;
GRANT ALL PRIVILEGES ON wordpress_test.* TO 'root'@'localhost';
```

## Running Tests

### Run All Tests
```bash
composer test
# or
vendor/bin/phpunit
```

### Run Only Unit Tests
```bash
composer test:unit
# or
vendor/bin/phpunit --testsuite="Unit Tests"
```

### Run Only Integration Tests
```bash
composer test:integration
# or
vendor/bin/phpunit --testsuite="Integration Tests"
```

### Run Tests with Coverage Report
```bash
composer test:coverage
# or
vendor/bin/phpunit --coverage-html tests/coverage/html
```

## Test Structure

### Unit Tests
- `PluginTest.php` - Tests main plugin functionality
- `WidgetTest.php` - Tests Elementor widget class

### Integration Tests
- `PluginIntegrationTest.php` - Tests plugin integration with WordPress
- `WidgetRenderingTest.php` - Tests widget rendering and output

### Mocks
- `tests/mocks/elementor-mock.php` - Mock Elementor classes
- `tests/mocks/gravity-forms-mock.php` - Mock Gravity Forms classes

## Writing New Tests

### Unit Test Example
```php
<?php
namespace GravityFormElementor\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;

class MyTest extends TestCase {
    
    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
        
        // Mock WordPress functions
        Functions\when('esc_html__')->returnArg();
    }

    protected function tearDown(): void {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function test_my_function() {
        // Your test code here
        $this->assertTrue(true);
    }
}
```

### Integration Test Example
```php
<?php
namespace GravityFormElementor\Tests\Integration;

use WP_UnitTestCase;

class MyIntegrationTest extends WP_UnitTestCase {
    
    public function test_wordpress_integration() {
        // Test with real WordPress functions
        $this->assertTrue(function_exists('wp_enqueue_script'));
    }
}
```

## Testing Best Practices

### For WordPress Plugins:
1. **Test plugin activation/deactivation**
2. **Test dependency checking**
3. **Test admin notices**
4. **Test hooks and filters**
5. **Test database operations**

### For Elementor Widgets:
1. **Test widget registration**
2. **Test widget properties (name, title, icon, etc.)**
3. **Test control registration**
4. **Test widget rendering**
5. **Test responsive behavior**

### For Gravity Forms Integration:
1. **Test form retrieval**
2. **Test form settings inheritance**
3. **Test form rendering**
4. **Test form submission handling**
5. **Test field customization**

## Continuous Integration

You can integrate these tests with CI/CD pipelines:

### GitHub Actions Example
```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:5.7
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: wordpress_test
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '7.4'
        extensions: mysql
        
    - name: Install dependencies
      run: composer install
      
    - name: Setup WordPress test environment
      run: bash bin/install-wp-tests.sh wordpress_test root root 127.0.0.1 latest
      
    - name: Run tests
      run: composer test
```

## Troubleshooting

### Common Issues:

1. **"Could not find wp-tests-config.php"**
   - Run the install script: `bash bin/install-wp-tests.sh`

2. **Database connection errors**
   - Check MySQL/MariaDB is running
   - Verify database credentials
   - Ensure test database exists

3. **Class not found errors**
   - Run `composer install` to install dependencies
   - Check autoloader paths in `composer.json`

4. **Mock-related errors**
   - Ensure Brain Monkey is properly set up in test bootstrap
   - Check mock classes are loaded correctly

### Debug Mode
Add this to your test to enable debug output:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Coverage Reports

After running tests with coverage, open `tests/coverage/html/index.html` in your browser to view detailed coverage reports.

## Contributing

When adding new features:
1. Write tests first (TDD approach)
2. Ensure all tests pass
3. Maintain good test coverage (aim for >80%)
4. Update this documentation if needed
