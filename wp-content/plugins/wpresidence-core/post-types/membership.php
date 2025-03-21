<?php
// register the custom post type
add_action( 'init', 'wpestate_create_membership_type' );

if( !function_exists('wpestate_create_membership_type') ):
function wpestate_create_membership_type() {
register_post_type( 'membership_package',
		array(
			'labels' => array(
				'name'          => esc_html__( 'Membership Packages','wpresidence-core'),
				'singular_name' => esc_html__( 'Membership Packages','wpresidence-core'),
				'add_new'       => esc_html__('Add New Membership Package','wpresidence-core'),
                'add_new_item'          =>  esc_html__('Add Membership Packages','wpresidence-core'),
                'edit'                  =>  esc_html__('Edit Membership Packages' ,'wpresidence-core'),
                'edit_item'             =>  esc_html__('Edit Membership Package','wpresidence-core'),
                'new_item'              =>  esc_html__('New Membership Packages','wpresidence-core'),
                'view'                  =>  esc_html__('View Membership Packages','wpresidence-core'),
                'view_item'             =>  esc_html__('View Membership Packages','wpresidence-core'),
                'search_items'          =>  esc_html__('Search Membership Packages','wpresidence-core'),
                'not_found'             =>  esc_html__('No Membership Packages found','wpresidence-core'),
                'not_found_in_trash'    =>  esc_html__('No Membership Packages found','wpresidence-core'),
                'parent'                =>  esc_html__('Parent Membership Package','wpresidence-core')
			),
		'public' => false,
                'show_ui'=>true,
                'show_in_nav_menus'=>true,
                'show_in_menu'=>true,
                'show_in_admin_bar'=>true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'package'),
		'supports' => array('title','thumbnail'),
		'can_export' => true,
		'register_meta_box_cb' => 'wpestate_add_pack_metaboxes',
                'menu_icon'=> WPESTATE_PLUGIN_DIR_URL.'/img/membership.png'
		)
	);
}
endif; // end   wpestate_create_membership_type




/////////////////////////////////////////////////////////////////////////////////////
// custom options for property
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_pack_metaboxes') ):
function wpestate_add_pack_metaboxes() {
  add_meta_box(  'estate_membership-sectionid',  esc_html__( 'Package Details', 'wpresidence-core' ),'membership_package','membership_package' ,'normal','default'
    );
}
endif; // end   wpestate_add_pack_metaboxes


if( !function_exists('membership_package') ):
function membership_package( $post ) {
	    wp_nonce_field( plugin_basename( __FILE__ ), 'estate_pack_noncename' );
	    global $post;
                $unlimited_days     =   esc_html(get_post_meta($post->ID, 'mem_days_unl', true));
                $unlimited_lists    =   esc_html(get_post_meta($post->ID, 'mem_list_unl', true));
                $billing_periods    =   array('Day','Week','Month','Year');

                $billng_saved       =   esc_html(get_post_meta($post->ID, 'biling_period', true));
                $billing_select     =   '<select name="biling_period" id="billing_period">';
                foreach($billing_periods as $period){
                    $billing_select.='<option value="'.$period.'" ';
                    if($billng_saved==$period){
                         $billing_select.=' selected="selected" ';
                    }
                    $billing_select.='>'.$period.'</option>';
                }
                $billing_select.='</select>';

                $check_unlimited_lists='';
                if($unlimited_lists==1){
                    $check_unlimited_lists=' checked="checked"  ';
                }


                $visible_array=array('yes','no');
                $visible_saved=get_post_meta($post->ID, 'pack_visible', true);
                $visible_select='<select id="pack_visible" name="pack_visible">';

                foreach($visible_array as $option){
                    $visible_select.='<option value="'.$option.'" ';
                    if($visible_saved==$option){
                        $visible_select.=' selected="selected" ';
                    }
                    $visible_select.='>'.$option.'</option>';
                }
                $visible_select.='</select>';


                $visible_pack_array     =   wpestate_user_types_list_array();
                $visible_pack_saved     =   get_post_meta($post->ID, 'pack_visible_user_role', true);
                $visible_pack_select    =   '<select id="pack_visible_user_role" name="pack_visible_user_role[]" multiple="multiple">';

                foreach($visible_pack_array as $option){
                    $visible_pack_select.='<option value="'.$option.'" ';

                    if( is_array($visible_pack_saved) && in_array($option,$visible_pack_saved) ){
                        $visible_pack_select.=' selected="selected" ';
                    }

                    $visible_pack_select.='>'.$option.'</option>';
                }
                $visible_pack_select.='</select>';

                print'
                <p class="meta-options third-meta-options">
                    <label for="biling_period">'.esc_html__('Billing Period:','wpresidence-core').'</label><br />
                    '.$billing_select.'
                </p>

                <p class="meta-options">
                    <label for="billing_freq">'.esc_html__('Billing Frequency','wpresidence-core').' </label><br />
                    <input type="text" id="billing_freq" size="58" name="billing_freq" value="'.  intval(get_post_meta($post->ID, 'billing_freq', true)).'">
                </p>

                <p class="meta-options">
                    <label for="pack_listings">'.esc_html__('How many listings are included?','wpresidence-core').'</label>
                    <p class="input-group">
                        <input type="text" id="pack_listings" size="58" name="pack_listings" value="'.  esc_html(get_post_meta($post->ID, 'pack_listings', true)).'">
                        <input type="hidden" name="mem_list_unl" value=""/>
                        <input type="checkbox"  id="mem_list_unl" name="mem_list_unl" value="1" '.$check_unlimited_lists.'  />
                        <label for="mem_list_unl" class="regular-label">'.esc_html__('Unlimited listings','wpresidence-core').'</label>
                    </p>    
                </p>

                <p class="meta-options">
                    <label for="pack_featured_listings">'.esc_html__('How many Featured listings are included?','wpresidence-core').'</label><br />
                    <input type="text" id="pack_featured_listings" size="58" name="pack_featured_listings" value="'.  esc_html(get_post_meta($post->ID, 'pack_featured_listings', true)).'">
                </p>

                 <p class="meta-options">
                    <label for="billing_freq">'.esc_html__('How many images are included per listing?','wpresidence-core').' </label><br />
                    <input type="text" id="pack_image_included" size="58" name="pack_image_included" value="'.  intval(get_post_meta($post->ID, 'pack_image_included', true)).'">
                </p>

                <p class="meta-options">
                    <label for="pack_price">'.esc_html__('Package Price in ','wpresidence-core'). ' ' .wpresidence_get_option('wp_estate_submission_curency').'</label><br />
                    <input type="text" id="pack_price" size="58" name="pack_price" value="'.  esc_html(get_post_meta($post->ID, 'pack_price', true)).'">
		</p>

                <p class="meta-options">
                    <label for="pack_visible_user_role">'.esc_html__('Display package for? *Hold CTRL for multiple selection.','wpresidence-core').'</label><br />
                    '.$visible_pack_select.'
		</p>

                <p class="meta-options">
                    <label for="pack_visible">'.esc_html__('Is it visible? ','wpresidence-core').'</label><br />
                    '.$visible_select.'
		</p>

                <p class="meta-options">
                    <label for="pack_stripe_id">Package Stripe ID (enter the ID from Stripe Account) </label><br>
                    <input type="text" id="pack_stripe_id" size="58" name="pack_stripe_id" value="'.esc_html(get_post_meta($post->ID, 'pack_stripe_id', true)).'">
                </p>
         ';
}
endif; // end   membership_package

