<?php
/**
 * ACF Field Configuration for FAQ Module
 *
 * @package SelfScan
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
        'key' => 'group_faq_module',
        'title' => 'FAQ Module Settings',
        'fields' => array(
            array(
                'key' => 'field_faq_module_title',
                'label' => 'FAQ Section Title',
                'name' => 'faq_section_title',
                'type' => 'text',
                'instructions' => 'Enter the title for this FAQ section',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 'Frequently Asked Questions',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_faq_module_include_faqs',
                'label' => 'Select FAQs',
                'name' => 'selected_faqs',
                'type' => 'relationship',
                'instructions' => 'Select which FAQs to display in this section. Leave empty to display all.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'faq',
                ),
                'filters' => array(
                    0 => 'search',
                ),
                'min' => '',
                'max' => '',
                'return_format' => 'id',
            ),
            array(
                'key' => 'field_faq_module_max_items',
                'label' => 'Maximum FAQs to Show',
                'name' => 'max_faqs',
                'type' => 'number',
                'instructions' => 'Maximum number of FAQs to display. Leave empty to show all selected.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => 1,
                'max' => '',
                'step' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-templates/template-home.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-templates/template-faq.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-templates/template-pricing.php',
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
        'description' => 'Settings for the reusable FAQ module',
    ));
} 