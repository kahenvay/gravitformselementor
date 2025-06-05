# Testing Setup for Gravity Form Elementor Widget

This document provides a comprehensive guide to setting up and running tests for the Gravity Form Elementor Widget plugin.

## Quick Start

### 1. Install Dependencies
```bash
composer install
```

### 2. Set Up WordPress Test Environment
```bash
# For local development with default settings
./bin/run-tests.sh --setup-wp

# Or manually with custom database settings
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

### 3. Run Tests
```bash
# Run all tests
./bin/run-tests.sh

# Run specific test types
./bin/run-tests.sh --type unit
./bin/run-tests.sh --type integration

# Run with coverage
./bin/run-tests.sh --coverage
```

## Understanding the Test Structure

### Test Types Explained

#### 1. **Unit Tests** (`tests/unit/`)
**Purpose:** Test individual functions and methods in isolation

**What they test:**
- Plugin initialization functions
- Widget class methods
- Form settings retrieval
- Dependency checking
- Version compatibility

**Benefits:**
- Fast execution (no database)
- Isolated testing
- Easy to debug
- Mock external dependencies

**Example:**
```php
public function test_dependency_check_with_missing_elementor() {
    Functions\when('did_action')->with('elementor/loaded')->justReturn(false);
    Functions\when('class_exists')->with('GFForms')->justReturn(true);
    
    $missing = gf_elementor_widget_check_dependencies();
    
    $this->assertContains('Elementor', $missing);
}
```

#### 2. **Integration Tests** (`tests/integration/`)
**Purpose:** Test plugin functionality within WordPress environment

**What they test:**
- Plugin activation/deactivation
- WordPress hooks and filters
- Database interactions
- Widget rendering with real WordPress
- Admin notices
- Style/script registration

**Benefits:**
- Tests real WordPress behavior
- Catches integration issues
- Tests actual plugin workflow

**Example:**
```php
public function test_widget_rendering_in_wordpress() {
    $widget = new \Elementor_GF_Widget();
    $widget->set_settings(['gravity_form' => '1']);
    
    ob_start();
    $widget->render();
    $output = ob_get_clean();
    
    $this->assertStringContainsString('gform_wrapper', $output);
}
```

## Testing WordPress Plugins - Best Practices

### 1. **Plugin Activation Testing**
```php
public function test_plugin_activation() {
    // Test constants are defined
    $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_VERSION'));
    
    // Test functions exist
    $this->assertTrue(function_exists('gf_elementor_widget_init'));
    
    // Test hooks are registered
    $this->assertTrue(has_action('plugins_loaded', 'gf_elementor_widget_init'));
}
```

### 2. **Dependency Testing**
```php
public function test_missing_dependencies() {
    // Mock missing Elementor
    Functions\when('did_action')->with('elementor/loaded')->justReturn(false);
    
    $missing = gf_elementor_widget_check_dependencies();
    $this->assertContains('Elementor', $missing);
}
```

### 3. **Admin Notice Testing**
```php
public function test_admin_notices() {
    ob_start();
    gf_elementor_widget_admin_notice_missing_dependencies();
    $output = ob_get_clean();
    
    $this->assertStringContainsString('notice', $output);
}
```

## Testing Elementor Widgets

### 1. **Widget Registration**
```php
public function test_widget_registration() {
    $widget = new \Elementor_GF_Widget();
    
    $this->assertEquals('gf_widget', $widget->get_name());
    $this->assertEquals('Gravity Form', $widget->get_title());
    $this->assertEquals('eicon-form-horizontal', $widget->get_icon());
}
```

### 2. **Widget Controls**
```php
public function test_widget_controls() {
    $widget = new \Elementor_GF_Widget();
    
    // Test that controls can be registered without errors
    $reflection = new \ReflectionClass($widget);
    if ($reflection->hasMethod('register_controls')) {
        $method = $reflection->getMethod('register_controls');
        $method->setAccessible(true);
        $method->invoke($widget);
        
        $this->assertTrue(true); // No exception thrown
    }
}
```

### 3. **Widget Rendering**
```php
public function test_widget_rendering() {
    $widget = new \Elementor_GF_Widget();
    $widget->set_settings([
        'gravity_form' => '1',
        'show_title' => 'yes'
    ]);
    
    ob_start();
    $widget->render();
    $output = ob_get_clean();
    
    $this->assertStringContainsString('gform_wrapper', $output);
}
```

## Testing Gravity Forms Integration

### 1. **Form Retrieval**
```php
public function test_form_retrieval() {
    // Mock GFAPI
    Functions\when('class_exists')->with('GFAPI')->justReturn(true);
    Functions\when('GFAPI::get_forms')->justReturn([
        ['id' => '1', 'title' => 'Contact Form']
    ]);
    
    $widget = new \Elementor_GF_Widget();
    $options = $this->getPrivateMethod($widget, 'get_forms_select_options')->invoke($widget);
    
    $this->assertArrayHasKey('1', $options);
    $this->assertEquals('Contact Form', $options['1']);
}
```

### 2. **Form Settings Integration**
```php
public function test_form_settings_inheritance() {
    $mock_form = [
        'id' => '1',
        'labelPlacement' => 'left_label',
        'descriptionPlacement' => 'above'
    ];
    
    Functions\when('GFAPI::get_form')->with('1')->justReturn($mock_form);
    
    $widget = new \Elementor_GF_Widget();
    $settings = $this->getPrivateMethod($widget, 'get_form_settings')->invoke($widget, '1');
    
    $this->assertEquals('left_label', $settings['labelPlacement']);
    $this->assertEquals('above', $settings['descriptionPlacement']);
}
```

## Common Testing Patterns

### 1. **Mocking WordPress Functions**
```php
// In unit tests with Brain Monkey
Functions\when('esc_html__')->returnArg();
Functions\when('wp_enqueue_style')->justReturn(true);
Functions\when('plugins_url')->justReturn('http://example.com/plugin');
```

### 2. **Testing Private Methods**
```php
$reflection = new \ReflectionClass($object);
$method = $reflection->getMethod('private_method');
$method->setAccessible(true);
$result = $method->invoke($object, $param1, $param2);
```

### 3. **Capturing Output**
```php
ob_start();
$widget->render();
$output = ob_get_clean();