////////////////////////////////////////////////////////////////////////////////
/// Get a list of all visible packages
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_all_packs') ):
function wpestate_get_all_packs(){
    $args = array(
                'post_type'         => 'membership_package',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                                            array(
                                                'key' => 'pack_visible',
                                                'value' => 'yes',
                                                'compare' => '='
                                            )

                 )

         );
        $pack_selection = new WP_Query($args);

        while ($pack_selection->have_posts()): $pack_selection->the_post();
            $return_string.='<option value="'.$post->ID.'">'.get_the_title().'</option>';
        endwhile;
        wp_reset_query();
        return $return_string;
}
endif; // end   wpestate_get_all_packs


////////////////////////////////////////////////////////////////////////////////
/// Get a package details from user top profile
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_pack_data_for_user_top') ):
function wpestate_get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation){
    print '<div class="pack_description">
                <div class="pack-unit">';

            $remaining_lists=wpestate_get_remain_listing_user($userID,$user_pack);
            if($remaining_lists==-1){
                $remaining_lists=esc_html__('unlimited','wpresidence-core');
            }



            if ($user_pack!=''){
                $title              = get_the_title($user_pack);
                $pack_time          = get_post_meta($user_pack, 'pack_time', true);
                $pack_list          = get_post_meta($user_pack, 'pack_listings', true);
                $pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
                $pack_price         = get_post_meta($user_pack, 'pack_price', true);
                $unlimited_lists    = get_post_meta($user_pack, 'mem_list_unl', true);
                $date               = strtotime ( get_user_meta($userID, 'package_activation',true) );
                $biling_period      = get_post_meta($user_pack, 'biling_period', true);
                $billing_freq       = intval(get_post_meta($user_pack, 'billing_freq', true));


                $seconds=0;
                switch ($biling_period){
                   case 'Day':
                       $seconds=60*60*24;
                       break;
                   case 'Week':
                       $seconds=60*60*24*7;
                       break;
                   case 'Month':
                       $seconds=60*60*24*30;
                       break;
                   case 'Year':
                       $seconds=60*60*24*365;
                       break;
                }

                $time_frame      =   $seconds*$billing_freq;
                $expired_date    =   $date+$time_frame;
                $date_format     =   get_option('date_format');
                $expired_date    =   date($date_format,$expired_date);
                $pack_image_included  =   get_post_meta($user_pack, 'pack_image_included', true);
                if (intval($pack_image_included)==0){
                    $pack_image_included=esc_html__('Unlimited', 'wpresidence-core');
                }



                print '<div class="pack_description_unit_head"><h4>'.esc_html__('Your Current Package :','wpresidence-core').'</h4>
                       <span class="pack-name">'.$title.' </span></div> ';

                if($unlimited_lists==1){
                    print '<div class="pack_description_unit pack_description_details">';
                    print esc_html__('  unlimited','wpresidence-core');
                    print '<p class="package_label">'.esc_html__('Listings Included','wpresidence-core').'</p></div>';

                    print '<div class="pack_description_unit pack_description_details">';
                    print esc_html__('  unlimited','wpresidence-core');
                    print '<p class="package_label">'.esc_html__('Listings Remaining','wpresidence-core').'</p></div>';
                }else{
                    print '<div class="pack_description_unit pack_description_details">';
                    print ' '.$pack_list;
                    print '<p class="package_label">'.esc_html__('Listings Included','wpresidence-core').'</p></div>';

                    print '<div class="pack_description_unit pack_description_details">';
                    print '<span id="normal_list_no"> '.$remaining_lists.'</span>';
                    print '<p class="package_label">'.esc_html__('Listings Remaining','wpresidence-core').'</p></div>';
                }

                print '<div class="pack_description_unit pack_description_details">';
                print '<span id="normal_list_no"> '.$pack_featured.'</span>';
                print '<p class="package_label">'.esc_html__('Featured Included','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print '<span id="featured_list_no"> '.wpestate_get_remain_featured_listing_user($userID).'</span>';
                print '<p class="package_label">'.esc_html__('Featured Remaining','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print ' '.$pack_image_included;
                print '<p class="package_label">'.esc_html__('Images / per listing','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print ' '.$expired_date;
                print '<p class="package_label">'.esc_html__('Ends On','wpresidence-core').'</p></div>';

            }else{

                $free_mem_list      =   esc_html( wpresidence_get_option('wp_estate_free_mem_list','') );
                $free_feat_list     =   esc_html( wpresidence_get_option('wp_estate_free_feat_list','') );
                $free_mem_list_unl  =   wpresidence_get_option('wp_estate_free_mem_list_unl', '' );
                $free_pack_image_included  =  esc_html( wpresidence_get_option('wp_estate_free_pack_image_included ','') );
                print '<div class="pack_description_unit_head"><h4>'.esc_html__('Your Current Package:','wpresidence-core').'</h4>
                      <span class="pack-name">'.esc_html__('Free Membership','wpresidence-core').'</span></div>';

                print '<div class="pack_description_unit pack_description_details">';
                if($free_mem_list_unl==1){
                    print esc_html__('  unlimited','wpresidence-core');
                }else{
                    print ' '.$free_mem_list;
                }
                print '<p class="package_label">'.esc_html__('Listings Included','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print '<span id="normal_list_no"> '.$remaining_lists.'</span>';
                print '<p class="package_label">'.esc_html__('Listings Remaining','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print '<span id="normal_list_no"> '.$free_feat_list.'</span>';
                print '<p class="package_label">'.esc_html__('Featured Included','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print '<span id="featured_list_no"> '.wpestate_get_remain_featured_listing_user($userID).'</span>';
                print '<p class="package_label">'.esc_html__('Featured Remaining','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print '<span id="free_pack_image_included"> '.$free_pack_image_included.'</span>';
                print '<p class="package_label">'.esc_html__('Images / listing','wpresidence-core').'</p></div>';

                print '<div class="pack_description_unit pack_description_details">';
                print '&nbsp;<p class="package_label">'.esc_html__('Ends On: -','wpresidence-core').'</p></div>';

            }
            print '</div></div>';

}
endif; // end   wpestate_get_pack_data_for_user_top




////////////////////////////////////////////////////////////////////////////////
/// Get a package details from user
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_pack_data_for_user') ):
function wpestate_get_pack_data_for_user($userID,$user_pack,$user_registered,$user_package_activation){

            if ($user_pack!=''){
                $title              = get_the_title($user_pack);
                $pack_time          = get_post_meta($user_pack, 'pack_time', true);
                $pack_list          = get_post_meta($user_pack, 'pack_listings', true);
                $pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
                $pack_price         = get_post_meta($user_pack, 'pack_price', true);

                $unlimited_lists    = get_post_meta($user_pack, 'mem_list_unl', true);
                print'<div class="user_dashboard_box">';
                print '<strong>'.esc_html__('Your Current Package: ','wpresidence-core').'</strong></br><strong>'.$title.'</strong></br> ';
                print '<p class="full_form-nob">';
                if($unlimited_lists==1){
                    print esc_html__('  Unlimited listings','wpresidence-core');
                }else{
                    print $pack_list.esc_html__(' Listings','wpresidence-core');
                    print ' - '.wpestate_get_remain_listing_user($userID,$user_pack).esc_html__(' remaining ','wpresidence-core').'</p>';
                }

                print ' <p class="full_form-nob"> <span id="normal_list_no">'.$pack_featured.esc_html__(' Featured listings','wpresidence-core').'</span>';
                print ' - <span id="featured_list_no">'.wpestate_get_remain_featured_listing_user($userID).'</span>'.esc_html__(' remaining','wpresidence-core').' </p>';
                print'</div>';

            }else{

                $free_mem_list      =   esc_html( wpresidence_get_option('wp_estate_free_mem_list','') );
                $free_feat_list     =   esc_html( wpresidence_get_option('wp_estate_free_feat_list','') );
                $free_mem_list_unl  =   wpresidence_get_option('wp_estate_free_mem_list_unl', '' );
                print'<div class="user_dashboard_box">';
                print '<strong>'.esc_html__('Your Current Package: ','wpresidence-core').'</strong></br><strong>'.esc_html__('Free Membership','wpresidence-core').'</strong>';
                print '<p class="full_form-nob">';
                if($free_mem_list_unl==1){
                     print esc_html__('Unlimited listings','wpresidence-core');
                }else{
                     print $free_mem_list.esc_html__(' Listings','wpresidence-core');
                     print ' - <span id="normal_list_no">'.wpestate_get_remain_listing_user($userID,$user_pack).'</span>'.esc_html__(' remaining','wpresidence-core').'</p>';

                }
                print '<p class="full_form-nob">';
                print $free_feat_list.esc_html__(' Featured listings','wpresidence-core');
                print ' - <span id="featured_list_no">'.wpestate_get_remain_featured_listing_user($userID).'</span>'.esc_html__('  remaining','wpresidence-core').' </p>';
                print'</div>';

            }

}
endif; // end   wpestate_get_pack_data_for_user




if( !function_exists('wpestate_get_remain_days_user') ):
function wpestate_get_remain_days_user($userID,$user_pack,$user_registered,$user_package_activation){

    if ($user_pack!=''){
        $pack_time  = get_post_meta($user_pack, 'pack_time', true);
        $now        = time();

        $user_date  = strtotime($user_package_activation);
        $datediff   = $now - $user_date;
        if( floor($datediff/(60*60*24)) > $pack_time){
            return 0;
        }else{
            return floor($pack_time-$datediff/(60*60*24));
        }


    }else{
        $free_mem_days      = esc_html( wpresidence_get_option('wp_estate_free_mem_days','') );
        $free_mem_days_unl  = wpresidence_get_option('wp_estate_free_mem_days_unl', '');
        if($free_mem_days_unl==1){
            return;
        }else{
             $now = time();
             $user_date = strtotime($user_registered);
             $datediff = $now - $user_date;
             if(  floor($datediff/(60*60*24)) >$free_mem_days){
                 return 0;
             }else{
                return floor($free_mem_days-$datediff/(60*60*24));
             }
        }
    }
}
endif; // end   wpestate_get_remain_days_user





if( !function_exists('wpestate_get_remain_listing_user') ):
function wpestate_get_remain_listing_user($userID,$user_pack){
    if ( $user_pack !='' ){
        $current_listings   = wpestate_get_current_user_listings($userID);
        $pack_listings      = get_post_meta($user_pack, 'pack_listings', true);

        return $current_listings;
    }else{
        $free_mem_list      = esc_html( wpresidence_get_option('wp_estate_free_mem_list','') );
        $free_mem_list_unl  = wpresidence_get_option('wp_estate_free_mem_list_unl', '' );
        if($free_mem_list_unl==1){
              return -1;
        }else{
            $current_listings=wpestate_get_current_user_listings($userID);
            return $current_listings;
        }
      }
}
endif; // end   wpestate_get_remain_listing_user



///////////////////////////////////////////////////////////////////////////////////////////
// return no of featuerd listings available for current pack
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_remain_featured_listing_user') ):
function wpestate_get_remain_featured_listing_user($userID){
    $count  =   get_the_author_meta( 'package_featured_listings' , $userID );
    return $count;
}
endif; // end   wpestate_get_remain_featured_listing_user




///////////////////////////////////////////////////////////////////////////////////////////
// return no of listings available for current pack
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_current_user_listings') ):
    function wpestate_get_current_user_listings($userID){
        $count  =   get_the_author_meta( 'package_listings' , $userID );
        return $count;
    }
endif;
///////////////////////////////////////////////////////////////////////////////////////////
// update listing no
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_listing_no') ):
function wpestate_update_listing_no($userID){
    $current  =   get_the_author_meta( 'package_listings' , $userID );
    if($current==''){
        //do nothing
    }else if($current==-1){ // if unlimited
        //do noting
    }else if($current-1>=0){
        update_user_meta( $userID, 'package_listings', $current-1) ;
    }else if( $current==0 ){
         update_user_meta( $userID, 'package_listings', 0) ;
    }
}
endif; // end   wpestate_update_listing_no



///////////////////////////////////////////////////////////////////////////////////////////
// update featured listing no
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_featured_listing_no') ):
function wpestate_update_featured_listing_no($userID){
    $current  =   get_the_author_meta( 'package_featured_listings' , $userID );

    if($current-1>=0){
        update_user_meta( $userID, 'package_featured_listings', $current-1) ;
    }else{
          update_user_meta( $userID, 'package_featured_listings', 0) ;
    }
}
endif; // end   wpestate_update_featured_listing_no



///////////////////////////////////////////////////////////////////////////////////////////
// update old users that don;t have membership details
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_old_users') ):

function wpestate_update_old_users($userID){
    $paid_submission_status    = esc_html ( wpresidence_get_option('wp_estate_paid_submission','') );
    if($paid_submission_status == 'membership' ){

        $curent_list   =   get_user_meta( $userID, 'package_listings', true) ;
        $cur_feat_list =   get_user_meta( $userID, 'package_featured_listings', true) ;

            if($curent_list=='' || $cur_feat_list=='' ){
                $package_listings           = esc_html( wpresidence_get_option('wp_estate_free_mem_list','') );
                $featured_package_listings  = esc_html( wpresidence_get_option('wp_estate_free_feat_list','') );
                    if($package_listings==''){
                        $package_listings=0;
                    }
                    if($featured_package_listings==''){
                        $featured_package_listings=0;
                    }

                update_user_meta( $userID, 'package_listings', $package_listings) ;
                update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;

                $time = time();
                $date = date('Y-m-d H:i:s',$time);
                update_user_meta( $userID, 'package_activation', $date);
            }

    }// end if memebeship

}
endif; // end   wpestate_update_old_users




///////////////////////////////////////////////////////////////////////////////////////////
// update user profile on register
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_profile') ):

function wpestate_update_profile($userID){
    if(1==1){ // if membership is on

        if( wpresidence_get_option('wp_estate_free_mem_list_unl', '' ) ==1 ){
            $package_listings =-1;
            $featured_package_listings  = esc_html( wpresidence_get_option('wp_estate_free_feat_list','') );
        }else{
            $package_listings           = esc_html( wpresidence_get_option('wp_estate_free_mem_list','') );
            $featured_package_listings  = esc_html( wpresidence_get_option('wp_estate_free_feat_list','') );

            if($package_listings==''){
                $package_listings=0;
            }
            if($featured_package_listings==''){
                $featured_package_listings=0;
            }
        }
        $cur_images    =   esc_html( wpresidence_get_option('free_pack_image_included','') );

        update_user_meta( $userID, 'package_listings', $package_listings) ;
        update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;
        update_user_meta( $userID, 'pack_image_included', $cur_images) ;

        $time = time();
        $date = date('Y-m-d H:i:s',$time);
        update_user_meta( $userID, 'package_activation', $date);
        //package_id no id since the pack is free

    }

}
endif; // end   wpestate_update_profile





///////////////////////////////////////////////////////////////////////////////////////////
// update user profile on register
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_display_packages') ):
function wpestate_display_packages(){
    global $post;
    $args = array(
                    'post_type'     => 'membership_package',
                    'posts_per_page'=> -1,
                    'meta_query'    => array(
                                            array(
                                                'key' => 'pack_visible',
                                                'value' => 'yes',
                                                'compare' => '=',
                                            )
                                        )
    );
    $pack_selection = new WP_Query($args);

    $return='<select name="pack_select" id="pack_select" class="select-submit2"><option value="">'.esc_html__('Select package','wpresidence-core').'</option>';
    while($pack_selection->have_posts() ){

        $pack_selection->the_post();
        $title=get_the_title();
        $return.='<option value="'.$post->ID.'"  data-price="'.get_post_meta(get_the_id(),'pack_price',true).'" data-pick="'.sanitize_title($title).'" >'.$title.'</option>';
    }
    $return.='</select>';

    print $return;

}
endif; // end   wpestate_display_packages


/////////////////////////////////////////////////////////////////////////////////////
/// downgrade to pack
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_downgrade_to_pack') ):
function wpestate_downgrade_to_pack( $user_id, $pack_id ){

    $future_listings                =   get_post_meta($pack_id, 'pack_listings', true);
    $future_featured_listings       =   get_post_meta($pack_id, 'pack_featured_listings', true);
    $future_images                  =   get_post_meta($pack_id, 'pack_image_included', true);
    update_user_meta( $user_id, 'package_listings', $future_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $future_featured_listings);
    update_user_meta( $user_id, 'pack_image_included', $future_featured_listings);

    $agent_list     =  (array) get_user_meta($user_id,'current_agent_list',true);
    $agent_list[]   =   $user_id;

    $args = array(
        'post_type'     => 'estate_property',
        'author__in'    =>  $agent_list,
        'post_status'   => 'any'
    );

    $query = new WP_Query( $args );
    global $post;
    while( $query->have_posts()){
            $query->the_post();

            $prop = array(
                    'ID'            => $post->ID,
                    'post_type'     => 'estate_property',
                    'post_status'   => 'expired',
                    'post_per_page' => -1,
            );

            wp_update_post($prop );
            update_post_meta($post->ID, 'prop_featured', 0);
      }
    wp_reset_query();

    $user = get_user_by('id',$user_id);
    $user_email=$user->user_email;

    $arguments=array();

    wpestate_select_email_type($user_email,'account_downgraded',$arguments);


}
endif; // end   wpestate_downgrade_to_pack





if( !function_exists('wpestate_downgrade_warning') ):
function wpestate_downgrade_warning($user_id){

    $user       =   get_user_by('id',$user_id);
    $user_email =   $user->user_email;

    $arguments=array();
    wpestate_select_email_type($user_email,'downgrade_warning',$arguments);


}
endif;



/////////////////////////////////////////////////////////////////////////////////////
/// downgrade to free
/////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_downgrade_to_free') ):
 function wpestate_downgrade_to_free($user_id){
    global $post;
    
    
    if( wpresidence_get_option( 'wp_estate_downgraded_to_free_values')== 'free' ){

        $free_pack_listings        = esc_html( wpresidence_get_option('wp_estate_free_mem_list','') );
        $free_pack_feat_listings   = esc_html( wpresidence_get_option('wp_estate_free_feat_list','') );
    }else{        
        $free_pack_listings        = 0;
        $free_pack_feat_listings   = 0;
    }
    $free_pack_images          = esc_html( wpresidence_get_option('free_pack_image_included','') );
    
    
    

    update_user_meta( $user_id, 'package_id', '') ;
    update_user_meta( $user_id, 'package_listings', $free_pack_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $free_pack_feat_listings);
    update_user_meta( $user_id, 'pack_image_included', $free_pack_images);
    update_user_meta( $user_id, 'stripe_subscription_id', '' );
    update_user_meta( $user_id, 'stripe'                ,  '' );
    $args = array(
            'post_type' => 'estate_property',
            'author'    => $user_id,
            'post_status'   => 'any'
    );

    $query = new WP_Query( $args );
    while( $query->have_posts()){
            $query->the_post();

            $prop = array(
                    'ID'            => $post->ID,
                    'post_type'     => 'estate_property',
                    'post_status'   => 'expired'
            );

            wp_update_post($prop );
      }
    wp_reset_query();

    $user_role = get_user_meta( $user_id, 'user_estate_role', true) ;
    if($user_role==3 || $user_role==4){
        wpestate_expire_agents_for_agencies($user_id);
    }

    $user       =   get_user_by('id',$user_id);
    $user_email =   $user->user_email;

    $arguments=array();
    wpestate_select_email_type($user_email,'membership_cancelled',$arguments);

 }
 endif; // end   wpestate_downgrade_to_free




function wpestate_expire_agents_for_agencies($user_id){

    $args = array(
            'post_type'        =>  'estate_agent',
            'author'           =>  $user_id,
            'post_status'      =>  array( 'any' )
            );

    $prop_selection = new WP_Query($args);

    while ($prop_selection->have_posts()): $prop_selection->the_post();
        $agent_id=get_post_meta(get_the_ID(), 'user_meda_id',true );
        wpestate_downgrade_to_free($agent_id);
    endwhile;


}


/////////////////////////////////////////////////////////////////////////////////////
/// upgrade user
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_upgrade_user_membership') ):
function wpestate_upgrade_user_membership($user_id,$pack_id,$type,$paypal_tax_id,$direct_pay=0){

		$available_listings                  =   floatval( get_post_meta($pack_id, 'pack_listings', true) );
		$featured_available_listings         =   floatval( get_post_meta($pack_id, 'pack_featured_listings', true));
		$pack_unlimited_list                 =   get_post_meta($pack_id, 'mem_list_unl', true);
		$available_images                    =   floatval( get_post_meta($pack_id, 'pack_image_included', true));

    $current_used_listings               =   get_user_meta($user_id, 'package_listings',true);
    $curent_used_featured_listings       =   get_user_meta($user_id, 'package_featured_listings',true);
    $curent_images                       =   get_user_meta($user_id, 'pack_image_included',true);
    $current_pack                        =   get_user_meta($user_id, 'package_id',true);


    $user_curent_listings                   =   wpestate_get_user_curent_listings_no_exp ( $user_id );
    $user_curent_future_listings            =   wpestate_get_user_curent_future_listings_no_exp( $user_id );


    if( wpestate_check_downgrade_situation($user_id,$pack_id) ){
        $new_listings           =   $available_listings;
        $new_featured_listings  =   $featured_available_listings;
    }else{
        $new_listings            =  $available_listings - $user_curent_listings ;
        $new_featured_listings   =  $featured_available_listings-  $user_curent_future_listings ;
    }


    // in case of downgrade
    if($new_listings<0){
        $new_listings=0;
    }

    if($new_featured_listings<0){
        $new_featured_listings=0;
    }


    if ($pack_unlimited_list==1){
        $new_listings=-1;
    }


    update_user_meta( $user_id, 'package_listings', $new_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $new_featured_listings);
    update_user_meta( $user_id, 'pack_image_included', $available_images);

    $time = time();
    $date = date('Y-m-d H:i:s',$time);
    update_user_meta( $user_id, 'package_activation', $date);
    update_user_meta( $user_id, 'package_id', $pack_id);
    $user = get_user_by('id',$user_id);
    $user_email=$user->user_email;

    $arguments=array();
    wpestate_select_email_type($user_email,'membership_activated',$arguments);

    $billing_for='Package';

    if($direct_pay==0){
        $invoice_id=wpestate_insert_invoice($billing_for,$type,$pack_id,$date,$user_id,0,0,$paypal_tax_id);
        update_post_meta($invoice_id, 'pay_status', 1);
      
    }
}

endif; // end   wpestate_upgrade_user_membership


/////////////////////////////////////////////////////////////////////////////////////
/// check for downgrade
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_check_downgrade_situation') ):
function  wpestate_check_downgrade_situation($user_id,$new_pack_id){

    $future_listings                  =   get_post_meta($new_pack_id, 'pack_listings', true);
    $future_featured_listings         =   get_post_meta($new_pack_id, 'pack_featured_listings', true);
    $unlimited_future                 =   get_post_meta($new_pack_id, 'mem_list_unl', true);
    $curent_list                      =   get_user_meta( $user_id, 'package_listings', true) ;

    if($unlimited_future==1){
        return false;
    }

    if ($curent_list == -1 && $unlimited_future!=1 ){ // if is unlimited and go to non unlimited pack
        return true;
    }

    if ( (wpestate_get_user_curent_listings_published($user_id) > $future_listings ) || ( wpestate_get_user_curent_future_listings($user_id) > $future_featured_listings ) ){
        return true;
    }else{
        return false;
    }


}
endif; // end   wpestate_check_downgrade_situation


/////////////////////////////////////////////////////////////////////////////////////
/// get the number of listings
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_user_curent_listings') ):
function wpestate_get_user_curent_listings($userid) {
  $args = array(
        'post_type'     =>  'estate_property',
        'post_status'   =>  'any',
        'author'        =>  $userid,

    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
}
endif; // end   get_user_curent_listings



if( !function_exists('wpestate_get_user_curent_listings_published') ):
function wpestate_get_user_curent_listings_published($userid) {
    $agent_list     =  (array) get_user_meta($userid,'current_agent_list',true);
    $agent_list[]   =   $userid;

    $args = array(
        'post_type'     =>  'estate_property',
        'post_status'   =>  'publish',
        'author__in'    =>  $agent_list,

    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
}
endif; // end   get_user_curent_listings


if( !function_exists('wpestate_get_user_curent_listings_no_exp') ):
function wpestate_get_user_curent_listings_no_exp($userid) {
    $agent_list     =   (array)get_user_meta($userid,'current_agent_list',true);
    $agent_list[]   =   $userid;

    $args = array(
        'post_type'     =>  'estate_property',
        'post_status'   =>  array( 'pending', 'publish' ),
        'author__in'    =>  $agent_list,

    );

    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();

}
endif; // end   wpestate_get_user_curent_listings_no_exp


/////////////////////////////////////////////////////////////////////////////////////
/// get the number of featured listings
/////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_user_curent_future_listings_no_exp') ):
function wpestate_get_user_curent_future_listings_no_exp($user_id){
    $agent_list     =   (array)get_user_meta($user_id,'current_agent_list',true);
    $agent_list[]   =   $user_id;

    $args = array(
        'post_type'     =>  'estate_property',
        'post_status'   =>  array( 'pending', 'publish' ),
        'author__in'    =>  $agent_list,
        'meta_query'    =>  array(
                                array(
                                    'key'   => 'prop_featured',
                                    'value' => 1,
                                    'meta_compare '=>'='
                                )
                            )
    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();

}
endif; // end   wpestate_get_user_curent_future_listings_no_exp


if( !function_exists('wpestate_get_user_curent_future_listings') ):
function wpestate_get_user_curent_future_listings($user_id){
    $agent_list     =  (array) get_user_meta($user_id,'current_agent_list',true);
    $agent_list[]   =   $user_id;
    $args = array(
        'post_type'     =>  'estate_property',
        'post_status'   =>  'any',
        'author__in'    =>  $agent_list,
        'meta_query'    =>  array(
                                array(
                                    'key'   => 'prop_featured',
                                    'value' => 1,
                                    'meta_compare '=>'='
                                )
                        )
    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();

}
endif; // end   wpestate_get_user_curent_future_listings

/////////////////////////////////////////////////////////////////////////////////////
/// update user with paypal profile id
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_user_recuring_profile') ):
function wpestate_update_user_recuring_profile( $profile_id,$user_id ){
      $profile_id=  str_replace('-', 'xxx', $profile_id);
      $profile_id=  str_replace('%2d', 'xxx', $profile_id);

      update_user_meta( $user_id, 'profile_id', $profile_id);

}
endif; // end   wpestate_update_user_recuring_profile


////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////

add_action( 'wp_ajax_wpestate_ajax_make_prop_featured', 'wpestate_ajax_make_prop_featured' );

if( !function_exists('wpestate_ajax_make_prop_featured') ):
function  wpestate_ajax_make_prop_featured(){
    check_ajax_referer( 'wpestate_property_actions', 'security' );
    $prop_id        =   intval($_POST['propid']);
    $current_user   =   wp_get_current_user();
    $userID         =   $current_user->ID;
    $parent_userID  =   wpestate_check_for_agency($userID);
    $post           =   get_post($prop_id);

    $agent_list                     =   (array)get_user_meta($parent_userID,'current_agent_list',true);

    if ( !is_user_logged_in() ) {
        exit('ko');
    }
    if($userID === 0 ){
        exit('out pls');
    }

    if( $post->post_author != $userID && !in_array($post->post_author , $agent_list) ) {
        exit('get out of my cloud');
    }else{
        if(wpestate_get_remain_featured_listing_user($parent_userID) >0 ){
            wpestate_update_featured_listing_no($parent_userID);
            update_post_meta($prop_id, 'prop_featured', 1);
            print 'done';
            die();
        }else{
            print 'no places';
            die();
        }
    }

}
endif; // end   wpestate_ajax_make_prop_featured

////////////////////////////////////////////////////////////////////////////////
/// Check user status durin cron
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_check_user_membership_status_function') ):
function wpestate_check_user_membership_status_function(){


    $blogusers = get_users('role=subscriber');
    foreach ($blogusers as $user) {

        $user_id=$user->ID;
        $pack_id= get_user_meta ( $user_id, 'package_id', true);

        if( $pack_id !='' ){ // if the pack is ! free
            $date =  strtotime ( get_user_meta($user_id, 'package_activation',true) );

            $biling_period  =   get_post_meta($pack_id, 'biling_period', true);
            $billing_freq   =   get_post_meta($pack_id, 'billing_freq', true);

            $seconds=0;
            switch ($biling_period){
               case 'Day':
                   $seconds=60*60*24;
                   break;
               case 'Week':
                   $seconds=60*60*24*7;
                   break;
               case 'Month':
                   $seconds=60*60*24*30;
                   break;
               case 'Year':
                   $seconds=60*60*24*365;
                   break;
            }
            $time_frame=$seconds*$billing_freq;

            $now=time();


            if( $now > ( $date+$time_frame-(60*60*24*3))  &&  ($now <$date+$time_frame-(60*60*24*2)) ){ // if this moment is bigger than pack activation + billing period
                wpestate_downgrade_warning($user_id);
            }


            if( $now >$date+$time_frame ){ // if this moment is bigger than pack activation + billing period
                 wpestate_downgrade_to_free($user_id);
            }

        } // end if if pack !- free

    }// end foreach

    wpestate_check_free_listing_expiration();

}
endif; // end   wpestate_check_user_membership_status_function

if( !function_exists('wpestate_check_if_developer_or_agency') ):
function wpestate_check_if_developer_or_agency($user_id){
        $user_agent_id      =  intval( get_user_meta($user_id,'user_agent_id',true) );
      
        if($user_agent_id!=0){
            $author_id          =  wpsestate_get_author($user_agent_id);
            $user_role=0;
            if($author_id){
                $user_role          =   intval( get_user_meta( $author_id, 'user_estate_role', true) ) ;
            }else if($user_role==2 || $user_role==3){
                return true;
            }
        }
        return false;
}
endif;
////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_check_free_listing_expiration') ):
    function wpestate_check_free_listing_expiration(){

        $free_feat_list_expiration= intval ( wpresidence_get_option('wp_estate_free_feat_list_expiration','') );

        if($free_feat_list_expiration!=0 && $free_feat_list_expiration!=''){
            $blogusers = get_users('role=subscriber');
            $users_with_free='';
            $author_array=array();
            foreach ($blogusers as $user) {
                $user_id=$user->ID;
                $pack_id= get_user_meta ( $user_id, 'package_id', true);



                if(wpestate_check_if_developer_or_agency($user_id)){
                   continue;
                }

                if( $pack_id =='' ){ // if the pack is ! free
                    $author_array[]=$user_id;
                }
            }

        if (!empty($author_array)){
            $args = array(
                'post_type'        =>  'estate_property',
                'author__in'           =>  $author_array,
                'post_status'      =>  'publish'
            );

            $prop_selection = new WP_Query($args);
            while ($prop_selection->have_posts()): $prop_selection->the_post();
                $the_id=get_the_ID();
                $pfx_date = strtotime ( get_the_date("Y-m-d",  $the_id ) );
                $expiration_date=$pfx_date+$free_feat_list_expiration*24*60*60;
                $today=time();

                if ($expiration_date < $today){
                    wpestate_listing_set_to_expire($the_id);
                }

            endwhile;
        }


        }
    }
endif;




if( !function_exists('wpestate_listing_set_to_expire') ):
    function wpestate_listing_set_to_expire($post_id){
        $prop = array(
                'ID'            => $post_id,
                'post_type'     => 'estate_property',
                'post_status'   => 'expired'
        );

        wp_update_post($prop );

        $user_id    =   wpsestate_get_author( $post_id );
        $user       =   get_user_by('id',$user_id);
        $user_email =   $user->user_email;

        $arguments=array(
            'expired_listing_url'  =>  esc_url( get_permalink($post_id) ),
            'expired_listing_name' => get_the_title($post_id)
        );
        wpestate_select_email_type($user_email,'free_listing_expired',$arguments);


    }
endif;


if(!function_exists('wpestate_check_for_agency')):
function wpestate_check_for_agency($user_id){
    $agent_id  = intval ( get_user_meta($user_id,'user_agent_id',true) ) ;
    if($agent_id!=0){
        $post=get_post($agent_id);
        if(isset($post->post_author) && $post->post_author!=1 && $post->post_author!=0 && !user_can( $post->post_author, 'manage_options' )){
            return $post->post_author;
        }else{
            return $user_id;
        }
    }else{
        return $user_id;
    }

}
endif;



?>
