<?php
//#################################################################
// Initial Values
  $data_ngs_default = array("show_panels"         => 1,
                            "panel_width"       => 600,
                            "panel_height"       => 400,
                            "panel_scale"        => "nocrop",
                            "transition_speed"   => 800,
                            "transition_interval" => 4000,
                            "fade_panels"        => 1,
                            "show_captions"      => 0,
                            "overlay_position"   => "bottom",
                            "overlay_opacity"    => 0.7,
                            "show_filmstrip"     => 1,
                            "filmstrip_position" => "bottom",
                            "pointer_size"       => 8,
                            "frame_width"        => 60,
                            "frame_height"       => 40,
                            "frame_scale"        => "crop", 
                            "frame_gap"          => 5,
                            "frame_opacity"      => 0.3,
                            "easing_value"       => "swing",
                            "nav_theme"          => "dark",
                            "pause_on_hover"      => 0,
                            "start_frame"        => 1);

  add_option('dataGalleryView', $data_ngs_default, 'Data from NextGen GalleryView');
  
  $data_ngs = get_option('dataGalleryView');
  
  define('BASE_URL'  , get_option('siteurl'));
  define('GALLERYVIEW_URL', get_option('siteurl').'/wp-content/plugins/' . dirname(plugin_basename(__FILE__))); // get_bloginfo('wpurl')

//#################################################################

function nggGalleryViewHeadAdmin() { ?>
  <!-- begin nextgen-js-galleryview admin scripts -->
    <style>    
      fieldset {
        border:1px solid #DFDFDF;
        background:#fff;
        -moz-border-radius-bottomleft:6px;
        -moz-border-radius-bottomright:6px;
        -moz-border-radius-topleft:6px;
        -moz-border-radius-topright:6px;      
      }
      
      legend {
        font-weight:bold;
        padding:0px 6px;
      }    
    </style>
  <!-- end nextgen-js-galleryview admin scripts -->    
  <?php  
  nggGalleryViewHead();
}

function nggGalleryViewHead() {
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  echo '<!-- begin nextgen-js-galleryview scripts -->
          <script type="text/javascript"  src="'.GALLERYVIEW_URL.'/GalleryView/scripts/jquery.timers-1.1.2.js"></script>
          <script type="text/javascript"  src="'.GALLERYVIEW_URL.'/GalleryView/scripts/jquery.easing.1.3.js"></script>
          <script type="text/javascript"  src="'.GALLERYVIEW_URL.'/GalleryView/scripts/jquery.galleryview-2.0.js"></script>
          <link   type="text/css"        href="'.GALLERYVIEW_URL.'/GalleryView/css/galleryview.css" rel="stylesheet" media="screen" />
        <!-- end nextgen-js-galleryview scripts -->
       ';
}

function nggGalleryViewAlign($align, $margin, $who="") {
    switch ($align) {
      case "left"       : $align = "margin:0px auto 0px 0px;";           break;
      case "right"      : $align = "margin:0px 0px 0px auto;";           break;
      case "center"     : $align = "margin:0px auto;";                   break;
      case "float_left" : $align = "float:left;  margin:".$margin."px;"; break;
      case "float_right": $align = "float:right; margin:".$margin."px;"; break;
    }
  
  return $align;
}

