# Gravity Form Elementor Widget

A WordPress plugin that adds a customizable widget for Gravity Forms to Elementor, allowing you to seamlessly integrate and style Gravity Forms in your Elementor pages.

## Features

- **Form Settings Integration**: Inherit settings from Gravity Forms or selectively override them
- **Extensive Styling Options**: Customize every aspect of your forms including inputs, labels, checkboxes, and more
- **Custom Form Controls**: Show/hide title, description, use AJAX submissions, and more
- **Advanced Styling**: Custom checkbox and radio button styling with visual controls
- **Responsive Design**: Forms adapt beautifully to all screen sizes
- **Developer-Friendly**: CSS classes for override states and debugging

## Requirements

- **Elementor** (version 3.0.0 or higher)
- **Gravity Forms** (any version)
- **PHP** version 7.4 or higher
- **WordPress** (compatible with current versions)

## Installation

1. Upload the plugin files to the `/wp-content/plugins/gravityfromelementor` directory
2. Ensure Elementor and Gravity Forms are installed and activated
3. Activate the plugin through the 'Plugins' screen in WordPress
4. The Gravity Form widget will be available in Elementor under the "Impact Hub Elements" and "Gravity Forms" categories

## Usage

### Basic Usage

1. Edit a page with Elementor
2. Add the "Gravity Form" widget to your page
3. Select a form from the dropdown
4. Configure display options (title, description, AJAX)
5. Style your form using the available styling options
6. Publish your page

### Form Settings Integration

The plugin can inherit settings directly from your Gravity Forms:

1. Select your form in the widget
2. Go to the "Form Settings Integration" section
3. Enable "Inherit Form Settings"
4. Optionally override specific settings as needed

### Available Form Settings Overrides

- **Label Placement**: Above, left, or right of inputs
- **Description Placement**: Above or below inputs
- **Sub-Label Placement**: For multi-input fields like Name
- **Required Indicators**: Text, asterisk, or custom indicators
- **Validation Summary**: Show/hide error summary at form top
- **Animations**: Enable/disable conditional logic animations

### Styling Options

The widget provides extensive styling controls for:

- **Inputs**: Padding, margins, borders, colors, typography
- **Labels**: Position, display, margins, typography
- **Checkboxes & Radio Buttons**: Custom styling with visual controls
- **Submit Button**: Full styling control
- **Sections**: Style section headers and dividers
- **Consent Fields**: Special styling for consent checkboxes
- **Fieldsets**: Show/hide fieldset borders

## Understanding Label Controls

The widget provides two different ways to control labels:

1. **Label Placement** (Form Settings): Controls the structural layout (above, left, right)
2. **Label Display** (Styling): Controls the CSS display property

For best results:
- Use "Label Placement" to control the overall layout
- Use "Auto" for Label Display unless you need specific CSS behavior
- When overriding form settings, the widget will show helpful notices

## Dependency Handling

The plugin includes comprehensive dependency checking:

- Automatically detects if required dependencies are missing
- Displays clear warning messages if dependencies are not met
- Prevents errors by not loading functionality if dependencies are missing
- Checks for minimum required versions of Elementor and PHP

## Testing

This plugin includes comprehensive unit and integration tests. For developers interested in contributing or modifying the plugin, see [TESTING.md](TESTING.md) for detailed testing instructions.

## Support

For support and feature requests, please contact support@impacthub.net.

## Changelog

### Version 1.0.2 (Current)

- Added comprehensive dependency checking for Elementor and Gravity Forms
- Improved error handling and user feedback
- Enhanced security with proper input sanitization
- Added graceful degradation when dependencies are missing
- Improved code organization and documentation
- Added extensive test coverage for reliability