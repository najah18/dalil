<?php
$show_adv_search    =   wpresidence_get_option('wp_estate_show_adv_search_general','');

if(isset($post->ID)){
    $show_adv_search_local = get_post_meta($post->ID,'page_show_adv_search',true);
    if($show_adv_search_local==''){
        $show_adv_search_local='global';
    }
    if($show_adv_search_local!='global'){
        $show_adv_search = $show_adv_search_local;
    }
    if(wpestate_half_map_conditions ($post->ID) ){
        $show_adv_search='no';
    }
}
if(is_tax() || is_category() || is_archive() ||is_tag()){
    $show_adv_search        =   wpresidence_get_option('wp_estate_show_adv_search_tax','');
}

if($show_adv_search!=='no'){
    $adv_submit                 =   wpestate_get_template_link('page-templates/advanced_search_results.php');
    $args                       =   wpestate_get_select_arguments();
    $action_select_list         =   wpestate_get_action_select_list($args);
    $categ_select_list          =   wpestate_get_category_select_list($args);
    $select_city_list           =   wpestate_get_city_select_list($args);
    $select_area_list           =   wpestate_get_area_select_list($args);
    $select_county_state_list   =   wpestate_get_county_state_select_list($args);
    $home_small_map_status      =   esc_html ( wpresidence_get_option('wp_estate_home_small_map','') );
    $show_adv_search_map_close  =   esc_html ( wpresidence_get_option('wp_estate_show_adv_search_map_close','') );
    $class                      =   'hidden';
    $class_close                =   '';
    $allowed_html               =   array();
?>
    <div id="adv-search-header-mobile" class=" d-xl-none">
        <i class="fas fa-search"></i>
        <?php esc_html_e('Advanced Search','wpresidence');?>
    </div>


    <div class="adv-search-mobile"  id="adv-search-mobile">
        <?php
        $adv_search_type        =   wpresidence_get_option('wp_estate_adv_search_type','');
        if ( $adv_search_type==6 ){
            print wpestate_show_advanced_search_tabs($adv_submit,'mobile');
        }else{

        ?>
        <form role="search" method="get"   action="<?php print esc_url($adv_submit); ?>" >

            <?php

            if ( $adv_search_type!==2 ){
                $adv_search_label       =   wpresidence_get_option('wp_estate_adv_search_label','');
              
                $adv_search_what        =   wpresidence_get_option('wp_estate_adv_search_what','');


                    if ( $adv_search_type==6 || $adv_search_type==7 || $adv_search_type==8 || $adv_search_type==9 ){
                        $adv6_taxonomy          =   wpresidence_get_option('wp_estate_adv6_taxonomy');
                        $search_field='';
                        if ($adv6_taxonomy=='property_category'){
                            $search_field="categories";
                        }else if ($adv6_taxonomy=='property_action_category'){
                            $search_field="types";
                        }else if ($adv6_taxonomy=='property_city'){
                            $search_field="cities";
                        }else if ($adv6_taxonomy=='property_area'){
                            $search_field="areas";
                        }else if ($adv6_taxonomy=='property_county_state'){
                            $search_field="county / state";
                        }

                        wpestate_show_search_field_tab_inject($adv_search_label[$key],'mobile',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,'',$select_county_state_list);
                    }

                    if($adv_search_type==10 ){
                        $adv_actions_value=esc_html__('Types','wpresidence');
                        $adv_actions_value1='all';

                        print '
                            <input type="text" id="adv_location" class="form-control" name="adv_location"  placeholder="'.esc_html__('Type address, state, city or area','wpresidence').'" value="">
                        ';

                        print wpestate_show_dropdown_taxonomy_v21('types',$adv_actions_value , '');
                        print '<input type="hidden" name="is10" value="10">';
                    }



                    if($adv_search_type==11 ){
                        $adv_actions_value=esc_html__('Types','wpresidence');
                        $adv_actions_value1='all';
                        $adv_categ_value    = esc_html__('Categories','wpresidence');
                        $adv_categ_value1   ='all';

                        print'
                        <input type="text" id="keyword_search" class="form-control" name="keyword_search"  placeholder="'. esc_html__('Type Keyword','wpresidence').'" value="">
                        ';

                        print wpestate_show_dropdown_taxonomy_v21('categories',$adv_categ_value , '');
                        print wpestate_show_dropdown_taxonomy_v21('types',$adv_actions_value , '');

                        print ' <input type="hidden" name="is11" value="11">';
                    }

                    if(is_array($adv_search_what)){
                        $adv_search_label       =   wpresidence_get_option('wp_estate_adv_search_label','');
                        foreach($adv_search_what as $key=>$search_field){
                            if($search_field=='property-price-v2'){
                                $price_array_data='';
                            }else{
                                $price_array_data=array();
                                $price_array_data['min_price_values']      =   wpresidence_get_option('wp_estate_min_price_dropdown_values','');
                                $price_array_data['max_price_values']      =    wpresidence_get_option('wp_estate_max_price_dropdown_values','');
                            }
            
                            wpestate_show_search_field($adv_search_label[$key],'mobile',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list,'','','','',$price_array_data);
                        }
                    }
               

            $extended_search= wpresidence_get_option('wp_estate_show_adv_search_extended','');
            if($extended_search=='yes'){
                show_extended_search('mobile');
            }
            ?>

            <?php
            } else {
            ?>
                <input type="text" id="adv_location_mobile" class="form-control" name="adv_location"  placeholder="<?php esc_html_e('Search State, City or Area','wpresidence');?>" value="">

                <input type="hidden" name="is2" value="1">
                <div class="dropdown form-control" >
                    <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger" data-value="">
                        <?php
                        echo  esc_html__('Categories','wpresidence');
                        ?>
                    <span class="caret caret_filter"></span> </div>

                    <input type="hidden" name="filter_search_type[]" value="<?php if(isset($_GET['filter_search_type'][0])){echo  esc_attr( wp_kses($_GET['filter_search_type'][0], $allowed_html) );}?>">
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                        <?php print trim($categ_select_list);?>
                    </ul>
                </div>

                <div class="dropdown form-control" >
                    <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value="">
                        <?php esc_html_e('Types','wpresidence');?>
                        <span class="caret caret_filter"></span> </div>

                    <input type="hidden" name="filter_search_action[]" value="<?php if(isset($_GET['filter_search_action'][0])){echo esc_attr( wp_kses($_GET['filter_search_action'][0], $allowed_html) );}?>">
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                        <?php print trim($action_select_list);?>
                    </ul>
                </div>

            <?php


                $availableTags  =  wpestate_return_data_for_location_autocomplete();

                print '<script type="text/javascript">
                           //<![CDATA[
                           jQuery(document).ready(function(){
                                var availableTags = ['.$availableTags.'];
                                jQuery("#adv_location_mobile").autocomplete({
                                    source: availableTags
                                });
                           });
                           //]]>
                        </script>';


            }
        }
            ?>


            <?php  if ( $adv_search_type!=6 ){  ?>
                <button class="wpresidence_button" id="advanced_submit_2_mobile"><?php esc_html_e('Search Properties','wpresidence');?></button>
            <?php } ?>

            <?php wp_nonce_field( 'wpestate_regular_search', 'wpestate_regular_search_nonce' ); ?>
         </form>
    </div>
<?php } ?>
