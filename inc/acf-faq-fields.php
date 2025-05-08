<?php
/**
 * ACF Field Configuration for FAQ Post Type
 *
 * @package SelfScan
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
        'key' => 'group_faq_content',
        'title' => 'FAQ Content',
        'fields' => array(
            array(
                'key' => 'field_faq_description',
                'label' => 'Description',
                'name' => 'faq_description',
                'type' => 'wysiwyg',
                'instructions' => 'Enter the answer to this FAQ question',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'faq',
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
        'description' => 'Fields for FAQ post type',
    ));
} 