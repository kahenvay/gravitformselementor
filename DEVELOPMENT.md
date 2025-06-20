# Development Guide for Gravity Form Elementor Widget

This document provides technical details and development guidance for the Gravity Form Elementor Widget plugin. It's intended for developers who are maintaining or extending the plugin.

## Plugin Architecture

### Core Components

1. **Main Plugin File** (`index.php`)
   - Plugin initialization
   - Dependency checking
   - Widget registration
   - Style registration

2. **Widget Implementation** (`widgets/gf-widget.php`)
   - Elementor widget class
   - Form settings integration
   - Widget controls
   - Rendering logic

3. **Styles** (`assets/css/style.css`)
   - Custom form styling
   - Custom checkbox/radio styling
   - Form settings integration styling
   - Responsive design

### Key Functions

- `gf_elementor_widget_check_dependencies()`: Checks for Elementor and Gravity Forms
- `gf_elementor_widget_check_elementor_version()`: Validates Elementor version
- `gf_elementor_widget_check_php_version()`: Validates PHP version
- `gf_elementor_widget_init()`: Initializes the plugin
- `register_gravity_form_elementor_widget()`: Registers the widget with Elementor
- `gf_register_widget_styles()`: Registers and enqueues styles

### Widget Methods

- `get_name()`: Returns widget name
- `get_title()`: Returns widget title
- `get_icon()`: Returns widget icon
- `get_categories()`: Returns widget categories
- `get_keywords()`: Returns widget keywords
- `get_forms_select_options()`: Gets available Gravity Forms
- `get_form_settings()`: Retrieves form settings from Gravity Forms
- `register_controls()`: Registers widget controls
- `render()`: Renders the widget

## Form Settings Integration

The widget can inherit settings from Gravity Forms and selectively override them:

### Implementation Details

1. **Settings Retrieval**
   - `get_form_settings()` method retrieves form settings using GFAPI
   - Settings include label placement, description placement, etc.

2. **Override Application**
   - `apply_form_settings_overrides()` applies overrides using Gravity Forms hooks
   - `modify_form_settings()` modifies the form object before rendering
   - `modify_required_indicator_content()` handles special case for required indicators

3. **CSS Classes**
   - `get_form_override_classes()` generates CSS classes for active overrides
   - Classes are added to the widget wrapper for styling and debugging

### Form Settings Flow

1. User selects a form in the widget
2. Widget retrieves form settings using GFAPI
3. User enables "Inherit Form Settings"
4. User optionally enables specific overrides
5. When rendering, widget applies overrides using Gravity Forms hooks
6. CSS classes are added to indicate active overrides

## Widget Controls

The widget includes several control sections:

1. **Main Controls**
   - Form selection
   - Title and description display
   - AJAX submission

2. **Form Settings Integration**
   - Master toggle for inheriting settings
   - Individual override toggles
   - Override value controls

3. **Advanced Form Settings**
   - Required indicator overrides
   - Validation summary overrides
   - Animation overrides

4. **Styling Controls**
   - Input styling
   - Label styling
   - Checkbox and radio styling
   - Submit button styling
   - Section styling

## CSS Architecture

The CSS is organized into several sections:

1. **Custom Checkbox/Radio Styling**
   - Uses CSS pseudo-elements for custom appearance
   - Maintains accessibility with hidden inputs

2. **Form Settings Integration Styling**
   - Label placement styles (top, left, right)
   - Responsive adjustments for different screen sizes

3. **Widget Error Styling**
   - Styles for error messages when dependencies are missing

4. **Override Indicators**
   - Styles for showing which settings are overridden in editor

## Development Workflow

### Setting Up Development Environment

1. Clone the repository
2. Run `composer install` to install dependencies
3. Set up WordPress test environment if needed

### Making Changes

1. Run tests to verify current functionality
2. Make changes to the code
3. Run tests again to ensure nothing broke
4. Add new tests for new functionality

### Coding Standards

The plugin follows WordPress coding standards:

- Use snake_case for function names
- Use CamelCase for class names
- Add PHPDoc comments to all functions and classes
- Follow WordPress security best practices

## Areas for Improvement

### Code Structure

1. **Namespace Implementation**
   - Add PHP namespaces for better organization
   - Prevent conflicts with other plugins

2. **Class-Based Architecture**
   - Refactor procedural code to class-based
   - Improve organization and maintainability

3. **Separate Admin and Frontend Code**
   - Split admin-related code from frontend code
   - Improve performance and organization

### Performance

1. **CSS Loading**
   - Load CSS only when widget is used
   - Minify CSS for production

2. **Conditional Loading**
   - Load Gravity Forms-related code only when needed
   - Reduce unnecessary processing

### Features

1. **JavaScript Enhancement**
   - Add JavaScript for dynamic form updates
   - Improve user experience

2. **Accessibility Improvements**
   - Ensure all form elements are fully accessible
   - Add ARIA attributes where needed

3. **Additional Form Controls**
   - Add more controls for form styling and behavior
   - Support more Gravity Forms features

## Testing

See [TESTING.md](TESTING.md) for detailed testing instructions.

## Release Process

1. Update version number in:
   - `index.php` (plugin header)
   - `define( 'GF_ELEMENTOR_WIDGET_VERSION', '1.0.2' );`

2. Run all tests to ensure everything works

3. Update README.md with new features and changes

4. Create a new release tag in Git

5. Push changes and tag to repository

## Troubleshooting Common Issues

### Widget Not Appearing in Elementor

- Check that Elementor is activated
- Check that Gravity Forms is activated
- Check PHP error logs for any issues

### Form Settings Not Inheriting

- Check that the form ID is correct
- Check that GFAPI is returning the form correctly
- Verify that hooks are being applied

### Styling Issues

- Check for CSS conflicts with theme
- Inspect element to see which styles are being applied
- Check if form settings overrides are active

## Support and Resources

- **Documentation**: See README.md for user documentation
- **Testing**: See TESTING.md for testing instructions
- **GitHub**: Submit issues and pull requests on GitHub
- **Support**: Contact support@impacthub.net for assistance