$this->assertStringContainsString('expected-content', $output);
```

### 4. **Testing Hooks and Filters**
```php
$this->assertTrue(has_action('wp_enqueue_scripts', 'my_function'));
$this->assertEquals(10, has_filter('the_content', 'my_filter'));
```

## Troubleshooting

### Common Issues:

1. **"Class 'WP_UnitTestCase' not found"**
   - Run WordPress test setup: `./bin/run-tests.sh --setup-wp`

2. **Database connection errors**
   - Check MySQL is running: `mysql -u root -p`
   - Verify database exists: `SHOW DATABASES;`

3. **"Brain\Monkey not found"**
   - Install dependencies: `composer install`

4. **Permission errors**
   - Make scripts executable: `chmod +x bin/*.sh`

### Debug Tips:

1. **Enable debug output:**
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

2. **Use var_dump in tests:**
   ```php
   var_dump($actual_value);
   $this->fail('Debug stop');
   ```

3. **Check test database:**
   ```bash
   mysql -u root -p wordpress_test
   SHOW TABLES;
   ```

## Continuous Integration

The plugin includes GitHub Actions workflow (`.github/workflows/tests.yml`) that:
- Runs tests on multiple PHP versions (7.4, 8.0, 8.1, 8.2)
- Tests against multiple WordPress versions
- Generates code coverage reports
- Checks coding standards

## Coverage Reports

After running tests with coverage:
```bash
./bin/run-tests.sh --coverage
```

Open `tests/coverage/html/index.html` in your browser to view detailed coverage reports.

## Contributing

When adding new features:
1. Write tests first (TDD)
2. Ensure all tests pass
3. Maintain >80% code coverage
4. Follow WordPress coding standards
