<?php
/** MILLDONE
 * WpResidence Theme - Agency Profile Template
 * src: templates/dashboard-templates/user_profile_agency.php
 * This template handles the display and functionality of the agency profile page
 * in the WpResidence theme, including contact information, social media links,
 * location details, and profile picture management.
 *
 * @package WpResidence
 * @subpackage AgencyProfile
 * @since 1.0.0
 */

// Retrieve current user and agency information
$current_user = wp_get_current_user();
$userID       = $current_user->ID;
$user_login   = $current_user->user_login;
$agency_id    = get_the_author_meta('user_agent_id', $userID);
$agency_lat   = esc_html(get_post_meta($agency_id, 'agency_lat', true));
$agency_long  = esc_html(get_post_meta($agency_id, 'agency_long', true));

// Define form fields for agency information
$agency_contact_information_fields = array(
    'agency_title'        => array('label' => esc_html__('Agency Name', 'wpresidence'), 'meta' => 'first_name', 'type' => 'wpestate_title'),
    'userphone'           => array('label' => esc_html__('Phone', 'wpresidence'), 'meta_name' => 'agency_phone', 'type' => 'post_meta'),
    'useremail'           => array('label' => esc_html__('Email', 'wpresidence'), 'bypass' => $userID, 'meta' => 'user_email', 'type' => 'user_meta'),
    'usermobile'          => array('label' => esc_html__('Mobile', 'wpresidence'), 'meta_name' => 'agency_mobile', 'type' => 'post_meta'),
    'userskype'           => array('label' => esc_html__('Skype', 'wpresidence'), 'meta_name' => 'agency_skype', 'type' => 'post_meta'),
    'agency_languages'    => array('label' => esc_html__('Languages', 'wpresidence'), 'meta_name' => 'agency_languages', 'type' => 'post_meta'),
    'agency_taxes'        => array('label' => esc_html__('Taxes', 'wpresidence'), 'meta_name' => 'agency_taxes', 'type' => 'post_meta'),
    'agency_license'      => array('label' => esc_html__('License', 'wpresidence'), 'meta_name' => 'agency_license', 'type' => 'post_meta'),
    'agency_address'      => array('label' => esc_html__('Address', 'wpresidence'), 'meta_name' => 'agency_address', 'type' => 'post_meta'),
    'agency_opening_hours'=> array('label' => esc_html__('Opening Hours', 'wpresidence'), 'meta_name' => 'agency_opening_hours', 'type' => 'post_meta'),
);

// Add HubSpot API field if enabled
if (wpresidence_get_option('wp_estate_enable_hubspot_integration_for_all', '') == 'yes') {
    $agency_contact_information_fields['hubspot_api'] = array(
        'label' => esc_html__('HubSpot Private Application Token', 'wpresidence'),
        'meta_name' => 'hubspot_api',
        'type' => 'post_meta',
    );
}

// Define form fields for social media information
$agency_social_information_fields = array(
    'userfacebook'  => array('label' => esc_html__('Facebook Url', 'wpresidence'), 'meta_name' => 'agency_facebook', 'type' => 'post_meta'),
    'userinstagram' => array('label' => esc_html__('Instagram Url', 'wpresidence'), 'meta_name' => 'agency_instagram', 'type' => 'post_meta'),
    'usertwitter'   => array('label' => esc_html__('X - Twitter Url', 'wpresidence'), 'meta_name' => 'agency_twitter', 'type' => 'post_meta'),
    'userpinterest' => array('label' => esc_html__('Pinterest Url', 'wpresidence'), 'meta_name' => 'agency_pinterest', 'type' => 'post_meta'),
    'userlinkedin'  => array('label' => esc_html__('Linkedin Url', 'wpresidence'), 'meta_name' => 'agency_linkedin', 'type' => 'post_meta'),
    'useryoutube'   => array('label' => esc_html__('Youtube Url', 'wpresidence'), 'meta_name' => 'agency_youtube', 'type' => 'post_meta'),
    'usertiktok'    => array('label' => esc_html__('TikTok Url', 'wpresidence'), 'meta_name' => 'agency_tiktok', 'type' => 'post_meta'),
    'usertelegram'  => array('label' => esc_html__('Telegram Url', 'wpresidence'), 'meta_name' => 'agency_telegram', 'type' => 'post_meta'),
    'uservimeo'     => array('label' => esc_html__('Vimeo Url', 'wpresidence'), 'meta_name' => 'agency_vimeo', 'type' => 'post_meta'),
    'agency_website'=> array('label' => esc_html__('Website Url (without http)', 'wpresidence'), 'meta_name' => 'agency_website', 'type' => 'post_meta'),
);

