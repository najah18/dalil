<?php
/** MILLDONE
 * Template Name: Front Property Submit
 * src: page-templates\front_property_submit.php
 * This template handles the front-end property submission process for the WpResidence theme.
 * It allows users to submit new property listings, including details, images, and custom fields.
 *
 * @package WpResidence
 * @subpackage PropertySubmission
 * @since 1.0.0
 */

// Check for WpResidence Core Plugin
if (!function_exists('wpestate_residence_functionality')) {
    esc_html_e('This page will not work without WpResidence Core Plugin, Please activate it from the plugins menu!', 'wpresidence');
    exit();
}

// Get current user information
$current_user = wp_get_current_user();
$userID = $current_user->ID;
$user_agent_id = intval(get_user_meta($userID, 'user_agent_id', true));
$status = get_post_status($user_agent_id);

// Redirect if user is pending or disabled
if ($status === 'pending' || $status === 'disabled') {
    wp_redirect(esc_url(home_url('/')));
    exit;
}

// Add allowed tags filter
add_filter('wp_kses_allowed_html', 'wpestate_add_allowed_tags');

// Initialize variables
$user_pack = get_the_author_meta('package_id', $userID);
$property_features = get_terms(array(
    'taxonomy' => 'property_features',
    'hide_empty' => false,
));
$property_status_array = get_terms(array(
    'taxonomy' => 'property_status',
    'hide_empty' => false,
));

$allowed_html = array();
$wpestate_submission_page_fields = wpresidence_get_option('wp_estate_submission_page_fields', '');
$all_submission_fields = wpestate_return_all_fields();
$agent_list = (array)get_user_meta($userID, 'current_agent_list', true);
$moving_array=array();
$wpestate_show_err=array();

// Define allowed HTML for description
$allowed_html_desc = array(
    'a' => array('href' => array(), 'title' => array()),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
    'ul' => array('li' => array()),
    'li' => array(),
    'code' => array(),
    'ol' => array('li' => array()),
    'del' => array('datetime' => array()),
    'blockquote' => array(),
    'ins' => array(),
);

// Initialize property variables
$property_variables = array(
    'property_city' => '',
    'property_area' => '',
    'prop_category_selected' => '',
    'prop_action_category_selected' => '',
    'get_listing_edit' => '',
    'submit_title' => '',
    'submit_description' => '',
    'prop_category' => '',
    'property_address' => '',
    'property_county' => '',
    'property_state' => '',
    'property_zip' => '',
    'country_selected' => '',
    'prop_stat' => '',
    'property_status' => '',
    'property_price' => '',
    'property_label' => '',
    'property_label_before' => '',
    'property_second_price' => '',
    'property_second_price_label' => '',
    'property_label_before_second_price' => '',
    'property_year_tax' => '',
    'property_hoa' => '',
    'property_size' => '',
    'owner_notes' => '',
    'property_lot_size' => '',
    'property_year' => '',
    'property_rooms' => '',
    'property_bedrooms' => '',
    'property_bathrooms' => '',
    'option_video' => '',
    'option_slider' => '',
    'video_type' => '',
    'embed_video_id' => '',
    'virtual_tour' => '',
    'property_latitude' => '',
    'property_longitude' => '',
    'google_view' => '',
    'google_camera_angle' => '',
    'property_hide_map_marker' => '',
    'prop_category' => '',
    'plan_title_array' => '',
    'plan_desc_array' => '',
    'plan_image_array' => '',
    'plan_size_array' => '',
    'plan_rooms_array' => '',
    'plan_bath_array' => '',
    'plan_price_array' => '',
    'property_has_subunits' => '',
    'property_subunits_list' => '',
    'energy_class' => '',
    'energy_index' => '',
);

// Extract variables to make them accessible in the template
extract($property_variables);

// Get custom fields
$custom_fields = wpresidence_get_option('wp_estate_custom_fields', '');
$custom_fields_array = array();
if (!empty($custom_fields)) {
    foreach ($custom_fields as $field) {
        $name = $field[0];
        $type = $field[2];
        $slug = wpestate_limit45(sanitize_title($name));
        $slug = sanitize_key($slug);
        $custom_fields_array[$slug] = '';
    }
}

