<?php
/**
 * Customizer Repeater Control for Footer Partners
 *
 * @package selfscan
 */

// Only load if in Customizer context
if (!class_exists('WP_Customize_Control')) {
	return;
}

/**
 * Custom control for repeating footer partner fields
 */
class SelfScan_Repeater_Control extends WP_Customize_Control {
	
	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'repeater';
	
	/**
	 * Button labels
	 *
	 * @var array
	 */
	public $button_labels = array();
	
	/**
	 * Constructor
	 */
	public function __construct($manager, $id, $args = array()) {
		parent::__construct($manager, $id, $args);
		
		// Set default button labels
		$this->button_labels = wp_parse_args($this->button_labels, array(
			'add'    => __('Add Partner', 'selfscan'),
			'remove' => __('Remove', 'selfscan'),
		));
	}
	
	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_media();
		// Only enqueue if not already enqueued (WPML controls may have already loaded this)
		if (!wp_script_is('selfscan-customizer-controls', 'enqueued')) {
			wp_enqueue_script(
				'selfscan-customizer-controls',
				get_template_directory_uri() . '/build/js/customizer-controls.js',
				array('jquery', 'customize-controls'),
				filemtime(get_template_directory() . '/build/js/customizer-controls.js'),
				true
			);
		}
		
		wp_enqueue_style(
			'selfscan-customizer-repeater',
			get_template_directory_uri() . '/css/customizer-repeater.css',
			array(),
			filemtime(get_template_directory() . '/css/customizer-repeater.css')
		);
	}
	
	/**
	 * Render the control's content
	 */
	public function render_content() {
		?>
		<div class="repeater-control" data-control-id="<?php echo esc_attr($this->id); ?>">
			<?php if ($this->label) : ?>
				<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<?php endif; ?>
			
			<?php if ($this->description) : ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php endif; ?>
			
			<div class="repeater-items" data-repeater-items>
				<!-- Items will be added here via JavaScript -->
			</div>
			
			<button type="button" class="button repeater-add-button" data-repeater-add>
				<?php echo esc_html($this->button_labels['add']); ?>
			</button>
			
			<input type="hidden" 
				   value="<?php echo esc_attr($this->value()); ?>" 
				   <?php $this->link(); ?> 
				   data-repeater-value />
		</div>
		
		<!-- Template for repeater item -->
		<script type="text/template" id="repeater-item-template-<?php echo esc_attr($this->id); ?>">
			<div class="repeater-item" data-repeater-item data-index="{{index}}">
				<div class="repeater-item-header">
					<span class="repeater-item-title">
						<?php esc_html_e('Partner', 'selfscan'); ?> <span class="item-number">{{itemNumber}}</span>
					</span>
					<button type="button" class="repeater-item-toggle" data-repeater-toggle>
						<span class="dashicons dashicons-arrow-down"></span>
					</button>
				</div>
				
				<div class="repeater-item-content">
					<div class="repeater-field">
						<label><?php esc_html_e('Logo Image', 'selfscan'); ?></label>
						<div class="media-widget-control">
							<div class="media-widget-preview" data-media-preview="{{index}}">
								<img src="{{imageUrl}}" style="{{imageStyle}}" />
							</div>
							<div class="media-widget-buttons">
								<button type="button" class="button select-media" data-media-select="{{index}}">
									<?php esc_html_e('Select Image', 'selfscan'); ?>
								</button>
								<button type="button" class="button remove-media" data-media-remove="{{index}}" style="{{removeStyle}}">
									<?php esc_html_e('Remove', 'selfscan'); ?>
								</button>
							</div>
							<input type="hidden" class="repeater-media-id" data-field="image_id" value="{{image_id}}" />
						</div>
					</div>
					
					<div class="repeater-field repeater-field-dimension">
						<label><?php esc_html_e('Width', 'selfscan'); ?></label>
						<div class="dimension-input-group">
							<input type="number" 
								   class="repeater-input dimension-value" 
								   data-field="width" 
								   value="{{width}}" 
								   step="0.1"
								   placeholder="<?php esc_attr_e('Auto', 'selfscan'); ?>" />
							<select class="repeater-input dimension-unit" data-field="width_unit">
								<option value="rem" {{width_unit_rem}}>rem</option>
								<option value="px" {{width_unit_px}}>px</option>
								<option value="%" {{width_unit_percent}}>%</option>
								<option value="auto" {{width_unit_auto}}>auto</option>
							</select>
						</div>
					</div>
					
					<div class="repeater-field repeater-field-dimension">
						<label><?php esc_html_e('Height', 'selfscan'); ?></label>
						<div class="dimension-input-group">
							<input type="number" 
								   class="repeater-input dimension-value" 
								   data-field="height" 
								   value="{{height}}" 
								   step="0.1"
								   placeholder="<?php esc_attr_e('Auto', 'selfscan'); ?>" />
							<select class="repeater-input dimension-unit" data-field="height_unit">
								<option value="rem" {{height_unit_rem}}>rem</option>
								<option value="px" {{height_unit_px}}>px</option>
								<option value="%" {{height_unit_percent}}>%</option>
								<option value="auto" {{height_unit_auto}}>auto</option>
							</select>
						</div>
					</div>
					
					<div class="repeater-field">
						<button type="button" class="button button-secondary repeater-remove-button" data-repeater-remove>
							<?php echo esc_html($this->button_labels['remove']); ?>
						</button>
					</div>
				</div>
			</div>
		</script>
		<?php
	}
}