function nggGalleryViewShow($info, $pictures = null) {	
  global $wpdb, $data_ngs;  

  extract(shortcode_atts(array(
  	"id"                   => $data_ngs["id"],
    "show_panels"          => $data_ngs["show_panels"],
    "panel_width"          => $data_ngs["panel_width"],
    "panel_height"         => $data_ngs["panel_height"],
    "panel_scale"          => $data_ngs["panel_scale"],
    "transition_speed"     => $data_ngs["transition_speed"],
    "transition_interval"  => $data_ngs["transition_interval"],
    "fade_panels"          => $data_ngs["fade_panels"],
    "show_captions"        => $data_ngs["show_captions"],
    "overlay_position"     => $data_ngs["overlay_position"],
    "overlay_opacity"      => $data_ngs["overlay_opacity"],
    "show_filmstrip"       => $data_ngs["show_filmstrip"],   
    "filmstrip_position"   => $data_ngs["filmstrip_position"],
    "pointer_size"         => $data_ngs["pointer_size"],
    "frame_width"          => $data_ngs["frame_width"],
    "frame_height"         => $data_ngs["frame_height"],
    "frame_scale"          => $data_ngs["frame_scale"],
    "frame_gap"            => $data_ngs["frame_gap"],
    "frame_opacity"        => $data_ngs["frame_opacity"],
    "easing_value"         => $data_ngs["easing_value"],
    "nav_theme"            => $data_ngs["nav_theme"],
    "pause_on_hover"       => $data_ngs["pause_on_hover"],
    "start_frame"          => $data_ngs["start_frame"]
  	), $info));
  	if (class_exists('nggLoader')) {
      $galleryID = $wpdb->get_var("SELECT gid FROM $wpdb->nggallery WHERE gid  = '".esc_attr($id)."' ");
    }
    
  // Get the pictures
  if ($galleryID) {
    $ngg_options = get_option('ngg_options');  
    $pictures    = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryID' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ");
               
    $final = array();    
    foreach($pictures as $picture) {
      $aux = array();
      $aux["title"] = $picture->alttext; // $picture->alttext;
      $aux["desc"]  = $picture->description;
      $aux["link"]  = BASE_URL . "/" . $picture->path ."/" . $picture->filename;
      $aux["img"]   = BASE_URL . "/" . $picture->path ."/" . $picture->filename;
      $aux["thumb"] = BASE_URL . "/" . $picture->path ."/thumbs/thumbs_" . $picture->filename;
      
      $final[] = $aux;
    }
    
    $pictures = $final;
    
  } else {
    $galleryID = rand();
  }
  
  if (empty($pictures)) return "";
  
  $out = '<ul id="myGallery_'.$galleryID.'" class="galleryview">';
    
  // Error with only one element
  foreach ($pictures as $picture)
    if ($picture["img"]) {
      $out .= "<li>";
      $out .= "<img src=\"" . $picture["img"]   . "\" alt=\"".  $picture["title"] . "\" class=\"full\" />";
      if ($show_captions) {
        $out .= "  <span class=\"panel-overlay\"> " . "<h2>" . $picture["title"] . "</h2>". "<p>". $picture["desc"] . "</p>". "</span>";
      }
      $out .= "</li>";    
    }

  $out .= ' </ul>';
  
  // Gather pictures and GalleryView Gallery
  $out .= '<script type="text/javascript">
            jQuery(document).ready(function($) {
            $(\'#myGallery_'.$galleryID.'\').galleryView({ '; // Leave a blank space in case there is no last comma to be removed later
              
  $out .= " show_panels: " . $show_panels . ",";
  $out .= " show_captions: " . $show_captions . ",";
  $out .= " show_filmstrip: " . $show_filmstrip . ",";
  
  if ($show_panels) {
    $out .= " panel_width: $panel_width,";
    $out .= " panel_height: $panel_height,";
    $out .= " panel_scale: \"$panel_scale\",";
    $out .= " transition_speed: $transition_speed,";
    $out .= " transition_interval: $transition_interval,";
    $out .= " fade_panels: " . $fade_panels . ",";
  }
  if ($show_captions) {
    $out .= " overlay_position: \"$overlay_position\",";
    $out .= " overlay_opacity: $overlay_opacity,";
  }
  if ($show_filmstrip) {
    $out .= " frame_width: $frame_width,";
    $out .= " frame_height: $frame_height,";
    $out .= " filmstrip_position: \"$filmstrip_position\",";
    $out .= " pointer_size: $pointer_size,";
    $out .= " frame_scale: \"$frame_scale\",";
    $out .= " frame_gap: $frame_gap,";
    $out .= " frame_opacity: $frame_opacity,";
    $out .= " easing: \"$easing_value\",";
  }
  $out .= " nav_theme: \"$nav_theme\",";
  $out .= " start_frame: $start_frame,";
  $out .= " pause_on_hover: " . $pause_on_hover . ",";
  
  
  $out = substr($out, 0, -1); // Remove last comma
  $out .= '   });';
  $out .= '});';
  $out .= '</script>';
  //$out .= '<!--' . print_r($show_filmstrip) . '-->';
  return $out;  
}

?>