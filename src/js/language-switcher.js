/**
 * Language Switcher functionality
 */
export default function initLanguageSwitcher() {
	const languageSwitchers = document.querySelectorAll('[data-language-switcher]');
	
	if (!languageSwitchers.length) {
		return;
	}
	
	languageSwitchers.forEach(function(switcher) {
		const isMobile = switcher.classList.contains('language-switcher--mobile');
		
		if (isMobile) {
			// Mobile: Handle button interactions (no dropdown logic needed)
			const languageButtons = switcher.querySelectorAll('.language-switcher__button:not(.language-switcher__button--active)');
			
			languageButtons.forEach(function(button) {
				// Add subtle interaction feedback
				button.addEventListener('mousedown', function() {
					this.style.transform = 'translateY(0) scale(0.98)';
				});
				
				button.addEventListener('mouseup', function() {
					this.style.transform = '';
				});
				
				button.addEventListener('mouseleave', function() {
					this.style.transform = '';
				});
			});
		} else {
			// Desktop: Keep original dropdown functionality
			const toggle = switcher.querySelector('[data-language-toggle]');
			const dropdown = switcher.querySelector('[data-language-dropdown]');
			
			if (!toggle || !dropdown) {
				return;
			}
			
			// Toggle dropdown on click
			toggle.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
				
				// Close all other dropdowns
				document.querySelectorAll('[data-language-toggle]').forEach(function(otherToggle) {
					if (otherToggle !== toggle) {
						otherToggle.setAttribute('aria-expanded', 'false');
						const otherDropdown = otherToggle.closest('[data-language-switcher]').querySelector('[data-language-dropdown]');
						if (otherDropdown) {
							otherDropdown.setAttribute('hidden', '');
							otherDropdown.classList.remove('is-open');
						}
					}
				});
				
				// Toggle current dropdown
				if (isExpanded) {
					toggle.setAttribute('aria-expanded', 'false');
					dropdown.setAttribute('hidden', '');
					dropdown.classList.remove('is-open');
				} else {
					toggle.setAttribute('aria-expanded', 'true');
					dropdown.removeAttribute('hidden');
					dropdown.classList.add('is-open');
				}
			});
			
			// Close dropdown when clicking outside
			document.addEventListener('click', function(e) {
				if (!switcher.contains(e.target)) {
					toggle.setAttribute('aria-expanded', 'false');
					dropdown.setAttribute('hidden', '');
					dropdown.classList.remove('is-open');
				}
			});
			
			// Handle keyboard navigation
			switcher.addEventListener('keydown', function(e) {
				if (e.key === 'Escape') {
					toggle.setAttribute('aria-expanded', 'false');
					dropdown.setAttribute('hidden', '');
					dropdown.classList.remove('is-open');
					toggle.focus();
				}
			});
			
			// Add staggered animation for dropdown items
			const links = dropdown.querySelectorAll('.language-switcher__link');
			links.forEach(function(link, index) {
				link.style.opacity = '0';
				link.style.transform = 'translateY(8px)';
				link.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
				link.style.transitionDelay = (index * 0.1) + 's';
				
				// Ensure normal state (not hover) when dropdown opens
				link.style.backgroundColor = '';
				link.style.color = '';
				link.style.boxShadow = '';
			});
			
			// Show items when dropdown opens
			const observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
					if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
						const isOpen = dropdown.classList.contains('is-open');
						links.forEach(function(link, index) {
							if (isOpen) {
								setTimeout(function() {
									link.style.opacity = '1';
									link.style.transform = 'translateY(0)';
									// Reset to normal state, not hover
									link.style.backgroundColor = '';
									link.style.color = '';
									link.style.boxShadow = '';
								}, index * 50);
							} else {
								link.style.opacity = '0';
								link.style.transform = 'translateY(8px)';
							}
						});
					}
				});
			});
			
			observer.observe(dropdown, { attributes: true, attributeFilter: ['class'] });
		}
	});
	
	// Handle language switch in Customizer preview
	if (window.parent && window.parent.wp && window.parent.wp.customize) {
		const languageLinks = document.querySelectorAll('.language-switcher__link, .language-switcher__button:not(.language-switcher__button--active)');
		
		languageLinks.forEach(function(link) {
			link.addEventListener('click', function(e) {
				e.preventDefault();
				
				const targetUrl = this.getAttribute('href');
				const langCode = this.getAttribute('lang');
				
				// Notify parent Customizer of language change
				window.parent.wp.customize.preview.send('wpml-language-change', {
					language: langCode,
					url: targetUrl
				});
				
				// Update preview URL
				window.location.href = targetUrl;
			});
		});
	}
}