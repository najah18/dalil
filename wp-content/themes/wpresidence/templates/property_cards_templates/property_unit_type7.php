<?php
/** MILLDONE
 * Template for displaying a property unit of type 7
 * src: templates\property_cards_templates\property_unit_type7.php
 * This template is part of the WpResidence theme and is used to display individual
 * property units in a grid or list view. It's typically called by loop files or
 * shortcodes that display multiple properties.
 *
 * @package WpResidence
 * @subpackage PropertyTemplates
 * @since WpResidence 1.0
 */

// Initialize content class
$content_class = isset($wpestate_options['content_class']) ? $wpestate_options['content_class'] : '';


// Retrieve essential property details
$title = get_the_title();
$link = esc_url(get_permalink());
$main_image = wpestate_return_property_card_main_image($postID, 'listing_full_slider');

// Check if we should use the property page composer for details
$wp_estate_use_composer_details = wpresidence_get_option('wp_estate_use_composer_details', '');

// Get realtor details (unused in this template, consider removing if not needed elsewhere)
$realtor_details = wpestate_return_agent_details($postID);


?>  

<div class="<?php echo esc_html($wpresidence_property_cards_context['property_unit_class']['col_class']); ?> listing_wrapper  property_unit_type7" 
    data-org="<?php echo esc_attr($wpresidence_property_cards_context['property_unit_class']['col_org']); ?>"   
    data-main-modal="<?php echo esc_attr($main_image); ?>"
    data-modal-title="<?php echo esc_attr($title); ?>"
    data-modal-link="<?php echo esc_attr($link); ?>"
    data-listid="<?php echo intval($postID); ?>"> 
    
    <div class="property_listing property_unit_type7 <?php echo wpestate_interior_classes($wpresidence_property_cards_context['wpestate_uset_unit']); ?>" 
         data-link="<?php echo $wpresidence_property_cards_context['wpestate_property_unit_slider'] == 0 ? esc_url($link) : ''; ?>">

        <?php 
        if ($wpresidence_property_cards_context['wpestate_uset_unit'] == 1) {
            // Build custom unit structure if enabled
            wpestate_build_unit_custom_structure($wpestate_custom_unit_structure, $postID, $wpestate_property_unit_slider);
        } else {
            // Default structure
            ?>
            <div class="listing-unit-img-wrapper">
                <div class="featured_gradient"></div>
                <?php include(locate_template('templates/property_cards_templates/property_card_details_templates/property_card_slider.php')); ?>
                <?php      include (locate_template( 'templates/property_cards_templates/property_card_details_templates/property_card_tags.php')); ?>
                <?php      include (locate_template( 'templates/property_cards_templates/property_card_details_templates/property_card_actions_type_default.php')); ?>
            </div>
    
            <div class="property-unit-information-wrapper">
                <?php 
                if ($wp_estate_use_composer_details == 'yes') {         
                    // Use property page composer for details
                    wpestate_return_property_card_content($postID,$wpresidence_property_cards_context, 7);
                } else {
                    // Display default property information
                    echo wpestate_return_property_card_categories($postID);
                    include(locate_template('templates/property_cards_templates/property_card_details_templates/property_card_title.php'));
                    include (locate_template( 'templates/property_cards_templates/property_card_details_templates/property_card_price.php'));
                    include( locate_template('templates/property_cards_templates/property_card_details_templates/property_card_content.php'));
                    include( locate_template('templates/property_cards_templates/property_card_details_templates/property_card_details_type7.php')); 
                }
                
                // Display agent information if enabled in theme options
                if (wpresidence_get_option('property_card_agent_show_row', '') == 'yes') { 
                    ?>     
                    <div class="property_location">
                        <?php 
                        include(locate_template('templates/property_cards_templates/property_card_details_templates/property_card_contact.php'));
                        ?>                            
                    </div>    
                    <?php
                } 
                ?>
            </div>
            <?php
        }
        ?>
    </div>    
</div>
