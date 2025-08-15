/**
 * WPML Customizer Controls Module
 * 
 * Handles WPML language switching functionality in the customizer controls panel.
 * Allows real-time language switching and updates multilingual field values.
 */

export default function initWpmlControls() {
	// Ensure jQuery and wp.customize are available
	if (typeof jQuery === 'undefined' || typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	const $ = jQuery;
	const api = wp.customize;

	// Wait for the customizer to be ready
	api.bind('ready', function() {
		
		// Handle language switcher button clicks
		$(document).on('click', '.wpml-lang-button', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var language = $button.data('language');
			var $container = $button.closest('.wpml-language-switcher-control');
			var $notice = $container.find('.wpml-language-notice');
			
			// Don't do anything if already active
			if ($button.hasClass('active')) {
				return;
			}
			
			// Show loading notice
			$notice.show();
			
			// Update active state
			$('.wpml-lang-button').removeClass('active');
			$button.addClass('active');
			
			// Make AJAX request to change language
			$.ajax({
				url: wpmlCustomizer.ajaxUrl,
				type: 'POST',
				data: {
					action: 'wpml_customizer_change_language',
					language: language,
					nonce: wpmlCustomizer.nonce
				},
				success: function(response) {
					if (response.success) {
						// Update the customizer values for the new language
						$.each(response.data.values, function(settingId, value) {
							var setting = api(settingId);
							if (setting) {
								setting.set(value);
							}
						});
						
						
						// Update the preview URL for the new language
						var previewUrl = buildLanguageUrl(language);
						if (previewUrl) {
							api.previewer.previewUrl.set(previewUrl);
						}
						
						// Hide loading notice
						$notice.hide();
						
						// Update current language indicator
						$container.find('.current-language').text(language.toUpperCase());
					} else {
						alert('Failed to switch language: ' + (response.data || 'Unknown error'));
						$notice.hide();
					}
				},
				error: function() {
					alert('Failed to switch language. Please try again.');
					$notice.hide();
				}
			});
		});
		
		/**
		 * Build language-specific URL for preview
		 */
		function buildLanguageUrl(language) {
			var baseUrl = wpmlCustomizer.homeUrl || window.location.origin;
			var urlFormat = wpmlCustomizer.urlFormat || 1;
			var defaultLang = wpmlCustomizer.defaultLang || 'en';
			
			// If this is the default language and URL format is directory-based
			if (language === defaultLang && urlFormat == 1) {
				return baseUrl + '/';
			}
			
			// Directory-based URLs
			if (urlFormat == 1) {
				return baseUrl + '/' + language + '/';
			}
			
			// Parameter-based URLs
			if (urlFormat == 3) {
				return baseUrl + '/?lang=' + language;
			}
			
			// Domain-based or other formats - return base URL
			return baseUrl + '/';
		}
		
		/**
		 * Initialize language switcher on load
		 */
		function initLanguageSwitcher() {
			var currentLang = wpmlCustomizer.currentLanguage || 'en';
			
			// Set active button
			$('.wpml-lang-button[data-language="' + currentLang + '"]').addClass('active');
			
			// Update current language indicator
			$('.current-language').text(currentLang.toUpperCase());
		}
		
		// Initialize when DOM is ready
		$(document).ready(function() {
			initLanguageSwitcher();
		});
	});
}