# Form Settings Integration Guide

## Overview

The Gravity Forms Elementor Widget now includes powerful form settings integration that allows you to inherit settings from your Gravity Forms or selectively override them. This provides better consistency between your forms and more granular control when needed.

## Key Features

### 1. **Inherit Form Settings**

- **Master Toggle**: Enable/disable form settings inheritance
- **Automatic Detection**: Widget automatically detects and uses your form's native settings
- **Backward Compatibility**: Existing widgets continue to work as before

### 2. **Selective Overrides**

You can override specific form settings while keeping others inherited:

#### **Label Placement Override**

- Override how field labels are positioned relative to inputs
- Options: Above inputs, Left aligned, Right aligned
- Inherits from form's `labelPlacement` setting by default
- **Note**: This affects the HTML structure and layout, not just CSS styling
- Works with responsive design (mobile devices stack vertically)

#### **Description Placement Override**

- Control where field descriptions appear
- Options: Above inputs, Below inputs
- Inherits from form's `descriptionPlacement` setting by default

#### **Sub-Label Placement Override**

- Affects multi-input fields (like Name fields)
- Options: Above inputs, Below inputs
- Inherits from form's `subLabelPlacement` setting by default

#### **Required Indicator Override**

- Customize how required fields are marked
- Options: Text "(Required)", Asterisk "\*", Custom indicator
- Inherits from form's `requiredIndicator` setting by default

#### **Validation Summary Override**

- Show/hide validation error summary at top of form
- Inherits from form's `validationSummary` setting by default

#### **Animation Override**

- Enable/disable slide animations for conditional logic
- Inherits from form's `enableAnimation` setting by default

## How It Works

### 1. **Form Settings Detection**

The widget uses `GFAPI::get_form()` to retrieve your form's configuration and extracts relevant settings like:

- `labelPlacement`
- `descriptionPlacement`
- `subLabelPlacement`
- `requiredIndicator`
- `validationSummary`
- `enableAnimation`

### 2. **Override Application**

When overrides are enabled, the widget uses Gravity Forms hooks to modify the form object before rendering:

- `gform_pre_render_{form_id}`
- `gform_pre_validation_{form_id}`
- `gform_pre_submission_filter_{form_id}`
- `gform_admin_pre_render_{form_id}`

**Special Case - Required Indicators:**
Required indicator overrides use the `gform_field_content_{form_id}` filter to modify the actual HTML content of each field, as the required indicator content is generated during field rendering rather than from the form object.

### 3. **Visual Indicators**

- **Editor Notice**: Shows which settings are being overridden (only visible in Elementor editor)
- **CSS Classes**: Adds classes to indicate active overrides for styling/debugging
- **Informational Notices**: Explains when form settings are being inherited

## Important: Label Placement vs Label Display

### **Label Placement** (Form Settings Integration)

- **Purpose**: Controls the structural layout of labels relative to inputs
- **Options**: Above inputs, Left of inputs, Right of inputs
- **Effect**: Changes HTML structure and applies flexbox layouts
- **Responsive**: Automatically stacks on mobile devices
- **Source**: Inherited from Gravity Forms or overridden in widget

### **Label Display** (Styling Controls)

- **Purpose**: Controls the CSS `display` property of labels
- **Options**: Auto (recommended), None, Block, Inline, Flex, etc.
- **Effect**: Only affects CSS display behavior
- **Recommendation**: Use "Auto" unless you need specific CSS behavior
- **Source**: Widget styling controls

### **How They Work Together**

1. **Label Placement** sets up the structural layout (flexbox, positioning)
2. **Label Display** fine-tunes the CSS display property within that structure
3. **"Auto" Display**: Automatically chooses the best display value for the placement
4. **Manual Display**: Override when you need specific CSS behavior

## Usage Instructions

### Step 1: Enable Form Settings Integration

1. Select your Gravity Form in the widget
2. Go to the "Form Settings Integration" section
3. Enable "Inherit Form Settings"

### Step 2: Configure Overrides (Optional)

1. For each setting you want to override:
   - Enable the "Override [Setting Name]" toggle
   - Choose your preferred value
2. The widget will show a notice indicating which settings are overridden

### Step 3: Style as Needed

- Use the existing styling controls for visual customization
- The "Label Display" control now includes helpful notices about form integration
- Override classes are added for advanced CSS targeting

## Benefits

### **For Users**

- **Consistency**: Forms maintain their configured behavior across different pages
- **Flexibility**: Override only what you need to change
- **Clarity**: Clear indication when settings are being overridden
- **Education**: Learn about form settings through the interface

### **For Developers**

- **Maintainability**: Changes to form settings automatically propagate
- **Debugging**: CSS classes indicate which overrides are active
- **Extensibility**: Easy to add new form setting integrations

## Technical Implementation

### New Methods Added:

- `get_form_settings()`: Retrieves form configuration from Gravity Forms
- `get_form_setting_labels()`: Provides human-readable labels for settings
- `apply_form_settings_overrides()`: Applies widget overrides using GF hooks
- `modify_form_settings()`: Modifies form object with override values
- `modify_required_indicator_content()`: Handles required indicator HTML modification via field content filter
- `get_form_override_classes()`: Generates CSS classes for active overrides

### New Controls Added:

- Form Settings Integration section with master toggle
- Individual override toggles for each setting
- Advanced Form Settings section for additional options
- Enhanced notices and descriptions

## Backward Compatibility

- Existing widgets continue to work without changes
- Form settings integration is opt-in via the "Inherit Form Settings" toggle
- All existing styling controls remain functional
- No breaking changes to existing functionality

## Future Enhancements

Potential additions for future versions:

- Integration with more Gravity Forms settings (honeypot, markup version, etc.)
- Field-level setting overrides
- Bulk override management
- Import/export of override configurations
- Integration with Gravity Forms themes and styling
