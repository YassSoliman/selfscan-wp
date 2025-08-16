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
						// Store current language globally
						window.wpmlCurrentLanguage = language;
						
						// Show/hide appropriate language fields using proper ID selectors
						var defaultLang = wpmlCustomizer.defaultLang || 'en';
						
						// Hide ALL multilingual fields first (both default and language-specific)
						$('#customize-control-footer_text').css('display', 'none');
						$('#customize-control-copyright_text').css('display', 'none');
						$('[id^="customize-control-footer_text_"]').css('display', 'none');
						$('[id^="customize-control-copyright_text_"]').css('display', 'none');
						
						if (language === defaultLang) {
							// Show only default language fields
							$('#customize-control-footer_text').css('display', 'block');
							$('#customize-control-copyright_text').css('display', 'block');
						} else {
							// Show only current language fields
							$('#customize-control-footer_text_' + language).css('display', 'block');
							$('#customize-control-copyright_text_' + language).css('display', 'block');
						}
						
						// Update the input field values directly using DOM manipulation
						$.each(response.data.values, function(settingId, value) {
							// Find the actual input/textarea element for this setting
							var $input = $('#customize-control-' + settingId + ' input, #customize-control-' + settingId + ' textarea');
							
							if ($input.length) {
								// Set the value directly
								$input.val(value);
								
								// Trigger events to notify Customizer of the change
								$input.trigger('input').trigger('change');
								
								// Also update the Customizer API setting if it exists
								var setting = api(settingId);
								if (setting) {
									setting.set(value);
								}
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
						
						// Trigger custom event for other components
						$(document).trigger('wpml:language-changed', [language]);
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
			var defaultLang = wpmlCustomizer.defaultLang || 'en';
			
			// Set active button
			$('.wpml-lang-button[data-language="' + currentLang + '"]').addClass('active');
			
			// Update current language indicator
			$('.current-language').text(currentLang.toUpperCase());
			
			// Handle initial field visibility and values
			if (currentLang !== defaultLang) {
				// Hide default language fields
				$('#customize-control-footer_text, #customize-control-copyright_text').css('display', 'none');
				
				// Show current language fields
				$('#customize-control-footer_text_' + currentLang + ', #customize-control-copyright_text_' + currentLang).css('display', 'block');
				
				// Load correct values for current language fields
				var footerTextValue = api('footer_text_' + currentLang) ? api('footer_text_' + currentLang).get() : '';
				var copyrightTextValue = api('copyright_text_' + currentLang) ? api('copyright_text_' + currentLang).get() : '';
				
				// Update field values if they exist
				if (footerTextValue && $('#customize-control-footer_text_' + currentLang + ' textarea').length) {
					$('#customize-control-footer_text_' + currentLang + ' textarea').val(footerTextValue);
				}
				if (copyrightTextValue && $('#customize-control-copyright_text_' + currentLang + ' input').length) {
					$('#customize-control-copyright_text_' + currentLang + ' input').val(copyrightTextValue);
				}
			} else {
				// For default language, make sure default fields are visible
				$('#customize-control-footer_text, #customize-control-copyright_text').css('display', 'block');
				
				// Hide all language-specific fields
				$('[id*="customize-control-footer_text_"], [id*="customize-control-copyright_text_"]').css('display', 'none');
			}
		}
		
		// Initialize when DOM is ready
		$(document).ready(function() {
			initLanguageSwitcher();
		});
	});
}