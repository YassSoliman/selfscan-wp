<?php
/**
 * ACF Fields for Menu Items
 *
 * This file handles registration of ACF fields for menu items
 *
 * @package selfscan
 */

/**
 * Register ACF fields for menu items
 */
function selfscan_register_menu_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_social_menu_icons',
        'title' => 'Social Media Icons',
        'fields' => array(
            array(
                'key' => 'field_social_media_icon',
                'label' => 'Icon',
                'name' => 'social_media_icon',
                'type' => 'image',
                'instructions' => 'Select an icon for this social media link.',
                'required' => 0,
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'nav_menu_item',
                    'operator' => '==',
                    'value' => 'all',
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
    ));
}
add_action('acf/init', 'selfscan_register_menu_acf_fields');

/**
 * Filter ACF field groups to only show on social menu items
 */
function selfscan_filter_acf_social_menu_fields($field_group) {
    // If not in admin or not the social menu field group, return
    if (!is_admin() || $field_group['key'] !== 'group_social_menu_icons') {
        return $field_group;
    }
    
    // Get the currently selected menu
    $current_menu = isset($_REQUEST['menu']) ? absint($_REQUEST['menu']) : 0;
    if (!$current_menu) {
        return $field_group;
    }
    
    // Check if this menu is assigned to the social-menu location
    $locations = get_nav_menu_locations();
    $is_social_menu = isset($locations['social-menu']) && $locations['social-menu'] == $current_menu;
    
    if (!$is_social_menu) {
        // Hide this field group
        $field_group['active'] = false;
    }
    
    return $field_group;
}
add_filter('acf/load_field_group', 'selfscan_filter_acf_social_menu_fields');

/**
 * Get menu item icon by menu item ID
 *
 * @param int $menu_item_id Menu item ID.
 * @return string|false Icon URL or false if not found.
 */
function selfscan_get_menu_item_icon($menu_item_id) {
    if (!function_exists('get_field')) {
        return false;
    }

    $icon_id = get_field('social_media_icon', $menu_item_id);
    if (!$icon_id) {
        return false;
    }

    return wp_get_attachment_url($icon_id);
}

/**
 * Fix media modal templates for menu editor
 * This adds the missing media template that causes the error
 */
function selfscan_fix_media_templates_for_menu() {
    global $pagenow;
    
    // Only add this on the nav-menus.php page
    if ($pagenow !== 'nav-menus.php') {
        return;
    }
    
    // Make sure wp_enqueue_media is called
    wp_enqueue_media();
    
    // Add the media template to fix the error
    add_action('admin_footer', function() {
        ?>
        <!-- Fix for missing media modal template -->
        <script type="text/html" id="tmpl-media-modal">
            <div class="media-modal wp-core-ui">
                <button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e( 'Close media panel', 'selfscan' ); ?></span></span></button>
                <div class="media-modal-content"></div>
            </div>
            <div class="media-modal-backdrop"></div>
        </script>
        
        <!-- Ensure WP media is properly initialized -->
        <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                if (typeof wp !== 'undefined' && wp.media && !wp.media.view.settings.post) {
                    wp.media.view.settings.post = { id: 0 };
                }
                
                // Override ACF's image field behavior for menus if needed
                if (typeof acf !== 'undefined') {
                    acf.addAction('ready', function() {
                        // Add a click listener to all image field buttons
                        $('.acf-field-image .acf-button').on('click', function(e) {
                            // If the media modal fails to open, try to initialize it manually
                            if (!$('.media-modal:visible').length) {
                                var frame = wp.media({
                                    title: 'Select or Upload Media',
                                    button: { text: 'Use this media' },
                                    multiple: false
                                });
                                
                                frame.on('select', function() {
                                    var attachment = frame.state().get('selection').first().toJSON();
                                    // Find the nearest input field and update it
                                    var $field = $(e.target).closest('.acf-field-image');
                                    var $input = $field.find('input[type="hidden"]');
                                    $input.val(attachment.id).trigger('change');
                                    
                                    // Update preview
                                    var $img = $field.find('img');
                                    if ($img.length) {
                                        $img.attr('src', attachment.url);
                                    } else {
                                        $field.find('.acf-image-uploader-aspect-ratio').html('<img src="'+attachment.url+'" alt="">');
                                    }
                                    
                                    // Show remove button
                                    $field.find('.acf-actions').show();
                                    $field.addClass('-has-value');
                                });
                                
                                frame.open();
                                e.preventDefault();
                            }
                        });
                    });
                }
            });
        })(jQuery);
        </script>
        <?php
    }, 20);
}
add_action('admin_init', 'selfscan_fix_media_templates_for_menu'); 