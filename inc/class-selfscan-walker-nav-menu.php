<?php
/**
 * Custom navigation menu walkers for SelfScan theme
 *
 * @package selfscan
 */

/**
 * Custom walker class for main navigation menu
 */
class SelfScan_Walker_Nav_Menu extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     *
     * @param string  $output            Used to append additional content (passed by reference).
     * @param WP_Post $item              Menu item data object.
     * @param int     $depth             Depth of menu item.
     * @param array   $args              An object of wp_nav_menu() arguments.
     * @param int     $id                Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        // Save the original classes before modification
        $original_classes = $classes;
        
        // Check for special classes we need to handle separately
        $has_button_class = in_array( 'button', $original_classes, true );
        $is_current_item = in_array( 'current-menu-item', $original_classes, true );
        
        // Reset item classes to only contain structural classes
        $classes = array();
        
        // Add menu-header__item class to all list items
        $classes[] = 'menu-header__item';
        
        // If the item has children (submenu), add appropriate class
        if ( in_array( 'menu-item-has-children', $original_classes, true ) ) {
            $classes[] = 'menu-item-has-children';
        }
        
        // Apply depth-specific classes if needed
        if ( in_array( 'menu-item-depth-' . $depth, $original_classes, true ) ) {
            $classes[] = 'menu-item-depth-' . $depth;
        }
        
        // Filter the CSS classes applied to the menu item's LI element
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        
        $output .= '<li' . $class_names . '>';
        
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';
        
        // Build the anchor class attribute based on the original menu item classes
        $anchor_classes = array('menu-header__link');
        
        // Add button class if needed
        if ( $has_button_class ) {
            $anchor_classes[] = 'button';
        }
        
        // Add active class if current item
        if ( $is_current_item ) {
            $anchor_classes[] = '_active';
        }
        
        // Add any additional custom classes from the original classes
        foreach ( $original_classes as $class ) {
            // Skip certain structural classes we don't want on the anchor
            if ( !in_array( $class, array('menu-item', 'menu-item-has-children', 'menu-item-depth-' . $depth, 'current-menu-item', 'button', 'menu-header__item') ) 
                && strpos( $class, 'menu-item-type-' ) === false 
                && strpos( $class, 'menu-item-object-' ) === false ) {
                $anchor_classes[] = $class;
            }
        }
        
        $atts['class'] = implode( ' ', array_filter( $anchor_classes ) );
        
        // Filter the HTML attributes applied to the menu item's A element
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Custom walker class for social media navigation menu
 */
class SelfScan_Walker_Social_Menu extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     *
     * @param string  $output            Used to append additional content (passed by reference).
     * @param WP_Post $item              Menu item data object.
     * @param int     $depth             Depth of menu item.
     * @param array   $args              An object of wp_nav_menu() arguments.
     * @param int     $id                Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $title_attr = ! empty( $item->attr_title ) ? $item->attr_title : $item->title;
        $url = ! empty( $item->url ) ? $item->url : '#';
        
        $output .= '<a href="' . esc_url( $url ) . '" class="menu-header__link" title="' . esc_attr( $title_attr ) . '">';
        
        // Check if we have an ACF field for the icon
        if (function_exists('get_field')) {
            $icon_id = get_field('social_media_icon', $item->ID);
            
            if ($icon_id) {
                // If we have an icon from ACF, display it
                $icon_url = wp_get_attachment_url($icon_id);
                
                if ($icon_url) {
                    // Check if it's an SVG
                    if (substr($icon_url, -4) === '.svg') {
                        // Get path relative to the theme directory
                        $icon_path = str_replace(get_template_directory_uri(), '', $icon_url);
                        
                        // Try to use selfscan_inline_svg if it exists
                        if (function_exists('selfscan_inline_svg')) {
                            selfscan_inline_svg($icon_url, ['class' => 'menu-header__icon']);
                        } else {
                            // Fallback to img tag for SVG
                            $output .= '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($title_attr) . '" class="menu-header__icon">';
                        }
                    } else {
                        // For non-SVG images, use img tag
                        $output .= '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($title_attr) . '" class="menu-header__icon">';
                    }
                }
            } else {
                // Fallback - check for default icons based on title
                $icon_name = strtolower($item->title);
                $icon_path = '/img/icons/' . $icon_name . '.svg';
                
                if (file_exists(get_template_directory() . $icon_path)) {
                    if (function_exists('selfscan_inline_svg')) {
                        selfscan_inline_svg(get_template_directory_uri() . $icon_path, ['class' => 'menu-header__icon']);
                    } else {
                        $output .= '<img src="' . esc_url(get_template_directory_uri() . $icon_path) . '" alt="' . esc_attr($title_attr) . '" class="menu-header__icon">';
                    }
                } else {
                    // If no icon is found, just display the title
                    $output .= esc_html($item->title);
                }
            }
        } else {
            // If ACF is not active, use filename based on title
            $icon_name = strtolower($item->title);
            $icon_path = '/img/icons/' . $icon_name . '.svg';
            
            if (file_exists(get_template_directory() . $icon_path)) {
                if (function_exists('selfscan_inline_svg')) {
                    selfscan_inline_svg(get_template_directory_uri() . $icon_path, ['class' => 'menu-header__icon']);
                } else {
                    $output .= '<img src="' . esc_url(get_template_directory_uri() . $icon_path) . '" alt="' . esc_attr($title_attr) . '" class="menu-header__icon">';
                }
            } else {
                // If no icon is found, just display the title
                $output .= esc_html($item->title);
            }
        }
        
        $output .= '</a>';
    }
    
    /**
     * Ends the element output.
     * No closing li tag needed for social icons as we're not using list items.
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '';
    }
    
    /**
     * Starts the list before the elements are added.
     * No opening ul tag needed for social icons.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '';
    }
    
    /**
     * Ends the list after the elements are added.
     * No closing ul tag needed for social icons.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '';
    }
} 