<?php
/**
 * WpResidence Welcome Panel
 *
 * This file contains functions to generate and display a custom welcome panel
 * for the WpResidence theme in the WordPress admin dashboard.
 *
 * @package WpResidence
 * @subpackage AdminInterface
 * @since WpResidence 1.0
 */

function wpestate_action_welcome_panel() {
    $logo               =   wpresidence_get_option('wp_estate_logo_image','url');
    $theme_activated    =   wpresidence_get_option('is_theme_activated','');
    $ajax_nonce = wp_create_nonce( "wpestate_setup_nonce" );
    print'<div class="wpestate_admin_theme_back_wrapper">
    <input type="hidden" id="wpestate_setup_nonce" value="'.esc_html($ajax_nonce).'" />    ';
    print '<div class="wpestate_admin_theme_back"></div>';
    print '<div class="wpestate_admin_theme_gradient"></div>';
    print '<img class="img-responsive retina_ready dashboard_widget_logo" src="' . get_theme_file_uri('/img/logo_admin_white.png').'" alt="'.esc_html__('image','wpresidence').'"/>';


    print'<nav class="wpestate_admin_menu">';
         print'<ul class="menu_admin">';
            print'<li><a target="_blank "href="http://help.wpresidence.net/article/how-to-update-the-theme/">'.esc_html__('Update the theme & plugins','wpresidence').'</a></li>';
            print'<li><a target="_blank "href="https://themeforest.net/item/wp-residence-real-estate-wordpress-theme/7896392?ref=annapx&ref=annapx">'.esc_html__('Buy new license','wpresidence').'</a></li>';
            print'<li><a target="_blank "href="http://help.wpresidence.net/article-category/change-log/">'.esc_html__('Change log','wpresidence').'</a></li>';
            print '<li><a href="#">'.esc_html__( 'Get help', 'wpresidence' ).'<div alt="f347" class="dashicons dashicons-arrow-down-alt2"></div></a>';
                print'<ul class="wpestate_dropdown_admin">';
                    print '<li><a target="_blank" href="http://support.wpestate.org/">'.esc_html__( 'Open ticket', 'wpresidence' ).'</a></li>';
                    print '<li><a target="_blank" href="http://help.wpresidence.net/">'.esc_html__( 'Documentation', 'wpresidence' ).'</a></li>';
                    print '<li><a target="_blank" href="https://themeforest.net/downloads">'.esc_html__( 'Rate us', 'wpresidence' ).'</a></li>';
                print'</ul>';
            print '</li>';
        print '</ul>';
    print'</nav>';


    print'<div class="wpestate_admin_version">';
        print '<div class="theme_details_welcome">'.esc_html__('Current Version: ','wpresidence') . wp_get_theme().'</div>';
        if($theme_activated=='is_active'){
            print'<div alt="f528" class="dashicons dashicons-unlock"></div>';
        }else{
            print'<div alt="f528" class="dashicons dashicons-lock"></div>';
        }

    print'</div>';

    print'<div class="wpestate_admin_theme_opt">';
        print'<div id="start_wprentals_setup" class="wpestate_admin_button">' . esc_html__('Start Now', 'wpresidence') . '</div>';
        print'<div class="wpestate_theme_opt wpestate_admin_button"><a href="admin.php?page='. str_replace(' ','',wp_get_theme()).'">' . esc_html__('Site Settings ', 'wpresidence') . '</a></div>';
    print'</div>';


    print'<div id="wpestate_start_wrapper">';
        print'<button type="button" class="wpestate_admin_button wpestate_start_wrapper_close" ><div alt="f158" class="dashicons dashicons-no"></div></button>';

        print'<div class="wpestate_admin_start_notice" id="wpestate_start">';
            print'<p>'.esc_html__('We recommend doing demo import first and then finishing this setup. Adding Demo import AFTER completing this setup changes your settings options to demo options. ','wpresidence') .'</p>';
            print'<div class="wpestate_admin_start_notice_wpapper">';
                print'<div class="wpestate_admin_button" id="button_start_notice">'.esc_html__('Continue','wpresidence') .'</div>'.esc_html__('OR','wpresidence');
                print'<div class="wpestate_admin_button"><a href="themes.php?page=pt-one-click-demo-import">'.esc_html__('Import Demo Content','wpresidence') .'</a></div>';
            print'</div>';
        print '</div>';


        print'<div class="wpestate_admin_start_map" id="wpestate_start_map"><h1>'.esc_html__( 'Map Settings', 'wpresidence' ).'</h1>';
            $api_key                        =   esc_html( wpresidence_get_option('wp_estate_api_key') );

            print'<div class="estate_start_setup">
                <div class="label_option_start">'.esc_html__('Google Maps API KEY','wpresidence').'</div>
                <div class="option_row_explain">'.esc_html__('The Google Maps JavaScript API v3 REQUIRES an API key to function correctly. Get an APIs Console key and post the code in Theme Options. You can get it from','wpresidence').' <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">'.esc_html__('here','wpresidence').'</a></div>
                    <input  type="text" id="api_key" name="api_key" class="regular-text" value="'.esc_html($api_key).'"/>
            </div>';

            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_map_prev" value="' . esc_html__('Previous Step', 'wpresidence') . '"/>';
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_map_set" value="'.esc_html__('Next Step', 'wpresidence').'"/>';


        print'</div>';


        print'<div class="wpestate_admin_start_general_settings" id="wpestate_general_settings"><h1>'.esc_html__('General Settings', 'wpresidence').'</h1>';
            $general_country                =   esc_html( wpresidence_get_option('wp_estate_general_country') );
            print'<div class="estate_start_setup">
                <div class="label_option_row">'.esc_html__('Country','wpresidence').'</div>
                <div class="option_row_explain">'.esc_html__('Select default country','wpresidence').'</div>
                '.wpestate_general_country_list($general_country).'
            </div>';

            $currency_symbol                =   esc_html( wpresidence_get_option('wp_estate_currency_symbol') );
            print'<div class="estate_start_setup">
                <div class="label_option_row">'.esc_html__('Currency symbol','wpresidence').'</div>
                <div class="option_row_explain">'.esc_html__('Set currency symbol for property price.','wpresidence').'</div>
                    <input  type="text" id="currency_symbol" name="currency_symbol"  value="'.esc_html($currency_symbol).'"/>
                </div>';

            $measure_sys='';
            $measure_array = array(

                        array( 'name' => esc_html__('feet','wpresidence'), 'unit'  => esc_html__('ft','wpresidence'), 'is_square' => 0 ),
                        array( 'name' => esc_html__('meters','wpresidence'), 'unit'  => esc_html__('m','wpresidence'), 'is_square' => 0 ),
                        array( 'name' => esc_html__('acres','wpresidence'), 'unit'  => esc_html__('ac','wpresidence'), 'is_square' => 1 ),
                        array( 'name' => esc_html__('yards','wpresidence'), 'unit'  => esc_html__('yd','wpresidence'), 'is_square' => 0 ),
                        array( 'name' => esc_html__('hectares','wpresidence'), 'unit'  => esc_html__('ha','wpresidence'), 'is_square' => 1 ),
            );
            update_option( 'wpestate_measurement_units',  $measure_array);


            $measure_array_status= esc_html( wpresidence_get_option('wp_estate_measure_sys','') );

            foreach($measure_array as $single_unit ){


                    $measure_sys.='<option value="'.esc_html($single_unit['unit']).'"';
                    if ($measure_array_status==$single_unit['unit']){
                        $measure_sys.=' selected="selected" ';
                    }
                                if( $single_unit['is_square'] === 1 ){
                                        $measure_sys.='>'.esc_html($single_unit['name']).' - '.esc_html($single_unit['unit']).'</option>';
                                }else{
                                        $measure_sys.='>'.esc_html__('square','wpresidence').' '.esc_html($single_unit['name']).' - '.esc_html($single_unit['unit']).'<sup>2</sup></option>';
                                }
            }

            print'<div class="estate_start_setup">
                <div class="label_option_row">'.esc_html__('Measurement Unit','wpresidence').'</div>
                <div class="option_row_explain">'.esc_html__('Select the measurement unit you will use on the website','wpresidence').'</div>
                    <select id="measure_sys" name="measure_sys">
                        '.trim($measure_sys).'
                    </select>
                </div>';


            $date_languages=array(  'xx'=> 'default',
                                    'af'=>'Afrikaans',
                                    'ar'=>'Arabic',
                                    'ar-DZ' =>'Algerian',
                                    'az'=>'Azerbaijani',
                                    'be'=>'Belarusian',
                                    'bg'=>'Bulgarian',
                                    'bs'=>'Bosnian',
                                    'ca'=>'Catalan',
                                    'cs'=>'Czech',
                                    'cy-GB'=>'Welsh/UK',
                                    'da'=>'Danish',
                                    'de'=>'German',
                                    'el'=>'Greek',
                                    'en-AU'=>'English/Australia',
                                    'en-GB'=>'English/UK',
                                    'en-NZ'=>'English/New Zealand',
                                    'eo'=>'Esperanto',
                                    'es'=>'Spanish',
                                    'et'=>'Estonian',
                                    'eu'=>'Karrikas-ek',
                                    'fa'=>'Persian',
                                    'fi'=>'Finnish',
                                    'fo'=>'Faroese',
                                    'fr'=>'French',
                                    'fr-CA'=>'Canadian-French',
                                    'fr-CH'=>'Swiss-French',
                                    'gl'=>'Galician',
                                    'he'=>'Hebrew',
                                    'hi'=>'Hindi',
                                    'hr'=>'Croatian',
                                    'hu'=>'Hungarian',
                                    'hy'=>'Armenian',
                                    'id'=>'Indonesian',
                                    'ic'=>'Icelandic',
                                    'it'=>'Italian',
                                    'it-CH'=>'Italian-CH',
                                    'ja'=>'Japanese',
                                    'ka'=>'Georgian',
                                    'kk'=>'Kazakh',
                                    'km'=>'Khmer',
                                    'ko'=>'Korean',
                                    'ky'=>'Kyrgyz',
                                    'lb'=>'Luxembourgish',
                                    'lt'=>'Lithuanian',
                                    'lv'=>'Latvian',
                                    'mk'=>'Macedonian',
                                    'ml'=>'Malayalam',
                                    'ms'=>'Malaysian',
                                    'nb'=>'Norwegian',
                                    'nl'=>'Dutch',
                                    'nl-BE'=>'Dutch-Belgium',
                                    'nn'=>'Norwegian-Nynorsk',
                                    'no'=>'Norwegian',
                                    'pl'=>'Polish',
                                    'pt'=>'Portuguese',
                                    'pt-BR'=>'Brazilian',
                                    'rm'=>'Romansh',
                                    'ro'=>'Romanian',
                                    'ru'=>'Russian',
                                    'sk'=>'Slovak',
                                    'sl'=>'Slovenian',
                                    'sq'=>'Albanian',
                                    'sr'=>'Serbian',
                                    'sr-SR'=>'Serbian-i18n',
                                    'sv'=>'Swedish',
                                    'ta'=>'Tamil',
                                    'th'=>'Thai',
                                    'tj'=>'Tajiki',
                                    'tr'=>'Turkish',
                                    'uk'=>'Ukrainian',
                                    'vi'=>'Vietnamese',
                                    'zh-CN'=>'Chinese',
                                    'zh-HK'=>'Chinese-Hong-Kong',
                                    'zh-TW'=>'Chinese Taiwan',
                );

            $date_lang_symbol =  wpestate_dropdowns_theme_admin_with_key($date_languages,'date_lang');
            print'<div class="estate_start_setup">
                <div class="label_option_row">'.esc_html__('Language for datepicker','wpresidence').'</div>
                <div class="option_row_explain">'.esc_html__('This applies for the calendar field type available for properties.','wpresidence').'</div>
                <select id="date_lang" name="date_lang">
                    '.trim($date_lang_symbol).'
                 </select>
            </div>';


            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_start_general_prev" value="' . esc_html__('Previous Step', 'wpresidence') . '"/>';
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_start_general_set" value="'.esc_html__('Next Step', 'wpresidence').'"/>';

        print'</div>';

        print'<div class="wpestate_admin_start_apperance_settings" id="wpestate_apperance_settings_quick"><h1>'.esc_html__('Apperance Options', 'wpresidence').'</h1>';
           $prop_list_array=array(
               "1"  =>  esc_html__("standard ","wpresidence"),
               "2"  =>  esc_html__("half map","wpresidence")
            );
           $property_list_type_symbol_adv   = wpestate_dropdowns_theme_admin_with_key($prop_list_array,'property_list_type_adv');

            print'<div class="estate_start_setup">
            <div class="label_option_row">'.esc_html__('Property List Type for Advanced Search','wpresidence').'</div>
            <div class="option_row_explain">'.esc_html__('Select standard or half map style for advanced search results page.','wpresidence').'</div>
                <select id="property_list_type_adv" name="property_list_type_adv">
                    '.trim($property_list_type_symbol_adv).'
                </select>
            </div>';

            $prop_unit_array    =   array(
                                        'grid'    =>esc_html__('grid','wpresidence'),
                                        'list'      => esc_html__('list','wpresidence')
                                    );
            $prop_unit_select_view   = wpestate_dropdowns_theme_admin_with_key($prop_unit_array,'prop_unit');

            print'<div class="estate_start_setup">
            <div class="label_option_row">'.esc_html__('Property List display(*global option)','wpresidence').'</div>
            <div class="option_row_explain">'.esc_html__('Select grid or list style for properties list pages.','wpresidence').'</div>
                <select id="prop_unit" name="prop_unit">
                    '.trim($prop_unit_select_view).'
                </select>
            </div>';

            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_apperance_settings_prev" value="' . esc_html__('Previous Step', 'wpresidence') . '"/>';
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_apperance_settings_set" value="'.esc_html__('Next Step', 'wpresidence').'"/>';

        print'</div>';

        print'<div class="wpestate_admin_end_notice" id="wpestate_end">';
            print'<p>'.esc_html__('For further setup see our help files, knowledgebase articles and tutorials to help make this process easier and more enjoyable for you: ','wpresidence') .'<a target="_blank" href="http://help.wpresidence.net/">'.esc_html__( 'http://help.wpresidence.net/', 'wpresidence' ).'</a></p>';
        print'</div>';
    print'</div>';
    print'</div>';
}