// Define form fields for agency location
$agency_location_fields = array(
    'agency_city'    => array('label' => esc_html__('City', 'wpresidence'), 'meta' => 'last_name', 'type' => 'taxonomy_name', 'tax_name' => 'city_agency'),
    'agency_county'  => array('label' => esc_html__('State/County', 'wpresidence'), 'meta' => 'last_name', 'type' => 'taxonomy_name', 'tax_name' => 'county_state_agency'),
    'agency_area'    => array('label' => esc_html__('Area', 'wpresidence'), 'meta' => 'last_name', 'type' => 'taxonomy_name', 'tax_name' => 'area_agency'),
    'agency_address' => array('label' => esc_html__('Address', 'wpresidence'), 'meta_name' => 'agency_address', 'type' => 'post_meta'),
);

// Define form fields for agency category
$agency_category_fields = array(
    'agency_category_submit' => array('label' => esc_html__('Category', 'wpresidence'), 'inputype' => 'wp_dropdown', 'type' => 'taxonomy', 'meta_name' => 'agency_category', 'tax_name' => 'category_agency'),
    'agency_action_submit'   => array('label' => esc_html__('Action Category', 'wpresidence'), 'inputype' => 'wp_dropdown', 'meta_name' => 'agency_action', 'type' => 'taxonomy', 'tax_name' => 'action_category_agency'),
);

// Define form fields for agency details
$agency_about_details_fields = array(
    'about_me' => array('label' => esc_html__('About Agency', 'wpresidence'), 'meta' => 'about_me', 'inputype' => 'textarea', 'type' => 'wpestate_description', 'bootstral_length' => 12),
    'agency_private_notes' => array('label' => esc_html__('Private Notes', 'wpresidence'), 'inputype' => 'textarea', 'meta_name' => 'agency_private_notes', 'type' => 'post_meta'),
);

// Get agency profile picture
$user_custom_picture = get_the_post_thumbnail_url($agency_id, 'user_picture_profile');
$image_id = get_post_thumbnail_id($agency_id);

if (empty($user_custom_picture)) {
    $user_custom_picture = get_theme_file_uri('/img/default_user.png');
}

$user_agent_id = intval(get_user_meta($userID, 'user_agent_id', true));
?>

<?php if (wp_is_mobile()): ?>
<div class="col-md-12 col-lg-12 user_profile_div">
    <div id="profile_message"></div>
    <div class="add-estate profile-page profile-onprofile">
        <?php
        if ($user_agent_id != 0 && get_post_status($user_agent_id) == 'pending') {
            echo '<div class="user_dashboard_app">' . esc_html__('Your account is pending approval. Please wait for admin to approve it. ', 'wpresidence') . '</div>';
        }
        if ($user_agent_id != 0 && get_post_status($user_agent_id) == 'disabled') {
            echo '<div class="user_dashboard_app">' . esc_html__('Your account is disabled.', 'wpresidence') . '</div>';
        }
        ?>
    </div>
</div>
<?php endif; ?>



