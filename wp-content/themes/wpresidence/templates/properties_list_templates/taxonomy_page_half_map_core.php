<?php
/** MILLDONE
 * Template for displaying property listings for taxonomies with a half map layout
 * src: templates\properties_list_templates\taxonomy_page_half_map_core.php
 * This template is part of the WpResidence theme and handles the display of
 * property listings for specific taxonomies alongside a map view. It includes 
 * advanced search options, property listings, and a Google Map.
 *
 * @package WpResidence
 * @subpackage PropertyListings
 * @since WpResidence 1.0
 */

// Retrieve theme options for property unit display
$wpestate_custom_unit_structure = wpresidence_get_option('wpestate_property_unit_structure');
$wpestate_uset_unit             = intval(wpresidence_get_option('wpestate_uset_unit', ''));
$wpestate_no_listins_per_row    = intval(wpresidence_get_option('wp_estate_listings_per_row', ''));
$wpestate_property_unit_slider  = wpresidence_get_option('wp_estate_prop_list_slider', '');

// Set up top bar style
$top_bar_style = "";
if (esc_html(wpresidence_get_option('wp_estate_show_top_bar_user_menu', '')) == "no") {
    $top_bar_style = ' half_no_top_bar ';
}

// Get header logo type and include hidden filters for AJAX search
$logo_header_type = wpresidence_get_option('wp_estate_logo_header_type', '');
include(locate_template('templates/properties_list_templates/filters_templates/taxomomy_ajax_tax_hidden_filters.php'));

// Set up property card type
$property_card_type = intval(wpresidence_get_option('wp_estate_unit_card_type'));
$property_card_type_string = ($property_card_type === 0) ? '' : '_type' . $property_card_type;

// Determine which column class we will use
$property_unit_class = wpestate_return_unit_class($wpestate_options);

// Open in new page option
$new_page_option = wpresidence_get_option('wp_estate_unit_card_new_page', '');

// Get half map position setting
$map_orientation_class = wpresidence_map_map_orientation_class();
// Set up mobile view and compare options
$show_mobile = 1;
$show_compare_only = 'yes';
?>

<div class="d-flex  flex-column  <?php echo esc_attr($map_orientation_class ); ?> ">
    <!-- Google Map Wrapper -->
    <div id="google_map_prop_list_wrapper" 
         class="col-12 col-md-6 half_mobile_hide google_map_prop_list <?php echo esc_attr($top_bar_style . ' half_' . $logo_header_type); ?>">
        <?php 
        // Include Google Maps base template
        include(locate_template('templates/google_maps_base.php')); 
        ?>
    </div>    
    
    <!-- Property Listings Sidebar -->
    <div id="google_map_prop_list_sidebar" 
         class="col-12 col-md-6 <?php echo esc_attr($top_bar_style . ' half_' . $logo_header_type); ?> ">
        <!-- Advanced Search Section -->
        <div class="search_wrapper">
            <?php 
            // Include advanced search form and property list filters
            include(get_theme_file_path('templates/advanced_search/advanced_search_type_half.php'));
            include(get_theme_file_path('templates/advanced_search/property_list_filter_half.php'));
            ?>
        </div>
 
        <div class="single-content">
            <?php
            // Display taxonomy title
            if (is_tax()) { 
                ?>
                <h1 class="entry-title title_prop"> 
                    <?php 
                    esc_html_e('Properties listed in ', 'wpresidence');
                    single_cat_title();
                    ?>
                </h1>
            <?php
            }
            ?>  
</div>
        <?php 
        // Include loading spinner template
        get_template_part('templates/spiner'); 
        ?> 

        <!-- Property Listings Container -->
        <div id="listing_ajax_container" class="ajax-map row"> 
            <?php
            $counter = 0;
            wpresidence_display_property_list_as_html($prop_selection,$wpestate_options  ,'property_list');
              
            ?>
        </div>
        <!-- Listings End here --> 
        
        <!-- Pagination -->
        <div class="half-pagination">
            <?php wpestate_pagination($prop_selection->max_num_pages, $range = 2); ?>       
        </div>    
    </div><!-- end sidebar container-->
</div>

<!-- Mobile view controllers -->
<div class="half_map_controllers_wrapper">
    <div class="half_mobile_toggle_listings half_control_visible"><i class="fas fa-bars"></i><?php esc_html_e('Listings', 'wpresidence'); ?></div>
    <div class="half_mobile_toggle_map"><i class="far fa-map"></i> <?php esc_html_e('Map View', 'wpresidence'); ?></div>
</div>