add_action( 'welcome_panel', 'wpestate_action_welcome_panel', 11, 1 ); // add theme admin welcome panel







/**
 * Add custom dashboard widgets for WPResidence theme
 *
 * This function adds custom dashboard widgets for administrators in the WPResidence theme.
 * It includes widgets for personalizing the website, adding new pages, managing payments,
 * and adding new properties.
 *
 * @since 1.0.0
 */
function wpestate_add_dashboard_widgets() {
    // Check if the current user is an administrator
    if ( ! current_user_can( 'administrator' ) ) {
        return;
    }

    // Add custom dashboard widgets
    wp_add_dashboard_widget(
        'wpestate_dashboard_welcome',
        esc_html__( 'Personalize Your Website', 'wpresidence' ),
        'wpestate_add_welcome_widget'
    );

    wp_add_dashboard_widget(
        'wpestate_dashboard_links',
        esc_html__( 'Add New Page', 'wpresidence' ),
        'wpestate_add_new_page_widget'
    );

    wp_add_dashboard_widget(
        'wpestate_set_payments',
        esc_html__( 'Payments', 'wpresidence' ),
        'wpestate_add_payments_widget'
    );

    wp_add_dashboard_widget(
        'wpestate_dashboard_new_property',
        esc_html__( 'Add New Property', 'wpresidence' ),
        'wpestate_add_new_property_widget'
    );
}

