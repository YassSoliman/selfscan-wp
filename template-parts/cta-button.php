<?php
/**
 * Template part for displaying a CTA button
 *
 * @package SelfScan
 * 
 * Parameters:
 * $args['cta_button'] - ACF link field (contains url, title, target)
 * $args['class'] - Additional CSS classes
 * $args['icon'] - Whether to show the arrow icon (default: true)
 */

// Set defaults and extract variables
$defaults = array(
    'cta_button' => null,
    'class' => 'button-red',
    'icon' => true
);

// Merge defaults with provided args
$args = wp_parse_args($args, $defaults);

// Extract data from ACF link field
$url = '';
$text = '';
$target = '';

if (is_array($args['cta_button']) && !empty($args['cta_button'])) {
    $url = isset($args['cta_button']['url']) ? $args['cta_button']['url'] : '';
    $text = isset($args['cta_button']['title']) ? $args['cta_button']['title'] : '';
    $target = isset($args['cta_button']['target']) ? $args['cta_button']['target'] : '';
}

// Build the class attribute
$button_class = 'button ' . esc_attr($args['class']);

// Add specific classes based on context if they're not already included
if (strpos($args['class'], 'hero-home__button') === false && 
    strpos($args['class'], 'get-started__button') === false && 
    strpos($args['class'], 'pricing-hero__button') === false) {
    $button_class .= ' cta-button';
}

// Set target attribute and rel for security if opening in new tab
$target_attr = '';
$rel_attr = '';
if (!empty($target)) {
    $target_attr = ' target="' . esc_attr($target) . '"';
    $rel_attr = ' rel="noopener noreferrer"';
}
?>

<a href="<?php echo esc_url($url); ?>" 
   class="<?php echo esc_attr($button_class); ?>"<?php echo $target_attr . $rel_attr; ?>
   data-track-cta>
    <span class="button__text">
        <?php echo esc_html($text); ?>
    </span>
    <?php if ($args['icon']) : ?>
    <span class="button__icon">
        <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-right.svg', ['class' => 'button__icon-svg']); ?>
    </span>
    <?php endif; ?>
</a> 