/**
 * Google Maps (Custom View Tag) for PHPMaker 2018
 * @license (C) 2017 e.World Technology Ltd.
 */
var ewGoogleMaps=[],ewGoogleMapIndex,ewGoogleMapStyles=[[{url:EW_IMAGE_FOLDER+"people35.png",height:35,width:35,anchor:[16,0],textColor:"#ff00ff",textSize:10},{url:EW_IMAGE_FOLDER+"people45.png",height:45,width:45,anchor:[24,0],textColor:"#ff0000",textSize:11},{url:EW_IMAGE_FOLDER+"people55.png",height:55,width:55,anchor:[32,0],textColor:"#ffffff",textSize:12}],[{url:EW_IMAGE_FOLDER+"conv30.png",height:27,width:30,anchor:[3,0],textColor:"#ff00ff",textSize:10},{url:EW_IMAGE_FOLDER+"conv40.png",height:36,width:40,anchor:[6,0],textColor:"#ff0000",textSize:11},{url:EW_IMAGE_FOLDER+"conv50.png",width:50,height:45,anchor:[8,0],textSize:12}],[{url:EW_IMAGE_FOLDER+"heart30.png",height:26,width:30,anchor:[4,0],textColor:"#ff00ff",textSize:10},{url:EW_IMAGE_FOLDER+"heart40.png",height:35,width:40,anchor:[8,0],textColor:"#ff0000",textSize:11},{url:EW_IMAGE_FOLDER+"heart50.png",width:50,height:44,anchor:[12,0],textSize:12}],[{url:EW_IMAGE_FOLDER+"pin.png",height:48,width:30,anchor:[-18,0],textColor:"#ffffff",textSize:10,iconAnchor:[15,48]}]];function ew_ShowGoogleMap(e){ewGoogleMapIndex++;var o=jQuery,t=e["latlng"],a=e["id"],i=e["use_single_map"],r,l,n=e["show_all_markers"],s=e["use_marker_clusterer"]&&window.MarkerClusterer;var g=function(t){o(document).trigger("map",[o.extend({index:ewGoogleMapIndex},e,t||{})]);if(ewGoogleMapIndex==ewGoogleMaps.length){for(var a in ewGoogleMaps){if(o.isNumber(a))continue;var i=ewGoogleMaps[a];if(i["markers"]&&!i["cluster"])i["cluster"]=new MarkerClusterer(i["map"],i["markers"],i["options"]);if(i["bounds"])i["map"].fitBounds(i["bounds"])}}return true};if(e["inited"])return g();var p=function(e){o("#"+e+"[type='text/html']").each(function(){$scr=o(this);$scr.closest("td").find("span:first").append($scr.html())})};if(!t){p(e["template_id"]);if(!i){o("#"+a).css({width:"",height:"",display:"inline-block"}).html(e["status"]).hide()}return g()}switch(e["type"].toLowerCase()){case"satellite":r=google.maps.MapTypeId.SATELLITE;break;case"hybrid":r=google.maps.MapTypeId.HYBRID;break;case"terrain":r=google.maps.MapTypeId.TERRAIN;break;default:r=google.maps.MapTypeId.ROADMAP}var d={zoom:e["zoom"]||10,center:t,mapTypeId:r};if(i){p(e["template_id"]);if(!ewGoogleMaps[a]){var f=o("<div></div>").attr("id",a).addClass("ewGoogleMap ewSingleMap").height(e["single_map_height"]);var h=o(".ewReportTable, .ewGrid").first();e["show_map_on_top"]?f.insertBefore(h.first()):f.insertAfter(h.first());ewGoogleMaps[a]={map:new google.maps.Map(f[0],d)};if(n)ewGoogleMaps[a]["bounds"]=new google.maps.LatLngBounds;if(s){ewGoogleMaps[a]["markers"]=[];ewGoogleMaps[a]["options"]={maxZoom:e["cluster_max_zoom"]===-1?null:e["cluster_max_zoom"],gridSize:e["cluster_grid_size"]===-1?null:e["cluster_grid_size"],styles:e["cluster_styles"]===-1?null:ewGoogleMapStyles[e["cluster_styles"]],imagePath:EW_IMAGE_FOLDER+"m"}}}}else{if(!ewGoogleMaps[a])ewGoogleMaps[a]={map:new google.maps.Map(o("#"+a)[0],d)}}l=ewGoogleMaps[a];e["inited"]=true;var w=new google.maps.Marker({position:t,map:l["map"],icon:e["icon"]||null,title:e["title"]||""});e["marker"]=w;if(i&&s)l["markers"].push(w);var u=o.trim(e["description"]);if(u){var c=new google.maps.InfoWindow({content:u||""});e["infowindow"]=c;google.maps.event.addListener(w,"click",function(){c.open(l["map"],w)})}if(i&&n)l["bounds"].extend(t);return g(l)}function ew_InitGoogleMaps(){var e=jQuery;ewGoogleMapIndex=0;e.each(ewGoogleMaps,function(o,t){if(t["inited"]){ew_ShowGoogleMap(t)}else{var a=t["id"],i=t["address"],r=t["latitude"],l=t["longitude"],n=t["geocoding_delay"];if(i&&e.trim(i)!=""){e.later(o*n,null,function(){var e=new google.maps.Geocoder;e.geocode({address:i},function(e,o){if(o==google.maps.GeocoderStatus.OK){t["latlng"]=e[0].geometry.location}else{t["status"]=o}ew_ShowGoogleMap(t)})})}else{if(r&&!isNaN(r)&&l&&!isNaN(l))t["latlng"]=new google.maps.LatLng(r,l);ew_ShowGoogleMap(t)}}})}jQuery(ew_InitGoogleMaps);jQuery(function(){$("#ewModalDialog").on("load.ew",ew_InitGoogleMaps)});