// Hook the function to the dashboard setup action
add_action( 'wp_dashboard_setup', 'wpestate_add_dashboard_widgets' );



/**
 * WPResidence Admin Login Customization
 *
 * This file contains a function to customize the WordPress admin login page
 * for the WPResidence theme, including logo and styling.
 *
 * @package WPResidence
 * @subpackage AdminCustomization
 * @since 1.0.0
 */

if ( ! function_exists( 'wpestate_admin_login_logo' ) ) :
    /**
     * Customize the WordPress admin login page
     *
     * This function adds custom CSS to the login page to change the logo,
     * background, and overall styling to match the WPResidence theme.
     *
     * @since 1.0.0
     */
    function wpestate_admin_login_logo() {
        // Get the custom logo URL from theme options
        $logo_url = esc_url( wpresidence_get_option( 'wp_estate_logo_image', 'url' ) );
        
        // If no custom logo is set, use the default theme logo
        if ( empty( $logo_url ) ) {
            $logo_url = get_theme_file_uri( '/img/logo.png' );
        }
        ?>
        <style type="text/css">
            /* Custom login logo */
            body.login div#login h1 a {
                background-image: url(<?php echo esc_url( $logo_url ); ?>);
                background-size: 161px;
                background-position: center bottom;
                background-repeat: no-repeat;
                width: 192px;
                height: 85px;
                margin: 10px auto;
                padding-bottom: 30px;
            }

            /* Login page background */
            body.login {
                background: rgb(20,28,21);
                background: linear-gradient(43deg, rgba(20,28,21,1) 0%, rgba(57,108,223,1) 100%);
            }

            /* Login form container */
            #login {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 0;
                margin: 0;
                background-color: #fff;
                box-shadow: 0 1px 3px rgba(0,0,0,.13);
            }

            /* Login form */
            .login form {
                box-shadow: none;
                padding: 26px 24px;
                margin-top: 0;
            }

            /* Interim login adjustments */
            .interim-login #login {
                transform: translate(-50%, -56%);
            }

            .interim-login #login_error,
            .interim-login.login .message {
                margin: 0;
            }

            /* WordPress auth check modal */
            #wp-auth-check-wrap #wp-auth-check {
                max-height: 515px !important;
            }
        </style>
        <?php
    }
