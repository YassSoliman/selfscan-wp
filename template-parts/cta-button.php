<?php
/**
 * Template part for displaying a CTA button
 *
 * @package SelfScan
 * 
 * Parameters:
 * $args['url'] - Button URL
 * $args['text'] - Button text
 * $args['class'] - Additional CSS classes
 * $args['cta_label'] - Optional tracking label for analytics
 * $args['icon'] - Whether to show the arrow icon (default: true)
 */

// Set defaults and extract variables
$defaults = array(
    'url' => '#',
    'text' => 'Start Background Check',
    'class' => 'button-red',
    'icon' => true
);

// Merge defaults with provided args
$args = wp_parse_args($args, $defaults);

// Build the class attribute
$button_class = 'button ' . esc_attr($args['class']);

// Add specific classes based on context if they're not already included
if (strpos($args['class'], 'hero-home__button') === false && 
    strpos($args['class'], 'get-started__button') === false && 
    strpos($args['class'], 'pricing-hero__button') === false) {
    $button_class .= ' cta-button';
}
?>

<a href="<?php echo esc_url($args['url']); ?>" 
   class="<?php echo esc_attr($button_class); ?>"
   data-track-cta>
    <span class="button__text">
        <?php echo esc_html($args['text']); ?>
    </span>
    <?php if ($args['icon']) : ?>
    <span class="button__icon">
        <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-right.svg', ['class' => 'button__icon-svg']); ?>
    </span>
    <?php endif; ?>
</a> 