// Generate property status options
$property_status = '';
if (is_array($property_status_array)) {
    foreach ($property_status_array as $term) {
        $property_status .= '<option value="' . esc_attr($term->name) . '">' . esc_html($term->name) . '</option>';
    }
}

// Generate slider options
$option_slider = '';
$slider_values = array('full top slider', 'small slider');
foreach ($slider_values as $value) {
    $option_slider .= '<option value="' . esc_attr($value) . '">' . esc_html($value) . '</option>';
}

// Get theme options
$wpestate_options = get_query_var('wpestate_options');
$wpestate_no_listins_per_row = intval(wpresidence_get_option('wp_estate_agent_listings_per_row', ''));

// Determine column class
$col_class = ($wpestate_options['content_class'] == 'col-md-12') ? 3 : 4;
if ($wpestate_no_listins_per_row == 3) {
    $col_class = ($wpestate_options['content_class'] == 'col-md-12') ? 4 : 6;
}

// Process form submission
if( isset($_POST) && isset($_POST['action']) && $_POST['action']=='front_submit' && is_user_logged_in()) {


    $parent_userID              =   wpestate_check_for_agency($userID);
    $paid_submission_status     =    esc_html ( wpresidence_get_option('wp_estate_paid_submission','') );
    $user_pack                  =   get_the_author_meta( 'package_id' , $parent_userID );
    $remaining_listings         =   wpestate_get_remain_listing_user($parent_userID,$user_pack);

    if($remaining_listings  === -1){
        $remaining_listings=11;
    }

    if ( $paid_submission_status == 'membership' && $remaining_listings != -1 && $remaining_listings < 1 ){
        $has_errors=true;
        $wpestate_show_err=esc_html__('Your current package doesn\'t let you publish more properties! You need to upgrade your membership.','wpresidence' );
    }

    else
    if ( $paid_submission_status!='membership' || ( $paid_submission_status== 'membership' || wpestate_get_current_user_listings($parent_userID) > 0)  ){ // if user can submit

        if( !isset($_POST['prop_category']) || $_POST['prop_category']==-1 ) {
            $prop_category=0;
        }else{
            $prop_category  =   intval($_POST['prop_category']);
        }

        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category=0;
        }else{
            $prop_action_category  =   wp_kses(esc_html($_POST['prop_action_category']),$allowed_html);
        }

        if( !isset($_POST['property_city']) ) {
            $property_city='';
        }else{
            $property_city  =   wp_kses(esc_html($_POST['property_city']),$allowed_html);
        }

        if( !isset($_POST['property_area']) ) {
            $property_area='';
        }else{
            $property_area  =   wp_kses(esc_html($_POST['property_area']),$allowed_html);
        }


        if( !isset($_POST['property_county']) ) {
            $property_county_state='';
        }else{
            $property_county_state  =   wp_kses(esc_html($_POST['property_county']),$allowed_html);
        }

        $wpestate_show_err              =   '';
        $post_id                        =   '';
        $submit_title                   =   '';
        if(isset($_POST['wpestate_title'])){
            $submit_title                   =   wp_kses( $_POST['wpestate_title'],$allowed_html );
        }
        $submit_description                   =   '';
        if(isset($_POST['wpestate_description'])){
            $submit_description             =   wp_kses( $_POST['wpestate_description'],$allowed_html_desc);
        }

        $property_address                   =   '';
        if(isset($_POST['property_address'])){
            $property_address               =   wp_kses( $_POST['property_address'],$allowed_html);
        }

        $property_county                   =   '';
        if(isset($_POST['property_county'])){
            $property_county                =   wp_kses( $_POST['property_county'],$allowed_html);
        }

        $property_zip                   =   '';
        if(isset($_POST['property_zip'])){
            $property_zip                   =   wp_kses( $_POST['property_zip'],$allowed_html);
        }


        // energy effect
        $energy_class                   =   '';
        if(isset($_POST['energy_class'])){
            $energy_class                   =   wp_kses( $_POST['energy_class'],$allowed_html);
        }
        $energy_index                   =   '';
        if(isset($_POST['energy_index'])){
            $energy_index                   =   wp_kses( $_POST['energy_index'],$allowed_html);
        }
        // end

        $country_selected                   =   '';
        if(isset($_POST['property_country'])){
            $country_selected               =   wp_kses( $_POST['property_country'],$allowed_html);
        }

        $prop_stat                   =   '';
        if(isset($_POST['property_status'])){
            $prop_stat                      =   wp_kses( $_POST['property_status'],$allowed_html);
        }


        $property_status                =   '';

        if(is_array($property_status_array)){
            foreach ($property_status_array as $key=>$term) {
                $property_status.='<option value="' . $term->name . '"';
                if ($term->name == $prop_stat) {
                    $property_status.='selected="selected"';
                }
                $property_status.='>' .stripslashes($term->name) . '</option>';
            }
        }


        $property_price                   =   '';
        if(isset($_POST['property_price'])){
            $property_price                 =   wp_kses( esc_html($_POST['property_price']),$allowed_html);
        }

        $property_label                   =   '';
        if(isset($_POST['property_label'])){
            $property_label                 =   wp_kses( esc_html($_POST['property_label']),$allowed_html);
        }

        $property_label_before                   =   '';
        if(isset($_POST['property_label_before'])){
            $property_label_before          =   wp_kses( esc_html($_POST['property_label_before']),$allowed_html);
        }



        $property_second_price                   =   '';
        if(isset($_POST['property_second_price'])){
            $property_second_price          =   wp_kses( esc_html($_POST['property_second_price']),$allowed_html);
        }


        $property_second_price_label                   =   '';
        if(isset($_POST['property_second_price_label'])){
            $property_second_price_label          =   wp_kses( esc_html($_POST['property_second_price_label']),$allowed_html);
        }


        $property_label_before_second_price                   =   '';
        if(isset($_POST['property_label_before_second_price'])){
            $property_label_before_second_price          =   wp_kses( esc_html($_POST['property_label_before_second_price']),$allowed_html);
        }




        $property_year_tax                   =   '';
        if(isset($_POST['property_year_tax'])){
            $property_year_tax          =   wp_kses( esc_html($_POST['property_year_tax']),$allowed_html);
        }


        $property_hoa                   =   '';
        if(isset($_POST['property_hoa'])){
            $property_hoa          =   wp_kses( esc_html($_POST['property_hoa']),$allowed_html);
        }




        $property_size                   =   '';
        if(isset($_POST['property_size'])){
            $property_size                  =   wp_kses( esc_html($_POST['property_size']),$allowed_html);
        }

        $owner_notes                   =   '';
        if(isset($_POST['owner_notes'])){
            $owner_notes                    =   wp_kses( esc_html($_POST['owner_notes']),$allowed_html);
        }

        $property_lot_size                   =   '';
        if(isset($_POST['property_lot_size'])){
            $property_lot_size              =   wp_kses( esc_html($_POST['property_lot_size']),$allowed_html);
        }

        $property_rooms                   =   '';
        if(isset($_POST['property_rooms'])){
            $property_rooms                 =   wp_kses( esc_html($_POST['property_rooms']),$allowed_html);
        }

        $property_bedrooms                   =   '';
        if(isset($_POST['property_bedrooms'])){
            $property_bedrooms              =   wp_kses( esc_html($_POST['property_bedrooms']),$allowed_html);
        }


        $property_bathrooms                   =   '';
        if(isset($_POST['property_bathrooms'])){
            $property_bathrooms             =   wp_kses( esc_html($_POST['property_bathrooms']),$allowed_html);
        }


        $option_video                   =   '';
        $video_values                   =   array('vimeo', 'youtube');

        $video_type                   =   '';
        if(isset($_POST['embed_video_type'])){
            $video_type                     =   wp_kses( esc_html($_POST['embed_video_type']),$allowed_html);
        }

        $google_camera_angle                   =   '';
        if(isset($_POST['google_camera_angle'])){
            $google_camera_angle            =   wp_kses( esc_html($_POST['google_camera_angle']),$allowed_html);
        }

        $property_hide_map_marker   ='';
        if(isset($_POST['property_hide_map_marker'])){
            $property_hide_map_marker            =   wp_kses( esc_html($_POST['property_hide_map_marker']),$allowed_html);
        }
        
        $property_has_subunits                   =   '';
        if(isset($_POST['property_has_subunits'])){
            $property_has_subunits          =   wp_kses( esc_html($_POST['property_has_subunits']),$allowed_html);
        }

        if(isset($_POST['property_subunits_list'])){
            $property_subunits_list         =   $_POST['property_subunits_list'];
        }
        $has_errors                     =   false;
        $errors                         =   array();

  



        foreach ($video_values as $value) {
            $option_video.='<option value="'.esc_html($value).'"';
            if ($value == $video_type) {
                $option_video.='selected="selected"';
            }
            $option_video.='>'.esc_html($value).'</option>';
        }

        $option_slider='';
        $slider_values = array('full top slider', 'small slider');
        $iframe = array( 'iframe' => array(
                         'src' => array (),
                         'width' => array (),
                         'height' => array (),
                         'frameborder' => array(),
                        'style' => array(),
                         'allowFullScreen' => array() // add any other attributes you wish to allow
                          ) );



        $embed_video_id                   =   '';
        if(isset($_POST['embed_video_id'])){
            $embed_video_id                 =   wp_kses( esc_html($_POST['embed_video_id']),$allowed_html);
        }

        $virtual_tour                   =   '';
        if(isset($_POST['embed_virtual_tour'])){
            $search="'";
            $replace='"';
            $virtual_tour                   =   wp_kses ( trim( str_replace($search,$replace,$_POST['embed_virtual_tour'] ) ),$iframe) ;
        }

        $property_latitude                   =   '';
        if(isset($_POST['property_latitude'])){
            $property_latitude              =   floatval( $_POST['property_latitude']);
        }

        $property_longitude                   =   '';
        if(isset($_POST['property_longitude'])){
            $property_longitude             =   floatval( $_POST['property_longitude']);
        }

        $google_view                   =   '';
        if(isset($_POST['property_google_view'])){
            $google_view                    =   wp_kses( esc_html( $_POST['property_google_view']),$allowed_html);
        }

        if($google_view==1){
            $google_view_check=' checked="checked" ';
        }else{
            $google_view_check=' ';
        }

      

        $property_hide_map_marker   ='';
        if(isset($_POST['property_hide_map_marker'])){
            $property_hide_map_marker            =   intval($_POST['property_hide_map_marker']);
        }

        if($property_hide_map_marker==1){
            $property_hide_map_marker_check=' checked="checked" ';
        }else{
            $property_hide_map_marker_check=' ';
        }



        $google_camera_angle                   =   '';
        if(isset($_POST['google_camera_angle'])){
            $google_camera_angle            =   intval( $_POST['google_camera_angle']);
        }

        $prop_category                  =   get_term( $prop_category, 'property_category');
        if(isset($prop_category->term_id)){
            $prop_category_selected         =   $prop_category->term_id;
        }else{
           $prop_category = -1;
        }

        $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');
        if(isset($prop_action_category->term_id)){
            $prop_action_category_selected  =   $prop_action_category->term_id;
        }else{
            $prop_action_category=-1;
        }

        $attchs =   array();
        if(isset($_POST['attachid'])){
            $attchs =   explode(',',$_POST['attachid']);
        }


        // save custom fields

        $i=0;
        if( !empty($custom_fields)){
            while($i< count($custom_fields) ){
               $name    =   $custom_fields[$i][0];
               $type    =   $custom_fields[$i][1];
               $slug    =   str_replace(' ','_',$name);
               $slug    =   wpestate_limit45(sanitize_title( $name ));
               $slug    =   sanitize_key($slug);
               if(isset($_POST[$slug])){
                $custom_fields_array[$slug]= wp_kses( esc_html($_POST[$slug]),$allowed_html);
               }
               $i++;
            }
        }

        if($submit_title==''){
            $has_errors=true;
            $errors[]=esc_html__('Please submit a title for your property','wpresidence');
        }
        $check_mandatory_field=wpestate_check_mandatory_fields($prop_category,$prop_action_category);

        if(!empty($check_mandatory_field)){
            $has_errors=true;
            $errors=array_merge($errors,$check_mandatory_field);
        }

        if($has_errors){
            foreach($errors as $key=>$value){
                $wpestate_show_err.=$value.'</br>';
            }
        }else{
            $paid_submission_status = esc_html ( wpresidence_get_option('wp_estate_paid_submission','') );
            $new_status             = 'pending';

            $admin_submission_status= esc_html ( wpresidence_get_option('wp_estate_admin_submission','') );
            if($admin_submission_status=='no' && $paid_submission_status!='per listing'){
               $new_status='publish';
            }


            $post = array(
                'post_title'	=> $submit_title,
                'post_content'	=> $submit_description,
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property' ,
                'post_author'   => $current_user->ID
            );
            $post_id =  wp_insert_post($post );

            if( $paid_submission_status == 'membership'){ // update pack status
                wpestate_update_listing_no($parent_userID);
            }

        }


        if($post_id) {
            // uploaded images
            $order  =   0;

            $last_id='';
            foreach($attchs as $att_id){
                if( !is_numeric($att_id) ){

                }else{
                    if($last_id==''){
                        $last_id=  $att_id;
                    }
                    $order++;
                    wp_update_post( array(
                                'ID' => $att_id,
                                'post_parent' => $post_id,
                                'menu_order'=>$order
                            ));


                }
            }

            if( isset($_POST['attachthumb']) &&  is_numeric($_POST['attachthumb']) && $_POST['attachthumb']!=''  ){
                set_post_thumbnail( $post_id, wp_kses(esc_html($_POST['attachthumb']),$allowed_html ));
            }else{
                set_post_thumbnail( $post_id, $last_id );
            }
            //end uploaded images


            if( isset($prop_category->name) ){
                wp_set_object_terms($post_id,$prop_category->name,'property_category');
            }
            if ( isset ($prop_action_category->name) ){
                wp_set_object_terms($post_id,$prop_action_category->name,'property_action_category');
            }
            if( isset($property_city) && $property_city!='none' ){
                if($property_city == -1 ){
                    $property_city='';
                }

                wp_set_object_terms($post_id,$property_city,'property_city');
            }

            if( isset($property_area) && $property_area!='none' ){
                wp_set_object_terms($post_id,$property_area,'property_area');
            }

            if( isset($property_county_state) && $property_county_state!='none' ){
                if($property_county_state == -1){
                    $property_county_state='';
                }
                wp_set_object_terms($post_id,$property_county_state,'property_county_state');
            }

            if($property_area!=''){
                $terms= get_term_by('name', $property_area, 'property_area');
                if($terms!=''){
                    $t_id=$terms->term_id;
                    $term_meta=array('cityparent'=>$property_city);
                    add_option( "taxonomy_$t_id", $term_meta );
                }

            }

            if($property_city!=''){
                $terms= get_term_by('name', $property_city, 'property_city');
                if($terms!=''){
                    $t_id=$terms->term_id;
                    $term_meta=array('stateparent'=>$property_county_state);
                    add_option( "taxonomy_$t_id", $term_meta );
                }

            }


            update_post_meta($post_id, 'sidebar_agent_option', 'global');
            update_post_meta($post_id, 'local_pgpr_slider_type', 'global');
            update_post_meta($post_id, 'local_pgpr_content_type', 'global');
            update_post_meta($post_id, 'prop_featured', 0);
            update_post_meta($post_id, 'property_address', $property_address);
            update_post_meta($post_id, 'property_county', $property_county);
            update_post_meta($post_id, 'property_zip', $property_zip);

            // energy effect
            update_post_meta($post_id, 'energy_class', $energy_class);
            update_post_meta($post_id, 'energy_index', $energy_index);
            // ee end

            update_post_meta($post_id, 'property_country', $country_selected);
            update_post_meta($post_id, 'property_size', $property_size);
            update_post_meta($post_id, 'owner_notes', $owner_notes);
            update_post_meta($post_id, 'property_lot_size', $property_lot_size);
            update_post_meta($post_id, 'property_rooms', $property_rooms);
            update_post_meta($post_id, 'property_has_subunits', $property_has_subunits);
            update_post_meta($post_id, 'property_subunits_list', $property_subunits_list);

            if(is_array($property_subunits_list)){
            foreach ($property_subunits_list as $key) {
                update_post_meta(intval($key), 'property_subunits_master',$post_id );
            }
            }else{
                update_post_meta(intval($property_subunits_list), 'property_subunits_master',$post_id );
            }



            update_post_meta($post_id, 'property_bedrooms', $property_bedrooms);
            update_post_meta($post_id, 'property_bathrooms', $property_bathrooms);
            update_post_meta($post_id, 'property_price', $property_price);
            update_post_meta($post_id, 'property_label', $property_label);
            update_post_meta($post_id, 'property_label_before', $property_label_before);

            update_post_meta($post_id, 'property_second_price', $property_second_price);
            update_post_meta($post_id, 'property_second_price_label', $property_second_price_label);
            update_post_meta($post_id, 'property_label_before_second_price', $property_label_before_second_price);

            update_post_meta($post_id, 'property_hoa', $property_hoa);
            update_post_meta($post_id, 'property_year_tax', $property_year_tax);
            update_post_meta($post_id, 'embed_video_type', $video_type);
            update_post_meta($post_id, 'embed_video_id',  $embed_video_id );
            update_post_meta($post_id, 'embed_virtual_tour', $virtual_tour);
            update_post_meta($post_id, 'property_latitude', $property_latitude);
            update_post_meta($post_id, 'property_longitude', $property_longitude);
            update_post_meta($post_id, 'property_google_view',  $google_view);
            update_post_meta($post_id, 'property_hide_map_marker',  $property_hide_map_marker);
            update_post_meta($post_id, 'google_camera_angle', $google_camera_angle);
            update_post_meta($post_id, 'pay_status', 'not paid');
            update_post_meta($post_id, 'page_custom_zoom', 16);


            $user_id_agent            =   get_the_author_meta( 'user_agent_id' , $current_user->ID  );
            update_post_meta($post_id, 'property_agent', $user_id_agent);


            // save custom fields
            $custom_fields = wpresidence_get_option( 'wp_estate_custom_fields', '');

            $i=0;
            if( !empty($custom_fields)){
                while($i< count($custom_fields) ){
                    $name   =   $custom_fields[$i][0];
                    $type   =   $custom_fields[$i][2];
                    $slug   =   str_replace(' ','_',$name);
                    $slug   =   wpestate_limit45(sanitize_title( $name ));
                    $slug   =   sanitize_key($slug);
                    if( isset($_POST[$slug]) ){
                        if($type=='numeric'){
                            $value_custom    =   intval(wp_kses( $_POST[$slug],$allowed_html ) );

                            if($value_custom==0){
                                $value_custom='';
                            }

                           update_post_meta($post_id, $slug, $value_custom);
                        }else{
                            $value_custom    =   esc_html(wp_kses( $_POST[$slug],$allowed_html ) );
                            update_post_meta($post_id, $slug, $value_custom);
                        }
                        $custom_fields_array[$slug]= wp_kses( esc_html($_POST[$slug]),$allowed_html);
                    }

                    $i++;
                }
            }



            if(is_array($property_features)){
                foreach($property_features as $key => $term){
                    $feature_name   =   $term->slug;
                    //$feature_name   =   str_replace('%','', $feature_name);
                    if(isset($_POST[$feature_name]) && $_POST[$feature_name]==1){
                        wp_set_object_terms($post_id,trim($term->name),'property_features',true);
                        $moving_array[]=$feature_name;
                    }
                }
            }


          


            wp_set_object_terms($post_id,$prop_stat,'property_status',true);


            wpestate_update_hiddent_address_single($post_id);

            // get user dashboard link
            $redirect = wpestate_get_template_link('page-templates/user_dashboard.php');

            $arguments=array(
                'new_listing_url'   =>  esc_url ( get_permalink($post_id) ),
                'new_listing_title' => $submit_title
            );


            wpestate_select_email_type(get_option('admin_email'),'new_listing_submission',$arguments);

            wp_reset_query();
            wp_redirect( $redirect);
            exit;
        }

        }else{
            echo 'sorry , you cannot submit';
                //end if user can submit
        }
} // end post

