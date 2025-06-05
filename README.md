# Gravity Form Elementor Widget

A WordPress plugin that adds a customizable widget for Gravity Forms to Elementor.

## Requirements

This plugin requires the following dependencies to be installed and activated:

### Required Plugins

- **Elementor** (version 3.0.0 or higher)
- **Gravity Forms** (any version)

### System Requirements

- **PHP** version 7.4 or higher
- **WordPress** (compatible with current versions)

## Installation

1. Upload the plugin files to the `/wp-content/plugins/gravityfromelementor` directory
2. Ensure Elementor and Gravity Forms are installed and activated
3. Activate the plugin through the 'Plugins' screen in WordPress
4. The Gravity Form widget will be available in Elementor under the "Impact Hub Elements" and "Gravity Forms" categories

## Features

- **Form Settings Integration**: Inherit settings from Gravity Forms or selectively override them
- **Intelligent Customization**: Clear indicators when overriding form settings with educational notices
- Customizable Gravity Forms widget for Elementor
- Form selection dropdown populated with available Gravity Forms
- Show/hide form title and description options
- Ajax form submission support
- **Enhanced Form Controls**: Label placement, description placement, sub-label placement overrides
- **Advanced Settings**: Required indicators, validation summary, animation controls
- Custom styling options for inputs, labels, and form elements
- Custom checkbox and radio button styling
- Fieldset visibility controls
- **Developer-Friendly**: CSS classes for override states and debugging

## New: Form Settings Integration

This plugin now includes powerful form settings integration that allows you to:

### **Inherit Form Settings**

- Automatically use your Gravity Form's native settings (label placement, descriptions, etc.)
- Maintain consistency across different pages and widgets
- Reduce configuration time and potential conflicts

### **Selective Overrides**

- Override specific settings while keeping others inherited
- Clear visual indicators when settings are being overridden
- Educational notices to help you understand the integration

### **Supported Settings**

- **Label Placement**: Above, left, or right of inputs
- **Description Placement**: Above or below inputs
- **Sub-Label Placement**: For multi-input fields like Name
- **Required Indicators**: Text, asterisk, or custom indicators
- **Validation Summary**: Show/hide error summary at form top
- **Animations**: Enable/disable conditional logic animations

See `FORM_SETTINGS_INTEGRATION.md` for detailed documentation.

## Dependency Checks

The plugin includes comprehensive dependency checking:

- **Automatic Detection**: The plugin automatically detects if required dependencies are missing
- **Admin Notices**: Clear warning messages are displayed in the WordPress admin if dependencies are not met
- **Graceful Degradation**: The plugin will not load its functionality if dependencies are missing, preventing errors
- **Version Compatibility**: Checks for minimum required versions of Elementor and PHP

## Error Handling

If dependencies are missing, users will see:

- Admin notices explaining which plugins need to be installed
- Error messages in the Elementor editor if forms cannot be loaded
- Helpful guidance on resolving dependency issues

## Usage

1. Edit a page with Elementor
2. Add the "Gravity Form" widget to your page
3. Select a form from the dropdown
4. **New**: Enable "Inherit Form Settings" to use your form's native configuration
5. **New**: Optionally override specific settings as needed (clear indicators will show what's overridden)
6. Customize the appearance using the available styling options
7. Publish your page

## Support

For support and feature requests, please contact support@impacthub.net.

## Changelog

### Version 1.1.0 (Latest)

- **NEW**: Form Settings Integration - Inherit or override Gravity Forms settings
- **NEW**: Selective override system with clear visual indicators
- **NEW**: Support for label placement, description placement, and sub-label placement overrides
- **NEW**: Required indicator customization (text, asterisk, custom)
- **NEW**: Validation summary and animation setting overrides
- **NEW**: Educational notices and override indicators in Elementor editor
- **NEW**: CSS classes for debugging and advanced styling
- Enhanced user experience with better guidance and explanations
- Improved backward compatibility - existing widgets continue to work unchanged
- Added comprehensive documentation for new features

### Version 1.0.2

- Added comprehensive dependency checking for Elementor and Gravity Forms
- Improved error handling and user feedback
- Enhanced security with proper input sanitization
- Added graceful degradation when dependencies are missing
- Improved code organization and documentation
