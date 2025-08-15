/**
 * WPML Preview Module
 * 
 * Handles WPML integration in the customizer preview panel.
 * Updates multilingual content when language changes occur.
 */

export default function initWpmlPreview() {
	// Ensure jQuery and wp.customize are available
	if (typeof jQuery === 'undefined' || typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	const $ = jQuery;
	const api = wp.customize;

	// Listen for changes to multilingual settings
	api('footer_text', function(value) {
		value.bind(function(newValue) {
			$('.footer__text p').html(newValue);
		});
	});
	
	api('copyright_text', function(value) {
		value.bind(function(newValue) {
			$('.footer__copy').html(newValue);
		});
	});
	
	// Listen for language changes in preview
	api('wpml_customizer_language', function(value) {
		value.bind(function(newLanguage) {
			// This will trigger a preview refresh with the new language
			// The refresh is handled by the control script
		});
	});
}