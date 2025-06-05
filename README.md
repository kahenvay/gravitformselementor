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

- Customizable Gravity Forms widget for Elementor
- Form selection dropdown populated with available Gravity Forms
- Show/hide form title and description options
- Ajax form submission support
- Custom styling options for inputs, labels, and form elements
- Custom checkbox and radio button styling
- Fieldset visibility controls

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
4. Customize the appearance using the available styling options
5. Publish your page

## Support

For support and feature requests, please contact support@impacthub.net.

## Changelog

### Version 1.0.2

- Added comprehensive dependency checking for Elementor and Gravity Forms
- Improved error handling and user feedback
- Enhanced security with proper input sanitization
- Added graceful degradation when dependencies are missing
- Improved code organization and documentation
