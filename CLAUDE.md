# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress theme called "SelfScan" built on the Underscores (_s) starter theme framework. It's a custom theme for a WordPress site with custom templates, Advanced Custom Fields (ACF) integration, and Webpack-based asset compilation.

## Development Commands

### Build Assets
- `npm run build` - Build production assets
- `npm run dev` - Build development assets
- `npm start` or `npm run start` - Watch files and rebuild on changes

### PHP Code Quality
- `composer lint:wpcs` - Check PHP files against WordPress Coding Standards
- `composer lint:php` - Check all PHP files for syntax errors
- `composer make-pot` - Generate translation file in languages/ directory

## Architecture

### Asset Pipeline
The theme uses Webpack for asset compilation with multiple entry points:
- **Main Entry**: `src/js/main.js` → `build/js/main.js` (site frontend functionality)
- **Customizer Controls**: `src/js/customizer-controls.js` → `build/js/customizer-controls.js` (customizer panel scripts)
- **Customizer Preview**: `src/js/customizer-preview.js` → `build/js/customizer-preview.js` (live preview updates)
- SCSS files compiled from `src/scss/` → `build/css/main.css`
- Swiper.js library included for sliders/carousels

### Theme Structure
- **Custom Page Templates**: Located in `page-templates/` (Home, Pricing, FAQ)
- **Template Parts**: Reusable components in `template-parts/`
- **Theme Includes**: All functionality files in `inc/` are auto-loaded via `recursive_file_search()` function
- **ACF Integration**: Multiple ACF field groups for templates and menus (requires ACF plugin)
- **Custom Walkers**: Custom nav menu walkers for primary and footer menus

### Key Features
- **Custom Menus**: Primary, Social Media, Footer, and Footer Right menus
- **ACF-Powered Templates**: Home, Pricing, and FAQ pages use ACF for content management
- **Custom Post Types**: Defined in `inc/custom-post-types.php`
- **Google Tag Manager**: Integrated in functions.php
- **Icon System**: SVG icons stored in `img/icons/`
- **WPML Integration**: Full multilingual support with desktop dropdown and mobile button group language switcher, translatable content
- **WordPress Customizer**: Enhanced with WPML integration and custom controls

### Important Files
- `functions.php`: Core theme setup, menu registration, script enqueueing, GTM integration, AJAX handlers
- `webpack.config.js`: Asset compilation configuration with multiple entry points
- `inc/class-selfscan-walker-nav-menu.php`: Custom primary menu walker with button styling support
- `inc/class-selfscan-walker-footer-menu.php`: Custom footer menu walker
- `inc/acf-*.php`: ACF field definitions and template logic
- `inc/wpml-language-switcher.php`: WPML helper functions and language switcher rendering
- `inc/class-wpml-customizer-integration.php`: WPML customizer integration and language switching
- `inc/class-customizer-repeater-control.php`: Custom repeater control for partner logos
- `inc/customizer.php`: WordPress Customizer configuration and controls

## Development Notes

### Menu System
- Menu items with CSS class `button` receive special button styling
- Social menu supports custom icons via ACF or fallback to `img/icons/` directory
- See `menu-setup-instructions.md` for detailed menu configuration

### JavaScript Modules

#### Frontend Modules (`src/js/`)
- `main.js`: Main entry point importing all frontend modules
- `accordion.js`: FAQ accordion functionality
- `nav.js`: Navigation menu interactions
- `swiper.js`: Slider initialization
- `tracking.js`: Analytics tracking
- `language-switcher.js`: WPML language switcher frontend functionality

#### Customizer Modules (`src/js/`)
- `customizer-controls.js`: Entry point for customizer panel scripts
- `customizer-preview.js`: Entry point for live preview updates
- `modules/repeater-control.js`: Partner logo repeater control functionality
- `modules/wpml-controls.js`: WPML language switching in customizer
- `modules/wpml-preview.js`: WPML preview integration
- `modules/customizer-preview-updates.js`: Live preview updates for theme options

## WPML Integration

### Language Switcher
- **Desktop**: Dropdown language switcher with globe.svg and arrow-down.svg icons
- **Mobile**: Button group interface with horizontal layout - more touch-friendly
- **Design**: Consistent brand colors with blue accents for active language
- **Locations**: Added to both desktop navigation and mobile menu
- **Implementation**: Context-aware rendering with different markup for mobile vs desktop

### Customizer Integration
- **Language Switcher**: Custom control in WordPress Customizer for real-time language switching
- **Multilingual Fields**: Footer text and copyright text are translatable per language
- **Storage**: Default language uses standard theme_mods, other languages use language-specific options
- **Preview**: Real-time preview updates when switching languages in customizer

### Partner Logo Repeater
- **Functionality**: Unlimited partner logos with individual width/height controls
- **Units**: REM (default), PX, %, AUTO with dropdown selector
- **Styling**: Dimensions apply to container div, maintaining image aspect ratios
- **Data Format**: JSON storage with backward compatibility for old format
- **Preview**: Live updates in customizer with proper image loading via WordPress media API

### Translatable Content
- Footer text (`footer_text`)
- Copyright text (`copyright_text`)
- Menu items (via WPML's standard menu translation)
- Page/post content (via WPML core functionality)

## WordPress Customizer Enhancements

### Custom Controls
- **Repeater Control**: For partner logos with media selection and dimension controls
- **Language Switcher Control**: For switching languages within customizer
- **Enhanced Styling**: Proper CSS for control layouts and media previews

### Live Preview Features
- Partner logo changes (add/remove/resize) update instantly
- Footer text and copyright changes update without page refresh
- Language switching updates preview URL and content
- Proper image loading with fallback mechanisms

### Dependencies
- WordPress 5.6+
- PHP 5.6+
- Advanced Custom Fields (ACF) plugin for full functionality
- WPML plugin for multilingual functionality (optional - degrades gracefully)
- Node.js and npm for development
- This project URL is : http://localhost:10008/blog/\
The admin login url for this project is : http://localhost:10008/wp-admin/?localwp_auto_login=1