<div class=" col-12 col-md-12 col-lg-8 user_profile_div">
    <div class="wpestate_dashboard_content_wrapper">
        <div id="profile_message"></div>

        <div class="add-estate profile-page profile-onprofile row">
            <div class="wpestate_dashboard_section_title"><?php esc_html_e('Contact Information', 'wpresidence'); ?></div>
            <?php echo wpestate_dashnoard_display_form_fields($agency_contact_information_fields, $agency_id); ?>
            <?php wp_nonce_field('profile_ajax_nonce', 'security-profile'); ?>
        </div>

        <div class="add-estate profile-page profile-onprofile row">
            <div class="wpestate_dashboard_section_title"><?php esc_html_e('Social Information', 'wpresidence'); ?></div>
            <?php echo wpestate_dashnoard_display_form_fields($agency_social_information_fields, $agency_id); ?>
        </div>

        <div class="add-estate profile-page profile-onprofile row">
            <div class="wpestate_dashboard_section_title"><?php esc_html_e('Category', 'wpresidence'); ?></div>
            <?php echo wpestate_dashnoard_display_form_fields($agency_category_fields, $agency_id); ?>
        </div>

        <div class="add-estate profile-page profile-onprofile row agency_map_wrapper">
            <div class="wpestate_dashboard_section_title"><?php esc_html_e('Location', 'wpresidence'); ?></div>
            <?php echo wpestate_dashnoard_display_form_fields($agency_location_fields, $agency_id); ?>
            <div id="googleMapsubmit"></div>

            <p class="fullp-button">
                <button id="google_agency_location" class="wpresidence_button wpresidence_success"><?php esc_html_e('Place Pin with Agency Address', 'wpresidence'); ?></button>
            </p>

            <input type="hidden" name="agency_lat" id="agency_lat" value="<?php echo esc_attr($agency_lat); ?>">
            <input type="hidden" name="agency_long" id="agency_long" value="<?php echo esc_attr($agency_long); ?>">
        </div>

        <div class="add-estate profile-page profile-onprofile row">
            <div class="wpestate_dashboard_section_title"><?php esc_html_e('About Agency', 'wpresidence'); ?></div>
            <?php echo wpestate_dashnoard_display_form_fields($agency_about_details_fields, $agency_id); ?>
        </div>

   
        <?php if ( !wp_is_mobile() ) {  ?>
            
            <button class="wpresidence_button" id="update_profile_agency"><?php esc_html_e('Update profile', 'wpresidence'); ?></button>
            <?php
            $ajax_nonce = wp_create_nonce("wpestate_update_profile_nonce");
            echo '<input type="hidden" id="wpestate_update_profile_nonce" value="' . esc_attr($ajax_nonce) . '" />';

            if ($user_agent_id != 0 && get_post_status($user_agent_id) == 'publish') {
                echo '<a href="' . esc_url(get_permalink($user_agent_id)) . '" class="wpresidence_button view_public_profile">' . esc_html__('View public profile', 'wpresidence') . '</a>';
            }
            ?>
            <button class="wpresidence_button" id="delete_profile"><?php esc_html_e('Delete profile', 'wpresidence'); ?></button>

        <?php } ?>


    </div>
</div>

<div class="col-12 col-md-12 col-lg-4  user-profile-dashboard-wrapper">
    <div class="wpestate_dashboard_content_wrapper">
        <div class="add-estate profile-page profile-onprofile row">
            <div class="wpestate_dashboard_section_title"><?php esc_html_e('Photo', 'wpresidence'); ?></div>
            <div class="profile_div" id="profile-div">
                <img id="profile-image" src="<?php echo esc_url($user_custom_picture); ?>" alt="<?php esc_attr_e('user image', 'wpresidence'); ?>" data-profileurl="<?php echo esc_attr($user_custom_picture); ?>" data-smallprofileurl="<?php echo esc_attr($image_id); ?>" />
                <div id="upload-container">
                    <div id="aaiu-upload-container">
                        <button id="aaiu-uploader" type="button" class="wpresidence_button wpresidence_success"><?php esc_html_e('Upload profile image', 'wpresidence'); ?></button>
                        <div id="aaiu-upload-imagelist">
                            <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                        </div>
                    </div>
                </div>
                <div id="imagelist-profile"></div>
                <span class="upload_explain"><?php esc_html_e('*minimum 500px x 500px', 'wpresidence'); ?></span>
            </div>
        </div>
    </div>
</div>

<?php if ( wp_is_mobile() ) {  ?>
        <div class="fullp-button m-0 p-3">
            <button class="wpresidence_button w-100" id="update_profile_agency"><?php esc_html_e('Update profile', 'wpresidence'); ?></button>
            <?php
            $ajax_nonce = wp_create_nonce("wpestate_update_profile_nonce");
            echo '<input type="hidden" id="wpestate_update_profile_nonce" value="' . esc_attr($ajax_nonce) . '" />';

            if ($user_agent_id != 0 && get_post_status($user_agent_id) == 'publish') {
                echo '<a href="' . esc_url(get_permalink($user_agent_id)) . '" class="wpresidence_button text-center view_public_profile w-100">' . esc_html__('View public profile', 'wpresidence') . '</a>';
            }
            ?>
            <button class="wpresidence_button w-100" id="delete_profile"><?php esc_html_e('Delete profile', 'wpresidence'); ?></button>
        </div>
<?php } ?>

<div class="col-12 col-md-12 col-lg-8 change_pass_wrapper">
    <div class="wpestate_dashboard_content_wrapper">
        <?php get_template_part('templates/dashboard-templates/change_pass_template'); ?>
    </div>
</div>