endif;

add_action( 'login_head', 'wpestate_admin_login_logo' );




/**
 * WPResidence Login Page Customizations
 *
 * This file contains functions to customize the WordPress login page for the WPResidence theme.
 * It includes customizations for the login logo URL, login logo title, and a function to disable license notifications.
 *
 * @package WPResidence
 * @subpackage LoginCustomization
 * @since 1.0.0
 */

if ( ! function_exists( 'wpestate_login_logo_url' ) ) :
    /**
     * Customize the login logo URL
     *
     * This function changes the URL of the logo on the login page to the home URL of the site.
     *
     * @since 1.0.0
     * @return string The URL for the login logo link
     */
    function wpestate_login_logo_url() {
        return esc_url( home_url( '/' ) );
    }
endif;
add_filter( 'login_headerurl', 'wpestate_login_logo_url' );







if ( ! function_exists( 'wpestate_login_logo_url_title' ) ) :
    /**
     * Customize the login logo title text
     *
     * This function changes the title attribute of the logo link on the login page.
     *
     * @since 1.0.0
     * @return string The title text for the login logo link
     */
    function wpestate_login_logo_url_title() {
        return sprintf(
            '%s %s',
            esc_html__( 'Powered by', 'wpresidence' ),
            esc_url( home_url( '/' ) )
        );
    }
endif;
add_filter( 'login_headertext', 'wpestate_login_logo_url_title' );






if ( ! function_exists( 'wpestate_disable_licence_notifications' ) ) :
    /**
     * Disable license notifications
     *
     * This function handles an AJAX request to disable license notifications for administrators.
     *
     * @since 1.0.0
     */
    function wpestate_disable_licence_notifications() {
        // Verify the AJAX request nonce for security
        check_ajax_referer( 'wpestate_close_notice_nonce', 'security' );

        // Check if the current user has administrator capabilities
        if ( current_user_can( 'administrator' ) ) {
            update_option( 'wp_estate_disable_notice', 'yes' );
        }

        wp_die(); // Proper way to end AJAX functions
    }
endif;
add_action( 'wp_ajax_wpestate_disable_licence_notifications', 'wpestate_disable_licence_notifications' );