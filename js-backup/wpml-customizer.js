/**
 * WPML Customizer Control Scripts
 */
(function($, api) {
	'use strict';
	
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
			$.post(wpmlCustomizer.ajaxUrl, {
				action: 'wpml_customizer_change_language',
				language: language,
				nonce: wpmlCustomizer.nonce
			}, function(response) {
				if (response.success) {
					// Update field values for the selected language
					var values = response.data.values;
					
					// Update each multilingual field
					$.each(values, function(settingId, value) {
						var setting = api(settingId);
						if (setting) {
							setting.set(value);
							
							// Update the actual input field
							var $input = $('#customize-control-' + settingId).find('input, textarea');
							if ($input.length) {
								$input.val(value).trigger('change');
							}
						}
					});
					
					// Instead of manually updating URL, get the correct URL from WPML
					// This respects WPML's URL settings
					var languages = wpmlCustomizer.languages;
					var targetLang = null;
					
					// Find the target language data
					for (var langCode in languages) {
						if (languages[langCode].language_code === language) {
							targetLang = languages[langCode];
							break;
						}
					}
					
					if (targetLang && targetLang.url) {
						// Use WPML's provided URL for the language
						api.previewer.previewUrl.set(targetLang.url);
					} else {
						// Fallback to manual URL update
						var currentUrl = api.previewer.previewUrl.get();
						var newUrl = updateUrlLanguage(currentUrl, language);
						api.previewer.previewUrl.set(newUrl);
					}
					
					// Refresh the preview with new language
					api.previewer.refresh();
					
					// Hide notice after a short delay
					setTimeout(function() {
						$notice.hide();
					}, 500);
				}
			});
		});
		
		// Function to update URL with new language
		function updateUrlLanguage(url, language) {
			var urlObj = new URL(url);
			var defaultLang = wpmlCustomizer.defaultLang || 'en';
			
			// Get current path parts
			var pathParts = urlObj.pathname.split('/').filter(Boolean);
			
			// Check if the first part is a language code (2 or 3 characters)
			var hasLangPrefix = pathParts.length > 0 && (pathParts[0].length === 2 || pathParts[0].length === 3);
			
			// Remove existing language code if present
			if (hasLangPrefix) {
				pathParts.shift();
			}
			
			// Add language prefix only if it's not the default language
			if (language !== defaultLang) {
				pathParts.unshift(language);
			}
			
			// Rebuild the pathname
			urlObj.pathname = '/' + pathParts.join('/');
			
			// Ensure we don't have double slashes
			urlObj.pathname = urlObj.pathname.replace(/\/+/g, '/');
			
			return urlObj.toString();
		}
		
		// Listen for preview language changes
		api.previewer.bind('wpml-language-change', function(data) {
			// Update the language switcher buttons
			$('.wpml-lang-button').removeClass('active');
			$('.wpml-lang-button[data-language="' + data.language + '"]').addClass('active');
			
			// Trigger language change
			$('.wpml-lang-button[data-language="' + data.language + '"]').click();
		});
		
		// Override the save function to handle multilingual fields
		var originalSave = api.requestChangesetUpdate;
		api.requestChangesetUpdate = function(changes, args) {
			// Get current language
			var currentLang = $('.wpml-lang-button.active').data('language');
			
			if (currentLang && currentLang !== wpmlCustomizer.defaultLang) {
				// Modify the changes object to save to language-specific options
				var modifiedChanges = {};
				
				$.each(changes || {}, function(settingId, value) {
					// Check if this is a multilingual field
					if (settingId === 'footer_text' || settingId === 'copyright_text') {
						// Don't save to the base option for non-default languages
						// The server-side code will handle saving to language-specific options
						modifiedChanges[settingId] = value;
					} else {
						modifiedChanges[settingId] = value;
					}
				});
				
				changes = modifiedChanges;
			}
			
			// Call the original save function
			return originalSave.call(this, changes, args);
		};
	});
	
})(jQuery, wp.customize);