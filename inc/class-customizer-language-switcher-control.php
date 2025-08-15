<?php
/**
 * Customizer Language Switcher Control
 *
 * @package selfscan
 */

// Only load if in Customizer context
if (!class_exists('WP_Customize_Control')) {
	return;
}

/**
 * Custom control for language switcher in Customizer
 */
class SelfScan_Language_Switcher_Control extends WP_Customize_Control {
	
	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'language_switcher';
	
	/**
	 * Render the control's content
	 */
	public function render_content() {
		if (!function_exists('icl_get_languages')) {
			return;
		}
		
		$languages = icl_get_languages('skip_missing=0&orderby=code&order=asc');
		$current_lang = get_option('wpml_customizer_language', ICL_LANGUAGE_CODE);
		
		if (empty($languages)) {
			return;
		}
		?>
		
		<div class="wpml-language-switcher-control">
			<?php if ($this->label) : ?>
				<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<?php endif; ?>
			
			<?php if ($this->description) : ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php endif; ?>
			
			<div class="wpml-language-buttons">
				<?php foreach ($languages as $lang) : 
					$active_class = ($lang['language_code'] === $current_lang) ? 'active' : '';
				?>
					<button type="button" 
							class="wpml-lang-button <?php echo esc_attr($active_class); ?>"
							data-language="<?php echo esc_attr($lang['language_code']); ?>"
							data-url="<?php echo esc_url($lang['url']); ?>">
						<?php if (!empty($lang['country_flag_url'])) : ?>
							<img src="<?php echo esc_url($lang['country_flag_url']); ?>" 
								 alt="<?php echo esc_attr($lang['native_name']); ?>" 
								 width="18" height="12">
						<?php endif; ?>
						<span><?php echo esc_html(strtoupper($lang['language_code'])); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
			
			<div class="wpml-language-notice" style="display: none;">
				<p class="notice notice-info">
					<?php esc_html_e('Switching language... Please wait.', 'selfscan'); ?>
				</p>
			</div>
		</div>
		
		<style>
			.wpml-language-switcher-control {
				margin-top: 15px;
			}
			
			.wpml-language-buttons {
				display: flex;
				gap: 10px;
				margin-top: 10px;
			}
			
			.wpml-lang-button {
				display: flex;
				align-items: center;
				gap: 5px;
				padding: 8px 15px;
				background: #f0f0f1;
				border: 2px solid #f0f0f1;
				border-radius: 4px;
				cursor: pointer;
				transition: all 0.2s ease;
				font-size: 13px;
				font-weight: 500;
			}
			
			.wpml-lang-button:hover {
				background: #e0e0e0;
				border-color: #e0e0e0;
			}
			
			.wpml-lang-button.active {
				background: #0073aa;
				border-color: #0073aa;
				color: #fff;
			}
			
			.wpml-lang-button img {
				display: block;
			}
			
			.wpml-language-notice {
				margin-top: 15px;
			}
			
			.wpml-language-notice .notice {
				margin: 0;
				padding: 10px;
			}
		</style>
		
		<?php
	}
}