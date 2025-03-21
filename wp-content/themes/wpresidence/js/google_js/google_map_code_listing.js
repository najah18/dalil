/*global google , Modernizr , InfoBox ,OverlappingMarkerSpiderfier,wpestate_setOms,wpestate_initialize_poi, control_vars,googlecode_regular_vars,jQuery,googlecode_property_vars,mapfunctions_vars,googlecode_property_vars2,wpestate_setMarkers,wpestate_set_google_search*/
var gmarkers = [];
var current_place=0;
var actions=[];
var categories=[];

var map_open=0;

var pins='';
var markers='';
var infoBox = null;
var category=null;
var map;
var found_id;
var selected_id         =   jQuery('#gmap_wrapper').attr('data-post_id');
var curent_gview_lat    =   jQuery('#gmap_wrapper').attr('data-cur_lat');
var curent_gview_long   =   jQuery('#gmap_wrapper').attr('data-cur_long');
var panorama;
var panorama_sh;
var oms;
var bounds;
var markers_cluster;




function wpresidence_initialize_map_listing(){
    "use strict";
    wpresidence_map_general_start_map('prop');
    
    if( typeof( googlecode_property_vars2) !== 'undefined' && googlecode_property_vars2.markers2.length > 2){          
        pins    =   googlecode_property_vars2.markers2;
        markers =   jQuery.parseJSON(pins);                 
    }       
    
    
    if(parseInt( jQuery('#googleMap').length) > 0 ||  parseInt( jQuery('#googleMapSlider').length) > 0 ){    
        if (markers.length>0){
            wpresidence_map_general_set_markers(map, markers,'');
        }
    
    
        if(wp_estate_kind_of_map===1 && mapfunctions_vars.show_g_search_status==='yes' && googlecode_property_vars.small_map!=='1'){
            wpestate_set_google_search(map);
        }


        if(wp_estate_kind_of_map===1){
            google.maps.event.trigger(gmarkers[0], 'click');
        }else{
            map.panTo(L.latLng( curent_gview_lat,curent_gview_long ));
            gmarkers[0].addTo( map );
            gmarkers[0].fire('click').openPopup();
        }
       
        wpresidence_map_panorama();
  
        if( parseInt( jQuery('#googleMapSlider').length) > 0){
            wpestate_initialize_poi(map,1,'googleMapSlider');    
        }else{
            wpestate_initialize_poi(map,1,'googleMap');    
        }
   } 
    
     
    
   // if(jQuery('#googleMap_shortcode').length > 0){


    if(jQuery('.googleMap_shortcode_class').length > 0){
        wpestate_map_shortcode_function();       
    }

}
///////////////////////////////// end initialize
/////////////////////////////////////////////////////////////////////////////////// 
 
 
if (typeof google === 'object' && typeof google.maps === 'object') {                                         
    google.maps.event.addDomListener(window, 'load', wpresidence_initialize_map_listing);
}else{
    wpresidence_initialize_map_listing();
}

function wpestate_toggleStreetView() {
    "use strict";
    if (panorama.visible){
         panorama.setVisible(false); 
         jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus').show();
         jQuery('#street-view').removeClass('mapcontrolon');
         jQuery('#street-view').html('<i class="fas fa-location-arrow"></i> '+control_vars.street_view_on);
    }else{
         panorama.setVisible(true);  
         jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus').hide();
         jQuery('#street-view').addClass('mapcontrolon');
         jQuery('#street-view').html('<i class="fas fa-location-arrow"></i> '+control_vars.street_view_off);
    }
}
