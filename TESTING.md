# Testing Guide for Gravity Form Elementor Widget

This document provides a comprehensive guide to testing the Gravity Form Elementor Widget plugin. It covers both quick testing for immediate verification and detailed testing procedures for development.

## Quick Start

### Run Basic Tests

```bash
# Install dependencies first
composer install

# Run all working tests (53 tests)
bash bin/run-tests.sh -t unit

# Expected result: OK (53 tests, 205 assertions)
```

## Test Setup

### 1. Install Dependencies

```bash
composer install
```

### 2. Set Up WordPress Test Environment (for Integration Tests)

```bash
# For local development with default settings
./bin/run-tests.sh --setup-wp

# Or manually with custom database settings
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

### 3. Run Different Test Types

```bash
# Run all tests
./bin/run-tests.sh

# Run only unit tests
./bin/run-tests.sh -t unit

# Run only integration tests
./bin/run-tests.sh -t integration

# Run tests with coverage report
./bin/run-tests.sh -c
```

## Test Structure

The plugin includes several test suites:

### Unit Tests

These tests don't require WordPress and run quickly:

- **BasicTest**: Validates testing setup and basic functionality
- **ComprehensiveTest**: Tests all major plugin functionality
- **PluginFunctionsTest**: Tests individual plugin functions
- **WidgetFunctionalityTest**: Tests widget-specific functionality

### Integration Tests

These tests require a WordPress test environment:

- **PluginIntegrationTest**: Tests plugin integration with WordPress
- **FullIntegrationTest**: Tests complete plugin workflow
- **WidgetRenderingTest**: Tests widget rendering in WordPress

## What Gets Tested

### Plugin Core Functionality

- Plugin constants and configuration
- Dependency checking (Elementor, Gravity Forms)
- PHP version compatibility
- WordPress hooks and actions
- Style registration and enqueueing

### Widget Functionality

- Widget properties (name, title, icon)
- Widget categories and keywords
- Form settings retrieval and management
- Form settings integration and overrides
- Widget rendering and error handling

### Gravity Forms Integration

- Form retrieval via GFAPI
- Form settings extraction
- Form rendering with shortcodes
- Form settings overrides via hooks

### WordPress Integration

- Admin notices for missing dependencies
- Widget registration with Elementor
- Style enqueueing
- Error handling and graceful degradation

## Running Specific Tests

### Test Individual Files

```bash
# Test basic functionality
vendor/bin/phpunit --testsuite="Unit Tests" --filter=BasicTest

# Test widget functionality
vendor/bin/phpunit --testsuite="Unit Tests" --filter=WidgetFunctionalityTest
```

### Test Specific Methods

```bash
# Test dependency checking
vendor/bin/phpunit --testsuite="Unit Tests" --filter=test_dependency_checking

# Test widget settings
vendor/bin/phpunit --testsuite="Unit Tests" --filter=test_widget_settings
```

## Test Coverage

The plugin has comprehensive test coverage for core functionality:

- ✅ Plugin initialization and dependency checking
- ✅ Widget registration and basic properties
- ✅ Form settings retrieval and management
- ✅ Style registration and enqueueing
- ✅ Error handling and graceful degradation

### Generate Coverage Reports

```bash
# Generate HTML coverage report
./bin/run-tests.sh -c

# View the report at tests/coverage/html/index.html
```

## Troubleshooting Tests

### Common Issues

1. **Missing Dependencies**
   - Run `composer install` to install PHPUnit and other dependencies

2. **WordPress Test Environment**
   - For integration tests, ensure MySQL is running
   - Check database credentials in `bin/install-wp-tests.sh`

3. **Permission Issues**
   - Ensure test scripts are executable: `chmod +x bin/*.sh`

4. **PHP Version**
   - Tests require PHP 7.4 or higher

### When Tests Fail

1. Read the error message carefully - it often points directly to the issue
2. Check if the test is expecting a specific WordPress function or class
3. Verify that mock classes are properly defined
4. Check for syntax errors in recently modified files

## Best Practices

1. **Run Tests Frequently**
   - Before making changes (to verify current state)
   - After making changes (to catch regressions)
   - Before committing code

2. **Add Tests for New Features**
   - Write tests for any new functionality
   - Update existing tests when changing behavior

3. **Use Test-Driven Development**
   - Write tests before implementing features
   - Use tests to define expected behavior

4. **Keep Tests Fast**
   - Unit tests should run in milliseconds
   - Avoid unnecessary database operations

## Continuous Integration

The plugin uses GitHub Actions for continuous integration:

- Tests run automatically on push and pull requests
- Multiple PHP versions are tested (7.4, 8.0, 8.1, 8.2)
- Test results are reported in the GitHub interface

## Test Files Structure

```
tests/
├── bootstrap-unit.php          # Unit test setup (no WordPress)
├── bootstrap.php               # Integration test setup (with WordPress)
├── TestCase.php                # Base test case class
├── unit/
│   ├── BasicTest.php           # Basic functionality tests
│   ├── ComprehensiveTest.php   # Complete functionality tests
│   ├── PluginFunctionsTest.php # Individual function tests
│   └── WidgetFunctionalityTest.php # Widget-specific tests
├── integration/
│   ├── PluginIntegrationTest.php   # Plugin integration tests
│   ├── FullIntegrationTest.php     # Complete workflow tests
│   └── WidgetRenderingTest.php     # Widget rendering tests
└── mocks/
    ├── elementor-mock.php      # Elementor class mocks
    └── gravity-forms-mock.php  # Gravity Forms class mocks
```

## Future Test Improvements

Areas for future test enhancement:

1. **Browser Testing**
   - Test widget in actual Elementor editor
   - Test form submission and validation

2. **Performance Testing**
   - Memory usage optimization
   - Load time benchmarks

3. **Accessibility Testing**
   - WCAG compliance verification
   - Screen reader compatibility

4. **Compatibility Testing**
   - Different WordPress versions
   - Different Elementor versions
   - Different Gravity Forms versions