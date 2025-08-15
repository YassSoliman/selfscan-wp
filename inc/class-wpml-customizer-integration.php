<?php
/**
 * WPML Customizer Integration
 *
 * @package selfscan
 */

/**
 * Class for handling WPML integration in the Customizer
 */
class SelfScan_WPML_Customizer_Integration {
	
	/**
	 * Current language in Customizer
	 *
	 * @var string
	 */
	private $customizer_language;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('customize_register', array($this, 'add_language_switcher'), 1);
		add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_scripts'));
		add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
		add_filter('customize_value_footer_text', array($this, 'filter_customizer_value'), 10, 2);
		add_filter('customize_value_copyright_text', array($this, 'filter_customizer_value'), 10, 2);
		add_action('customize_save_after', array($this, 'save_multilingual_options'));
		add_action('wp_ajax_wpml_customizer_change_language', array($this, 'ajax_change_language'));
	}
	
	/**
	 * Add language switcher to Customizer
	 */
	public function add_language_switcher($wp_customize) {
		if (!selfscan_is_wpml_active()) {
			return;
		}
		
		// Detect current language from context
		$current_language = $this->detect_current_language();
		
		// Set the customizer language to match current context
		update_option('wpml_customizer_language', $current_language);
		
		// Add language switcher section at the top
		$wp_customize->add_section('wpml_language_switcher', array(
			'title'    => __('Language', 'selfscan'),
			'priority' => 1,
		));
		
		// Add custom control for language switcher
		require_once get_template_directory() . '/inc/class-customizer-language-switcher-control.php';
		
		$wp_customize->add_setting('wpml_customizer_language', array(
			'default'           => $current_language,
			'sanitize_callback' => 'sanitize_text_field',
			'type'              => 'option',
			'transport'         => 'postMessage',
		));
		
		$wp_customize->add_control(new SelfScan_Language_Switcher_Control(
			$wp_customize,
			'wpml_customizer_language',
			array(
				'label'    => __('Select Language', 'selfscan'),
				'section'  => 'wpml_language_switcher',
				'settings' => 'wpml_customizer_language',
			)
		));
	}
	
	/**
	 * Enqueue Customizer control scripts
	 */
	public function enqueue_customizer_scripts() {
		if (!selfscan_is_wpml_active()) {
			return;
		}
		
		wp_enqueue_script(
			'selfscan-customizer-controls',
			get_template_directory_uri() . '/build/js/customizer-controls.js',
			array('jquery', 'customize-controls'),
			filemtime(get_template_directory() . '/build/js/customizer-controls.js'),
			true
		);
		
		// Get current customizer language (should be set correctly now)
		$this->customizer_language = get_option('wpml_customizer_language', ICL_LANGUAGE_CODE);
		
		// Get WPML settings for URL structure
		$wpml_default_lang = apply_filters('wpml_default_language', 'en');
		$wpml_setting = get_option('icl_sitepress_settings');
		$url_format = isset($wpml_setting['language_negotiation_type']) ? $wpml_setting['language_negotiation_type'] : 1;
		
		// Pass data to JavaScript
		wp_localize_script('selfscan-customizer-controls', 'wpmlCustomizer', array(
			'ajaxUrl'         => admin_url('admin-ajax.php'),
			'nonce'           => wp_create_nonce('wpml_customizer_nonce'),
			'currentLanguage' => $this->customizer_language,
			'languages'       => selfscan_get_wpml_languages(),
			'defaultLang'     => $wpml_default_lang,
			'urlFormat'       => $url_format, // 1 = directory, 2 = domain, 3 = parameter
			'homeUrl'         => home_url(),
		));
	}
	
	/**
	 * Enqueue preview scripts
	 */
	public function enqueue_preview_scripts() {
		if (!selfscan_is_wpml_active()) {
			return;
		}
		
		// Only enqueue if not already enqueued (main customizer may have already loaded this)
		if (!wp_script_is('selfscan-customizer-preview', 'enqueued')) {
			wp_enqueue_script(
				'selfscan-customizer-preview',
				get_template_directory_uri() . '/build/js/customizer-preview.js',
				array('jquery', 'customize-preview'),
				filemtime(get_template_directory() . '/build/js/customizer-preview.js'),
				true
			);
		}
	}
	
	/**
	 * Filter customizer value based on selected language
	 */
	public function filter_customizer_value($value, $setting) {
		if (!selfscan_is_wpml_active()) {
			return $value;
		}
		
		// Get current customizer language
		$customizer_lang = get_option('wpml_customizer_language', ICL_LANGUAGE_CODE);
		$default_lang = apply_filters('wpml_default_language', 'en');
		
		// Always check for language-specific value first
		if ($customizer_lang !== $default_lang) {
			$option_name = $setting->id . '_' . $customizer_lang;
			$lang_value = get_theme_mod($option_name, '');
			
			// Return language-specific value if it exists, otherwise fall back to default
			if (!empty($lang_value)) {
				return $lang_value;
			}
			// If no translation exists, fall back to the default language value
			return get_theme_mod($setting->id, $value);
		}
		
		// For default language, return the base value
		return $value;
	}
	
	/**
	 * Save multilingual options
	 */
	public function save_multilingual_options($wp_customize) {
		if (!selfscan_is_wpml_active()) {
			return;
		}
		
		$customizer_lang = get_option('wpml_customizer_language', ICL_LANGUAGE_CODE);
		$default_lang = apply_filters('wpml_default_language', 'en');
		
		// List of multilingual settings
		$multilingual_settings = array('footer_text', 'copyright_text');
		
		foreach ($multilingual_settings as $setting_id) {
			$setting = $wp_customize->get_setting($setting_id);
			
			if ($setting) {
				$value = $setting->post_value();
				
				if ($value !== null) {
					// Save to language-specific option if not default language
					if ($customizer_lang !== $default_lang) {
						$option_name = $setting_id . '_' . $customizer_lang;
						set_theme_mod($option_name, $value);
						
						// Don't update the base option for non-default languages
						set_theme_mod($setting_id, get_theme_mod($setting_id));
					}
				}
			}
		}
	}
	
	/**
	 * AJAX handler for language change in Customizer
	 */
	public function ajax_change_language() {
		// Check nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wpml_customizer_nonce')) {
			wp_die('Security check failed');
		}
		
		// Check permission
		if (!current_user_can('customize')) {
			wp_die('Permission denied');
		}
		
		$language = sanitize_text_field($_POST['language']);
		
		// Save the selected language
		update_option('wpml_customizer_language', $language);
		
		// Get values for the selected language
		$values = array();
		$multilingual_settings = array('footer_text', 'copyright_text');
		$default_lang = apply_filters('wpml_default_language', 'en');
		
		foreach ($multilingual_settings as $setting_id) {
			if ($language === $default_lang) {
				$values[$setting_id] = get_theme_mod($setting_id, '');
			} else {
				$option_name = $setting_id . '_' . $language;
				$values[$setting_id] = get_theme_mod($option_name, '');
				
				// Fallback to default language value if translation doesn't exist
				if (empty($values[$setting_id])) {
					$values[$setting_id] = get_theme_mod($setting_id, '');
				}
			}
		}
		
		wp_send_json_success(array(
			'language' => $language,
			'values'   => $values,
		));
	}
	
	
	/**
	 * Detect current language from various sources
	 */
	private function detect_current_language() {
		// Try to get language from preview URL first
		if (isset($_GET['url'])) {
			$preview_url = $_GET['url'];
			$language = $this->extract_language_from_url($preview_url);
			if ($language) {
				return $language;
			}
		}
		
		// Try to get from current ICL_LANGUAGE_CODE
		if (defined('ICL_LANGUAGE_CODE')) {
			return ICL_LANGUAGE_CODE;
		}
		
		// Fallback to default language
		return apply_filters('wpml_default_language', 'en');
	}
	
	/**
	 * Extract language from URL
	 */
	private function extract_language_from_url($url) {
		$parsed_url = parse_url($url);
		if (!isset($parsed_url['path'])) {
			return null;
		}
		
		$path_parts = explode('/', trim($parsed_url['path'], '/'));
		
		if (empty($path_parts[0])) {
			// Root URL, probably default language
			return apply_filters('wpml_default_language', 'en');
		}
		
		// Check if first part is a language code
		$possible_lang = $path_parts[0];
		if (strlen($possible_lang) === 2 || strlen($possible_lang) === 3) {
			// Verify this is an active language
			$languages = selfscan_get_wpml_languages();
			foreach ($languages as $lang) {
				if ($lang['language_code'] === $possible_lang) {
					return $possible_lang;
				}
			}
		}
		
		// If no language code found in URL, assume default language
		return apply_filters('wpml_default_language', 'en');
	}
}

// Initialize the class
new SelfScan_WPML_Customizer_Integration();