<?php
/** MILLDONE
 * Template for displaying property details on property cards (Type 8)
 * src: templates\property_cards_templates\property_card_details_templates\property_card_details_type8.php
 * This template is part of the WpResidence theme and is used to show
 * key property features such as size, number of bedrooms, and bathrooms
 * in a compact format within property cards.
 *
 * @package WpResidence
 * @subpackage PropertyCard
 * @since WpResidence 1.0
 */
?>

<div class="property_details_type1_wrapper"> 
    <?php
    // Retrieve and format property details
    $property_size = wpestate_get_converted_measure($postID, 'property_size');
    $property_bedrooms = get_post_meta($postID, 'property_bedrooms', true);
    $property_bathrooms = get_post_meta($postID, 'property_bathrooms', true);

    // Display number of bedrooms if available
    if (!empty($property_bedrooms) && $property_bedrooms != 0) {
        $bedroom_string = sprintf(
            _n(
                '<span class="property_details_type1_value">%d</span> Bedroom',
                '<span class="property_details_type1_value">%d</span> Bedrooms',
                $property_bedrooms,
                'wpresidence'
            ),
            $property_bedrooms
        );
        echo '<span class="property_details_type1_rooms">' . $bedroom_string . '</span>';

        // Add separator if there are more details to display
        if ($property_bathrooms != 0 || $property_size != 0) {
            echo ' <span>&#183;</span> ';
        }
    }

    // Display number of bathrooms if available
    if (!empty($property_bathrooms) && $property_bathrooms != 0) {
        $bathroom_string = sprintf(
            _n(
                '<span class="property_details_type1_value">%d</span> Bathroom',
                '<span class="property_details_type1_value">%d</span> Bathrooms',
                $property_bathrooms,
                'wpresidence'
            ),
            $property_bathrooms
        );
        echo '<span class="property_details_type1_baths">' . $bathroom_string . '</span>';

        // Add separator if property size is available
        if ($property_size != 0) {
            echo ' <span>&#183;</span> ';
        }
    }

    // Display property size if available
    if (!empty($property_size) && $property_size != '0') {
        echo '<span class="property_details_type1_size"><span class="property_details_type1_value">' . 
             esc_html__('Size', 'wpresidence') . ' ' . $property_size . '</span></span>';
    }
    ?>
</div>