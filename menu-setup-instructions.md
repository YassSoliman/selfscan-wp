# SelfScan Menu Setup Instructions

This document provides instructions for setting up the custom navigation menus in the SelfScan WordPress theme.

## Available Menu Locations

The theme has two menu locations:

1. **Primary Menu** - The main navigation menu
2. **Social Media Menu** - For social media icons in the header

## Setting Up the Primary Menu

1. Go to WordPress Admin → Appearance → Menus
2. Create a new menu or edit an existing one
3. Add your desired pages to the menu (recommended: Pricing, FAQ, Get Started)
4. If you want an item to appear as a button (like "Get Started"):
   - Expand the menu item by clicking on the arrow
   - Click on "CSS Classes" in the Screen Options at the top if it's not visible
   - Add the class `button` to the CSS Classes field
5. Under "Menu Settings", check the "Primary Menu" location
6. Save the menu

### Special Features:
- Menu items with the CSS class `button` will automatically receive button styling
- The currently active page will show the active state styling

## Setting Up the Social Media Menu

1. Go to WordPress Admin → Appearance → Menus
2. Create a new menu or edit an existing one
3. Add Custom Links for your social media platforms:
   - URL: Your social media profile URL
   - Link Text: Name of the platform (e.g., "LinkedIn", "Facebook")
4. Under "Menu Settings", check the "Social Media Menu" location
5. Save the menu
6. After saving, expand each menu item to see additional fields
7. You'll see an "Icon" field where you can upload or select an image from the media library
8. Upload SVG or other image files to use as icons

### Troubleshooting Media Upload:
- If the "Add Image" button doesn't seem to work initially, try refreshing the page after saving the menu
- Some browsers may require you to click once on the menu item to expand it first, then click the "Add Image" button
- Make sure you have the latest version of ACF installed
- Try using a different browser if issues persist

### Notes:
- The theme works best with SVG icons for social media
- If no icon is selected via ACF, the system will try to find a matching icon in the `/img/icons/` directory based on the menu item name (e.g., "LinkedIn" will look for `/img/icons/linkedin.svg`)
- The current social icon files in use are:
  - `/img/icons/linkedin.svg`
  - `/img/icons/facebook.svg`

## Requirements

- Advanced Custom Fields (ACF) plugin must be installed and activated
- If ACF is not active, the theme will fall back to using the default icons based on menu item name

## Fallback Behavior

If no menus are assigned to the locations:
- The Primary Menu will show the default hardcoded menu items
- The Social Media Menu will show the default hardcoded social icons

## Customization

To add new social icons:

### Option 1: Using ACF (Recommended)
1. Create a menu item in the Social Media Menu
2. Upload your icon image using the ACF field 

### Option 2: Using the theme directory
1. Add the SVG icon to the `/img/icons/` directory
2. Use a filename that matches the menu item name (lowercase)
   - For example: For a "Twitter" menu item, save as `twitter.svg` 