<?php
/**
 * WPML Language Switcher functionality
 *
 * @package selfscan
 */

/**
 * Check if WPML is active
 *
 * @return bool
 */
function selfscan_is_wpml_active() {
	return function_exists('icl_get_languages');
}

/**
 * Get active languages from WPML
 *
 * @return array
 */
function selfscan_get_wpml_languages() {
	if (!selfscan_is_wpml_active()) {
		return array();
	}
	
	return icl_get_languages('skip_missing=0&orderby=code&order=asc');
}

/**
 * Get current language code
 *
 * @return string
 */
function selfscan_get_current_language() {
	if (!selfscan_is_wpml_active()) {
		return 'EN';
	}
	
	return strtoupper(ICL_LANGUAGE_CODE);
}

/**
 * Render the language switcher
 *
 * @param string $context 'desktop' or 'mobile'
 * @return void
 */
function selfscan_render_language_switcher($context = 'desktop') {
	if (!selfscan_is_wpml_active()) {
		return;
	}
	
	$languages = selfscan_get_wpml_languages();
	
	if (empty($languages) || count($languages) < 2) {
		return;
	}
	
	$current_lang = selfscan_get_current_language();
	$current_lang_data = null;
	$other_languages = array();
	
	foreach ($languages as $lang) {
		if ($lang['active']) {
			$current_lang_data = $lang;
		} else {
			$other_languages[] = $lang;
		}
	}
	
	if (!$current_lang_data) {
		return;
	}
	
	if ($context === 'mobile') {
		// Mobile: Render inline button group
		?>
		<li class="menu-header__item menu-header__item--language">
			<div class="language-switcher language-switcher--mobile" data-language-switcher>
				<div class="language-switcher__label">
					<?php 
					selfscan_inline_svg(
						get_template_directory_uri() . '/img/icons/globe.svg',
						array('class' => 'language-switcher__globe', 'aria-hidden' => 'true')
					); 
					?>
					<span class="language-switcher__label-text"><?php esc_html_e('Language', 'selfscan'); ?></span>
				</div>
				<div class="language-switcher__buttons" role="group" aria-label="<?php esc_attr_e('Select language', 'selfscan'); ?>">
					<!-- Current active language -->
					<button class="language-switcher__button language-switcher__button--active" 
							aria-current="true"
							disabled>
						<?php echo esc_html($current_lang); ?>
					</button>
					
					<!-- Other languages -->
					<?php foreach ($other_languages as $lang) : 
						$lang_code = strtoupper($lang['language_code']);
						$switch_url = $lang['url'];
						$lang_name = $lang['native_name'];
					?>
					<a href="<?php echo esc_url($switch_url); ?>" 
					   class="language-switcher__button"
					   lang="<?php echo esc_attr($lang['language_code']); ?>"
					   hreflang="<?php echo esc_attr($lang['language_code']); ?>"
					   aria-label="<?php echo esc_attr(sprintf(__('Switch to %s', 'selfscan'), $lang_name)); ?>">
						<?php echo esc_html($lang_code); ?>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
		</li>
		<?php
	} else {
		// Desktop: Keep original dropdown design
		$container_class = 'language-switcher language-switcher--desktop';
		?>
		<li class="menu-header__item menu-header__item--language">
			<div class="<?php echo esc_attr($container_class); ?>" data-language-switcher>
				<button class="language-switcher__toggle" 
						aria-label="<?php esc_attr_e('Change language', 'selfscan'); ?>" 
						aria-expanded="false"
						data-language-toggle>
					<span class="language-switcher__icon">
						<?php 
						selfscan_inline_svg(
							get_template_directory_uri() . '/img/icons/globe.svg',
							array('class' => 'language-switcher__globe', 'aria-hidden' => 'true')
						); 
						?>
					</span>
					<span class="language-switcher__current"><?php echo esc_html($current_lang); ?></span>
					<span class="language-switcher__arrow">
						<?php 
						selfscan_inline_svg(
							get_template_directory_uri() . '/img/icons/arrow-down.svg',
							array('class' => 'language-switcher__arrow-icon', 'aria-hidden' => 'true')
						); 
						?>
					</span>
				</button>
				
				<?php if (!empty($other_languages)) : ?>
				<ul class="language-switcher__dropdown" data-language-dropdown hidden>
					<?php foreach ($other_languages as $lang) : 
						$lang_code = strtoupper($lang['language_code']);
						$switch_url = $lang['url'];
						$lang_name = $lang['native_name'];
						$display_name = $lang['translated_name']; // Use translated name for better UX
					?>
					<li class="language-switcher__item">
						<a href="<?php echo esc_url($switch_url); ?>" 
						   class="language-switcher__link"
						   lang="<?php echo esc_attr($lang['language_code']); ?>"
						   hreflang="<?php echo esc_attr($lang['language_code']); ?>"
						   data-lang-code="<?php echo esc_attr($lang_code); ?>"
						   aria-label="<?php echo esc_attr(sprintf(__('Switch to %s', 'selfscan'), $lang_name)); ?>">
							<?php echo esc_html($lang_code); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
		</li>
		<?php
	}
}

/**
 * Get multilingual option value
 * 
 * @param string $option_name Base option name
 * @param mixed $default Default value if option doesn't exist
 * @return mixed
 */
function selfscan_get_multilingual_option($option_name, $default = '') {
	if (!selfscan_is_wpml_active()) {
		return get_theme_mod($option_name, $default);
	}
	
	$current_lang = strtolower(ICL_LANGUAGE_CODE);
	$default_lang = apply_filters('wpml_default_language', 'en');
	
	// For default language, use the base option name for backward compatibility
	if ($current_lang === $default_lang) {
		return get_theme_mod($option_name, $default);
	}
	
	// For other languages, append the language code
	$multilingual_option = $option_name . '_' . $current_lang;
	$value = get_theme_mod($multilingual_option, '');
	
	// If no translation exists, fall back to default language
	if (empty($value)) {
		$value = get_theme_mod($option_name, $default);
	}
	
	return $value;
}

/**
 * Set multilingual option value
 * 
 * @param string $option_name Base option name
 * @param mixed $value Value to set
 * @param string $language Language code (optional, uses current if not provided)
 * @return bool
 */
function selfscan_set_multilingual_option($option_name, $value, $language = null) {
	if (!selfscan_is_wpml_active()) {
		set_theme_mod($option_name, $value);
		return true;
	}
	
	if ($language === null) {
		$language = strtolower(ICL_LANGUAGE_CODE);
	} else {
		$language = strtolower($language);
	}
	
	$default_lang = apply_filters('wpml_default_language', 'en');
	
	// For default language, use the base option name
	if ($language === $default_lang) {
		set_theme_mod($option_name, $value);
	} else {
		// For other languages, append the language code
		$multilingual_option = $option_name . '_' . $language;
		set_theme_mod($multilingual_option, $value);
	}
	
	return true;
}