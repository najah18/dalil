<?php
/**MILLDONE
 * Single Estate Property Template
 * src: single-estate_property.php
 * This file handles the display of a single estate property page in the WpResidence theme.
 * It checks user permissions, loads appropriate templates, and sets up necessary data for property display.
 *
 * @package WpResidence
 * @subpackage PropertyTemplates
 * @since 1.0
 *
 * @uses get_header()
 * @uses get_footer()
 * @uses wp_get_current_user()
 * @uses get_post_custom()
 * @uses wp_estate_count_page_stats()
 * @uses wpresidence_get_option()
 * @uses get_post_meta()
 * @uses wpestate_load_property_page_layout()
 * @uses wpestate_listing_pins()
 * @uses wp_localize_script()
 *
 * Dependencies:
 * - WordPress core functions
 * - WpResidence theme-specific functions
 * 
 * Usage:
 * This file is typically used as a template for single property pages in the WpResidence theme.
 */

// Check post status and user permissions
$status = get_post_status($post->ID);
if (!is_user_logged_in()) {
    if ($status === 'expired') {
        wp_safe_redirect(home_url('/'));
        exit;
    }
} else {
    if (!current_user_can('administrator') && $status === 'expired') {
        wp_safe_redirect(home_url('/'));
        exit;
    }
}

get_header();

// Initialize variables
$show_compare_only = 'no';
$current_user      = wp_get_current_user();
$userID            = $current_user->ID;
$user_option       = 'favorites' . intval($userID);
$wpestate_options  = get_query_var('wpestate_options');

// Get property details and count page views
$wpestate_prop_all_details = get_post_custom($post->ID);
wp_estate_count_page_stats($post->ID);
global $propid;
$propid = $post->ID;

// Load custom template if set
$wp_estate_global_page_template = intval(wpresidence_get_option('wp_estate_global_property_page_template'));
$wp_estate_local_page_template  = intval(get_post_meta($post->ID, 'property_page_desing_local', true));

if ($wp_estate_global_page_template != 0 || $wp_estate_local_page_template != 0) {
 
    $wpestate_wide_elememtor_page_class = '';
    if (wpresidence_get_option('wpestate_wide_elememtor_page') === 'yes') {
        $wpestate_wide_elememtor_page_class = "wpestate_wide_elememtor_page";
    }

    ?>
    
    <!-- Loading Custom template for property page -->
    <div class="container content_wrapper wpestate_content_wrapper_custom_template <?php echo esc_attr($wpestate_wide_elememtor_page_class); ?>">
        <div class="wpestate_content_wrapper_custom_template_wrapper">
            <?php include(locate_template('templates/property_desing_loader.php')); ?>
        </div>
    </div>
    <?php
}

// Load theme template if Elementor is not being used
if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('single')) {
    $wp_estate_property_layouts = intval(wpresidence_get_option('wp_estate_property_layouts'));
    wpestate_load_property_page_layout($wp_estate_property_layouts,$post->ID);
}

// Set up map arguments
$mapargs = array(
    'post_type'   => 'estate_property',
    'post_status' => 'publish',
    'p'           => $post->ID,
    'fields'      => 'ids'
);

$selected_pins = wpestate_listing_pins('blank_single', 0, $mapargs, 1);

// Localize script for Google Maps
wp_localize_script('googlecode_property', 'googlecode_property_vars2', array(
    'markers2' => $selected_pins
));

get_footer();
?>