# Quick Test Guide

## TL;DR - Run Tests Now

```bash
# Run all working tests (25 tests, 104 assertions)
vendor/bin/phpunit -c phpunit-unit.xml --filter "BasicTest|ComprehensiveTest"
```

**Expected Result:** `OK (25 tests, 104 assertions)` in under 1 second

## What Gets Tested

✅ **Plugin works correctly**
- Version constants defined
- Functions exist and work
- Dependencies detected properly
- PHP version compatibility

✅ **Widget works correctly**  
- Proper Elementor integration
- Settings management
- Categories and properties
- Method availability

✅ **WordPress integration works**
- Function mocking
- Style registration
- Hook system
- Security functions

✅ **Gravity Forms integration works**
- Form retrieval
- GFAPI functionality
- Form structure validation

## Individual Test Commands

```bash
# Test basic infrastructure (10 tests)
vendor/bin/phpunit -c phpunit-unit.xml --filter BasicTest

# Test complete functionality (15 tests)  
vendor/bin/phpunit -c phpunit-unit.xml --filter ComprehensiveTest

# Test specific functionality
vendor/bin/phpunit -c phpunit-unit.xml --filter test_plugin_configuration
vendor/bin/phpunit -c phpunit-unit.xml --filter test_widget_class
vendor/bin/phpunit -c phpunit-unit.xml --filter test_dependency_checking
```

## When to Run Tests

- ✅ Before making any code changes
- ✅ After making any code changes  
- ✅ Before committing code
- ✅ When debugging issues
- ✅ When adding new features

## Troubleshooting

### If tests fail:
1. Check PHP version (needs 7.4+)
2. Run `composer install` 
3. Check file permissions
4. Verify plugin files exist

### If "command not found":
```bash
# Install dependencies first
composer install

# Then run tests
vendor/bin/phpunit -c phpunit-unit.xml --filter "BasicTest|ComprehensiveTest"
```

## What This Tells You

- ✅ **Green/OK**: Your plugin core functionality works
- ❌ **Red/Errors**: Something broke, check the error messages
- ⚠️ **Warnings**: Minor issues, usually safe to ignore

See `TEST_SUMMARY.md` for complete details on what's tested.
