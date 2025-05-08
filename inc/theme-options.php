<?php
/**
 * Theme Options Page for SelfScan
 *
 * @package SelfScan
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Theme Options Page
 */
function selfscan_register_theme_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'    => 'SelfScan Settings',
            'menu_title'    => 'SelfScan Settings',
            'menu_slug'     => 'selfscan-settings',
            'capability'    => 'manage_options',
            'redirect'      => false,
            'icon_url'      => 'dashicons-shield',
            'position'      => 80
        ));
    }
}
add_action('acf/init', 'selfscan_register_theme_options_page');

/**
 * Register Theme Options Fields
 */
function selfscan_register_theme_options_fields() {
    if (function_exists('acf_add_local_field_group')) {
        // Top Bar Settings
        acf_add_local_field_group(array(
            'key' => 'group_top_bar_settings',
            'title' => 'Top Bar Settings',
            'fields' => array(
                array(
                    'key' => 'field_top_bar_tab',
                    'label' => 'Top Bar',
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
                    'key' => 'field_show_top_bar',
                    'label' => 'Show Top Bar',
                    'name' => 'show_top_bar',
                    'type' => 'true_false',
                    'instructions' => 'Toggle to show or hide the top information bar.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => 'Show',
                    'ui_off_text' => 'Hide',
                ),
                array(
                    'key' => 'field_top_bar_content',
                    'label' => 'Top Bar Content',
                    'name' => 'top_bar_content',
                    'type' => 'wysiwyg',
                    'instructions' => 'Enter the content you want to display in the top bar.',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_show_top_bar',
                                'operator' => '==',
                                'value' => 1,
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '<ul class="top-section__list">
    <li class="top-section__item">
        Phone Support
        <a href="tel:18001234567" class="top-section__link">1-800-123-4567</a>
    </li>
    <li class="top-section__item">
        Email
        <a href="mailto:info@selfscan.ca" class="top-section__link">info@selfscan.ca</a>
    </li>
</ul>',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                    'delay' => 0,
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
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
    }
}
add_action('acf/init', 'selfscan_register_theme_options_fields'); 