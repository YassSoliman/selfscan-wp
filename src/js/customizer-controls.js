/**
 * Customizer Controls Scripts
 * 
 * This file contains JavaScript for the WordPress Customizer controls panel.
 * It handles WPML language switching and repeater controls.
 */

import initRepeaterControl from './modules/repeater-control';
import initWpmlControls from './modules/wpml-controls';

// Initialize when customizer is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize repeater controls
    initRepeaterControl();
    
    // Initialize WPML controls
    initWpmlControls();
});