get_header();

// HTML Form Code
?>

<div class="row wpresidence_page_content_wrapper">
    <div class="p-0 p04mobile col-md-12 page-template-front_property_submit wpestate_inside_container">
        <form action="" method="POST" id="front_submit_form">
            <input type="hidden" name="action" value="front_submit" />

            <div class="navigation_container flex-column flex-lg-row">
                <!-- Navigation buttons -->
                <a href="#" class="inner_navigation navigation_1 active" data-id="1"><?php esc_html_e('1. Description', 'wpresidence'); ?></a>
                <a href="#" class="inner_navigation navigation_2" data-id="2"><?php esc_html_e('2. Media', 'wpresidence'); ?></a>
                <a href="#" class="inner_navigation navigation_3" data-id="3"><?php esc_html_e('3. Location', 'wpresidence'); ?></a>
                <a href="#" class="inner_navigation navigation_4" data-id="4"><?php esc_html_e('4. Details', 'wpresidence'); ?></a>
                <a href="#" class="inner_navigation navigation_5" data-id="5"><?php esc_html_e('5. Amenities', 'wpresidence'); ?></a>
            </div>

            <div class="submit_property_front_wrapper">
                <?php
                // Display mandatory fields
                $mandatory_fields = wpresidence_get_option('wp_estate_mandatory_page_fields', '');
                if (is_array($mandatory_fields)) {
                    $mandatory_fields = array_map("wpestate_strip_array", $mandatory_fields);
                }
                if (is_array($mandatory_fields) && !empty($mandatory_fields)) {
                    $all_mandatory_fields = wpestate_return_all_fields(1);
                    echo '<div class="submit_mandatory col-md-12">';
                    esc_html_e('These fields are mandatory: Title', 'wpresidence');
                    foreach ($mandatory_fields as $key => $value) {
                        echo ', ' . esc_html($all_mandatory_fields[$value]);
                    }
                    echo '</div>';
                }

                // Display paid submission message
                if (esc_html(wpresidence_get_option('wp_estate_paid_submission', '')) == 'yes') {
                    echo '<br>' . esc_html__('This is a paid submission. The listing will be live after payment is received.', 'wpresidence');
                }

                // Display error messages
                if ($wpestate_show_err) {
                    echo '<div class="alert alert-danger">' . wp_kses_post($wpestate_show_err) . '</div>';
                }
                ?>

                <!-- Property submission steps -->
                <div class="single_step step_1">
                    <?php
                    include(locate_template('templates/submit_templates/property_description.php'));
                    include(locate_template('templates/submit_templates/property_categories.php'));
                    include(locate_template('templates/submit_templates/property_status.php'));
                    ?>
                </div>

                <div class="single_step step_2">
                    <?php
                    include(locate_template('templates/submit_templates/property_images.php'));
                    include(locate_template('templates/submit_templates/property_video.php'));
                    include(locate_template('templates/submit_templates/video_tour.php'));
                    ?>
                </div>

                <div class="single_step step_3">
                    <?php
                    include(locate_template('templates/submit_templates/property_location.php'));
                    ?>
                </div>

                <div class="single_step step_4">
                    <?php
                    include(locate_template('templates/submit_templates/property_details.php'));
                    include(locate_template('templates/submit_templates/property_energy_effective.php'));
                    ?>
                </div>

                <div class="single_step step_5">
                    <?php
                    include(locate_template('templates/submit_templates/property_amenities.php'));
                    ?>
                </div>

                <div class="navigation_container_footer">
                    <input type="hidden" value="1" id="current_step" />
                    <input type="hidden" value="register" id="submit_type" />
                    <button type="button" class="wpresidence_button" id="front_submit_prev_step"><?php esc_html_e('Prev Step', 'wpresidence'); ?></button>
                    <button type="button" class="wpresidence_button" id="front_submit_next_step"><?php esc_html_e('Next Step', 'wpresidence'); ?></button>
                    <button type="submit" class="wpresidence_button" id="submit_property"><?php esc_html_e('Submit Property', 'wpresidence'); ?>
                        <span class="loadersmall hidden"></span></button>
                </div>

            </div>
            <?php wp_nonce_field('submit_property_front_action', 'submit_property_front_nonce', false); ?>
        </form>
    </div>
</div>

<?php
include get_theme_file_path('sidebar.php');
get_footer();
?>