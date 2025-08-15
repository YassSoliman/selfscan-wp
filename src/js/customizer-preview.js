/**
 * Customizer Preview Scripts
 * 
 * This file contains JavaScript for the WordPress Customizer preview panel.
 * It handles live preview updates for theme customizations and WPML integration.
 */

import initCustomizerPreview from './modules/customizer-preview-updates';
import initWpmlPreview from './modules/wpml-preview';

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize customizer preview updates
    initCustomizerPreview();
    
    // Initialize WPML preview integration
    initWpmlPreview();
});