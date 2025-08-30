<?php
/**
 * ACF Field Configuration for Blog Settings
 *
 * @package SelfScan
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Blog Settings Fields
 */
function selfscan_register_blog_settings_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_blog_settings',
            'title' => 'Blog Settings',
            'fields' => array(
                array(
                    'key' => 'field_blog_settings_tab',
                    'label' => 'Blog Settings',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_featured_blog_post',
                    'label' => 'Featured Blog Post',
                    'name' => 'featured_blog_post',
                    'type' => 'post_object',
                    'instructions' => 'Select a blog post to feature prominently at the top of the blog page. This post will appear in the hero section and will be excluded from the regular articles list below.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post',
                    ),
                    'taxonomy' => '',
                    'return_format' => 'object',
                    'multiple' => 0,
                    'allow_null' => 1,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_blog_posts_per_page',
                    'label' => 'Posts Per Page',
                    'name' => 'blog_posts_per_page',
                    'type' => 'number',
                    'instructions' => 'Number of regular posts to display in the articles list (excluding the featured post). Default is 3.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 3,
                    'min' => 1,
                    'max' => 12,
                    'step' => 1,
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_show_load_more',
                    'label' => 'Show "Load More" Button',
                    'name' => 'show_load_more_button',
                    'type' => 'true_false',
                    'instructions' => 'Display the "Load more articles" button when there are additional posts available.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => 'Show',
                    'ui_off_text' => 'Hide',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'selfscan-settings',
                    ),
                ),
            ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => 'Configure blog page settings including featured post selection and display options.',
            'show_in_rest' => 0,
        ));
    }
}
add_action('acf/init', 'selfscan_register_blog_settings_fields');

/**
 * Helper function to get the featured blog post
 *
 * @return WP_Post|false Featured post object or false
 */
function selfscan_get_featured_blog_post() {
    if (function_exists('get_field')) {
        $featured_post = get_field('featured_blog_post', 'option');
        if ($featured_post && $featured_post->post_status === 'publish') {
            return $featured_post;
        }
    }
    return false;
}

/**
 * Helper function to get blog posts per page setting
 *
 * @return int Number of posts per page
 */
function selfscan_get_blog_posts_per_page() {
    if (function_exists('get_field')) {
        $posts_per_page = get_field('blog_posts_per_page', 'option');
        return $posts_per_page ? (int) $posts_per_page : 3;
    }
    return 3;
}

/**
 * Helper function to check if load more button should be shown
 *
 * @return bool True if load more button should be shown
 */
function selfscan_show_load_more_button() {
    if (function_exists('get_field')) {
        return (bool) get_field('show_load_more_button', 'option');
    }
    return true;
}