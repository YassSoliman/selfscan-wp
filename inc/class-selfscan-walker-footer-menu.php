<?php
/**
 * Custom Footer Menu Walker for SelfScan theme
 *
 * @package SelfScan
 */

/**
 * Custom walker class for footer navigation
 */
class SelfScan_Walker_Footer_Menu extends Walker_Nav_Menu {
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
        
        // Add footer-menu__item class to all items
        $classes[] = 'footer-menu__item';
        
        // Filter the CSS classes applied to the menu item's LI element
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        
        $output .= '<li' . $class_names . '>';
        
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';
        $atts['class']  = 'footer-menu__link';
        
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
 * Custom walker class for footer right menu (social/connect links)
 */
class SelfScan_Walker_Footer_Right_Menu extends Walker_Nav_Menu {
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
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
        
        // For parent items (depth 0), create h2 heading
        if ($depth === 0) {
            $output .= '<div class="footer__item">';
            $output .= '<h2 class="footer__label">' . $title . '</h2>';
        } else {
            // For child items, create anchor tags with footer__link class
            $atts = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target ) ? $item->target : '';
            $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
            $atts['href']   = ! empty( $item->url ) ? $item->url : '#';
            $atts['class']  = 'footer__link';
            
            // Filter the HTML attributes applied to the menu item's A element
            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
            
            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
            
            $output .= '<a' . $attributes . '>' . $title . '</a>';
        }
    }
    
    /**
     * Ends the element output, if needed.
     * This method closes divs for parent items.
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ($depth === 0) {
            $output .= '</div>';
        }
    }
    
    /**
     * Starts the list before the elements are added.
     * No opening ul tag needed for footer right menu.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '';
    }
    
    /**
     * Ends the list after the elements are added.
     * No closing ul tag needed for footer right menu.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '';
    }
} 