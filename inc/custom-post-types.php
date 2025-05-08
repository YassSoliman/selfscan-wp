<?php
/**
 * Custom Post Types for SelfScan Theme
 *
 * @package SelfScan
 */

/**
 * Register custom post types for the theme
 */
function selfscan_register_custom_post_types() {
    // Register FAQ Custom Post Type - Simplified version
    $labels = array(
        'name'                  => _x( 'FAQs', 'Post type general name', 'selfscan' ),
        'singular_name'         => _x( 'FAQ', 'Post type singular name', 'selfscan' ),
        'menu_name'             => _x( 'FAQs', 'Admin Menu text', 'selfscan' ),
        'name_admin_bar'        => _x( 'FAQ', 'Add New on Toolbar', 'selfscan' ),
        'add_new'               => __( 'Add New', 'selfscan' ),
        'add_new_item'          => __( 'Add New FAQ', 'selfscan' ),
        'new_item'              => __( 'New FAQ', 'selfscan' ),
        'edit_item'             => __( 'Edit FAQ', 'selfscan' ),
        'view_item'             => __( 'View FAQ', 'selfscan' ),
        'all_items'             => __( 'All FAQs', 'selfscan' ),
        'search_items'          => __( 'Search FAQs', 'selfscan' ),
        'not_found'             => __( 'No FAQs found.', 'selfscan' ),
        'not_found_in_trash'    => __( 'No FAQs found in Trash.', 'selfscan' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-format-chat',
        'supports'           => array( 'title' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'faq', $args );
}
add_action( 'init', 'selfscan_register_custom_post_types' ); 