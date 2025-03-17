<?php
/**MILLDONE
 * Template for displaying Property Card Details (Type 4)
 * src: templates\property_cards_templates\property_card_details_templates\property_card_details_type4.php
 * This file is part of the WpResidence theme and is used to render
 * the details section of a property card for Type 4 layout.
 */

// Set up necessary variables
$property_size = wpestate_get_converted_measure($postID, 'property_size');
$property_bedrooms = get_post_meta($postID, 'property_bedrooms', true) ?? '';
$property_bathrooms = get_post_meta($postID, 'property_bathrooms', true) ?? '';
$prop_id = $postID;
?>

<div class="property_listing_details">
    <?php
    // Display number of bedrooms
    if ($property_bedrooms != '' && $property_bedrooms != 0) {
        echo '<div class="inforoom_unit_type4">' . esc_html($property_bedrooms) . ' ' . esc_html__('Bedrooms', 'wpresidence') . '</div>';

        // Add separator if there are more details to display
        if (($property_bathrooms != '' && $property_bathrooms != 0) || 
            ($property_size != '' && strval($property_size) != '0')) {
            echo ' ' . trim('<span>&#183;</span>') . ' ';
        }
    }

    // Display number of bathrooms
    if ($property_bathrooms != '' && $property_bathrooms != 0) {
        echo '<div class="infobath_unit_type4">' . esc_html($property_bathrooms) . ' ' . esc_html__('Baths', 'wpresidence') . '</div>';

        // Add separator if there's property size to display
        if ($property_size != '' && strval($property_size) != '0') {
            echo ' ' . trim('<span>&#183;</span>') . ' ';
        }
    }

    // Display property size
    if ($property_size != '' && strval($property_size) != '0') {
        echo '<div class="infosize_unit_type4">' . esc_html__('Size', 'wpresidence') . ' ' . $property_size . '</div>';
    }
    ?>
</div>

<?php

// Display additional property details

if ($wpresidence_property_cards_context['property_unit_class']['col_class'] == 'col-md-12') {
?>
    <div class="listing_details the_list_view" style="display:block;">
        <?php  echo wpresidence_unit_card_generate_excerpt_html(100, $postID); ?>
    </div>   

    <div class="listing_details half_map_list_view">
        <?php  echo wpresidence_unit_card_generate_excerpt_html(60, $postID); ?>
    </div>   
<?php
} else {
?>
    <div class="listing_details the_list_view">
        <?php  echo wpresidence_unit_card_generate_excerpt_html(100, $postID); ?>
    </div>
<?php 
}
?>