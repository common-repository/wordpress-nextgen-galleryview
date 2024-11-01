<?php
/*
Plugin Name: WordPress NextGen GalleryView
Plugin URI: http://hybridindie.com/
Description: jQuery JavaScript Gallery plugin extending NextGen Gallery's slideshow abilities without breakage. Uses GalleryView - jQuery Content Gallery Plugin by Jack Anderson (http://www.spaceforaname.com/galleryview/).
Author: John Brien
Author URI: http://blog.hybridindie.com/
Version: 0.5.5                         
*/ 

//#################################################################
// Restrictions
  if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

include "nggGalleryViewSharedFunctions.php";
  
class GalleryView {
  
  function admin_menu() {  
    add_menu_page('GalleryView Defaults', 'GalleryView', 8, plugin_basename( dirname(__FILE__)), array($this, 'general_page')); // add_options_page
    add_submenu_page( plugin_basename( dirname(__FILE__)), 'GalleryView Defaults', 'GalleryView Defaults', 8, plugin_basename( dirname(__FILE__)), array($this, 'general_page'));
    add_submenu_page( plugin_basename( dirname(__FILE__)), 'Option Generator', 'Options Generator', 8, 'specific_galleryview', array($this, 'specific_page'));
  } 
  
  
  function save_request() {
    global $data_ngs, $_REQUEST;
    
    $data_ngs['show_panels'] = (bool)   $_REQUEST['show_panels'];
    // panel_width	integer (pixels)	400	Width of panel
    $data_ngs['panel_width'] = (int)    $_REQUEST['panel_width'];
    // panel_height	integer (pixels)	300	Height of panel
    $data_ngs['panel_height'] = (int)    $_REQUEST['panel_height'];
    $data_ngs['panel_scale'] = (string)    $_REQUEST['panel_scale'];
    
    // transition_speed	jQuery time value (200,’slow’,etc)	400	Duration of transition animation
    $data_ngs['transition_speed'] = (int)   $_REQUEST['transition_speed'];
    // transition_interval	jQuery time value (200,’slow’,etc)	6000	Length of time between transitions (0 = no automatic transitions)
    $data_ngs['transition_interval'] = (int)   $_REQUEST['transition_interval'];
    // fade_panels	boolean	true	Determines whether panels fade during transitions or switch instantly.
    $data_ngs['fade_panels'] = (bool)   $_REQUEST['fade_panels'];
    
    // show_captions	boolean	false	Determines whether or not frame captions are displayed
    $data_ngs['show_captions'] = (bool)   $_REQUEST['show_captions'];
    // overlay_position	‘top’ | ‘bottom’	‘bottom’	Position of overlay within panel
    $data_ngs['overlay_position'] = (string)    $_REQUEST['overlay_position'];
    // overlay_opacity	float 0.0 – 1.0	0.6	Opacity of panel overlay background
    $data_ngs['overlay_opacity'] = (float)   $_REQUEST['overlay_opacity'];
    
    $data_ngs['show_filmstrip'] = (bool)   $_REQUEST['show_filmstrip'];
    // filmstrip_position	‘top’ | ‘bottom’	‘bottom’	Position of filmstrip within gallery
    $data_ngs['filmstrip_position'] = (string)    $_REQUEST['filmstrip_position'];
    $data_ngs['pointer_size'] = (int)   $_REQUEST['pointer_size'];
    // frame_width	integer (pixels)	80	Width of filmstrip frame
    $data_ngs['frame_width'] = (int)    $_REQUEST['frame_width'];
    // frame_height	integer (pixels)	80	Height of filmstrip frame
    $data_ngs['frame_height'] = (int)    $_REQUEST['frame_height'];
    $data_ngs['frame_scale'] = (string)    $_REQUEST['frame_scale'];
    $data_ngs['frame_gap'] = (int)   $_REQUEST['frame_gap'];
    // overlay_opacity	float 0.0 – 1.0	0.6	Opacity of panel overlay background
    $data_ngs['frame_opacity'] = (float)   $_REQUEST['frame_opacity'];
    
    // easing	jQuery easing value (’linear’,’swing’,etc)	’swing’	Controls animation of filmstrip and pointer
    $data_ngs['easing_value'] = (string)    $_REQUEST['easing_value'];
    // nav_theme	(’light’ | ‘dark’)	‘light’	Color of navigation buttons and frame pointer
    $data_ngs['nav_theme'] = (string)    $_REQUEST['nav_theme'];
    // pause_on_hover	boolean	false	If true, animations will pause when the mouse hovers over the panel (requires 500ms hover to pause)    
    $data_ngs['pause_on_hover'] = (bool)   $_REQUEST['pause_on_hover'];
    $data_ngs['start_frame'] = (int)   $_REQUEST['start_frame'];  
  }
  
  function specific_page() {
  	global $data_ngs, $wpdb;

    if ($_REQUEST["enviar"])
      $this->save_request();
  
    $code  = "[galleryview id=yyy";
    $code .= " show_panels=" . ($data_ngs['show_panels'] ? 'true' : 'false');
    $code .= " show_captions=" . ($data_ngs['show_captions'] ? 'true' : 'false');
    $code .= " show_filmstrip=" . ($data_ngs['show_filmstrip'] ? 'true' : 'false');

    if ($data_ngs['show_panels'] ) {
      $code .= " panel_width=" . $data_ngs['panel_width'];
      $code .= " panel_height=" . $data_ngs['panel_height'];
      $code .= " panel_scale=" . $data_ngs['panel_scale'];
      $code .= " transition_speed=" . $data_ngs['transition_speed'];
      $code .= " transition_interval=" . $data_ngs['transition_interval'];
      $code .= " fade_panels=" . ($data_ngs['fade_panels'] ?'true':'false');
    }
    if ($data_ngs['show_captions']) {
      $code .= " overlay_position=" . $data_ngs['overlay_position'];
      $code .= " overlay_opacity=" . $data_ngs['overlay_opacity'];
    }
    if ($data_ngs['show_filmstrip']) {
      $code .= " frame_width=" . $data_ngs['frame_width'];
      $code .= " frame_height=" . $data_ngs['frame_height'];
      $code .= " filmstrip_position=" . $data_ngs['filmstrip_position'];
      $code .= " pointer_size=" . $data_ngs['pointer_size'];
      $code .= " frame_scale=" . $data_ngs['frame_scale'];
      $code .= " frame_gap=" . $data_ngs['frame_gap'];
      $code .= " frame_opacity=" . $data_ngs['frame_opacity'];
      $code .= " easing_value=" . $data_ngs['easing_value'];
    }
    $code .= " nav_theme=" . $data_ngs['nav_theme'];
    $code .= " start_frame=" . $data_ngs['start_frame'];
    $code .= " pause_on_hover=" . ($data_ngs['pause_on_hover'] ?'true':'false');
            
    $code .= "]";
      
    $code_2 = "<?php \n  echo  do_shortcode(\"" . $code . "\"); \n?>";
      
    ?>
  	<div class="wrap">
      <h2>NextGen GalleryView</h2>
      <form method="post">      
        <div>   
          <fieldset class="options" style="padding:20px; margin-top:20px;">
            <legend> Specific Options </legend>
              
              Allows a gallery to have a behavior other that the General one. 
              <br/><br/>

              <?php $this->show_admin_layouts(); ?>

              <div class="submit"> 
                <input type="submit" name="enviar" value="Generate Code">
              </div>

            <hr style="width:90%; border:1px solid #DFDFDF;">
            
            <br/>You have two options:
            
            <br><br><b>1. Write on your post</b> (You must replace 'yyy' with your Gallery Id)<br>
            
            <textarea style="width:700px; height:130px;"><?php echo $code; ?></textarea>

            <br><br><b>2. Write on any php page</b> (You must replace 'yyy' with your Gallery Id)<br>
            
            <textarea style="width:700px; height:130px;"><?php echo $code_2; ?></textarea>
            
            <hr style="width:90%; border:1px solid #DFDFDF;">
            
            <br/>If you remove, for example, "width=300, " the General option will be used on that item.
          </fieldset>
        </div>  
        
        <?php $this->example_show($code); ?>
      </form>
    </div>
  <?php }
  
  function general_page() {
  	global $data_ngs, $data_ngs_default, $wpdb;

    $msg = "";
        
    if ($_REQUEST["enviar"] == "Back to Default") {
      $data_ngs = $data_ngs_default;
      update_option('dataGalleryView', $data_ngs);
      $msg = "Data saved successfully.";
    } elseif ($_REQUEST["enviar"]) {
      $this->save_request();
      
      update_option('dataGalleryView', $data_ngs);
      $msg = "Data saved successfully.";
    }
  	
  	if ($msg != '') echo '<div id="message"class="updated fade"><p>' . $msg . '</p></div>';
    
    $code = "[galleryview id=yyy]";    
    ?>    
  	<div class="wrap">
      <h2>NextGen GalleryView</h2>    
      <form method="post">      
        <div>   
          <fieldset class="options" style="padding:20px; margin-top:20px;">
            <legend> Default Options </legend>      
              <?php $this->show_admin_layouts(); ?>      

              <div class="submit" style="clear:both;"> 
                <input type="submit" name="enviar" value="Save">
                <input type="submit" name="enviar" value="Back to Default">
              </div>
              
            <hr style="width:90%; border:1px solid #DFDFDF;">
            <br><br><b>Write on your post</b> (You must replace 'yyy' with your Gallery Id)<br>

            <textarea style="width:700px; height:60px;"><?php echo $code; ?></textarea>            
          </fieldset>
        </div>  
        
        <?php $this->example_show($code); ?>
      </form>
    </div>
  	<?php
  }  
  
  function example_show($code) {
    global $_REQUEST, $data_ngs, $wpdb; 
    
    $gal_id = $_REQUEST['gal_id']; 

    $gallerylist = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY gid ASC");

    $select = "";
    if(is_array($gallerylist))
      foreach($gallerylist as $gallery) {
        $selected = ($gallery->gid == $gal_id )?	' selected="selected"' : "";
        $select .= '<option value="'.$gallery->gid.'"'.$selected.' >('.$gallery->gid.') '.$gallery->title.'</option>'."\n";
      }
      
    if ($gal_id)
      $real_deal = do_shortcode( str_replace("yyy", $gal_id, $code) );
    ?>     
    <div>
      <fieldset class="options" style="padding:20px; margin-top:20px; margin-bottom:20px;">
        <legend> Example </legend>
        
        This is how your gallery will look like with the options above (after you <b>save</b> them). <br/><br/>

        <div class="submit">           
          <div class="alignleft actions">
            <select id="gal_id" name="gal_id" style="width:250px;">;
              <option value="0"> Choose a gallery </option>
              <?php echo $select; ?>
            </select>
            <input type="submit" id="enviar" name="enviar" value="Select" class="button-secondary" />
          </div>            
        </div>
        <br/>
        <div class="slides">
          <?php echo $real_deal; ?>
        </div>
      </fieldset>
    </div>
  <?php } 
  
  function show_admin_layouts() { 
    global $data_ngs; ?>
          <div style="clear:both; padding-top:10px;">
            <div style="width:120px; float:left;"> Show Panel </div>
            <div style="width:120px; float:left;"> <input type="checkbox" id="show_panels" name="show_panels" <?php echo ($data_ngs['show_panels']? "checked=\"checked\"": "") ?> onClick="if(this.checked){document.getElementById('panel_options').style.display='';} else{document.getElementById('panel_options').style.display='none';};" > </div>
          </div>

          <fieldset id="panel_options" class="options" style="padding:20px; margin-top:0px; display:<?php echo ($data_ngs['show_panels']?'':'none')?>;">
            <legend> Panel Options </legend>

              <div style="">
                <div style="width:120px; float:left;"> Panel Width </div>
                <div style="width:120px; float:left;"> <input type="text" name="panel_width" value="<?php echo $data_ngs['panel_width']?>" style="width:60px;">px </div>
              </div>

              <div style="clear:left; padding-top:10px;">
                <div style="width:120px; float:left;"> Panel Height </div>
                <div style="width:120px; float:left;"> <input type="text" name="panel_height" value="<?php echo $data_ngs['panel_height']?>" style="width:60px;">px </div>
              </div>

              <div style="clear:both; padding-top:10px;">
                <div style="width:120px; float:left;"> Panel Scaling </div>
                <div style="width:120px; float:left;"> 
                  <select name="panel_scale">
                    <option value="crop" <?php echo ($data_ngs['panel_scale'] == "crop" ? "selected":"") ?>> Crop to Fill       </option>
                    <option value="nocrop" <?php echo ($data_ngs['panel_scale'] == "nocrop" ? "selected":"") ?>> No Cropping    </option>
                  </select>
                </div>
              </div>

              <div style="clear:left; padding-top:10px;">
                <div style="width:120px; float:left;"> Transition Speed (in milliseconds) </div>
                <div style="width:120px; float:left;"> <input type="text" name="transition_speed" value="<?php echo $data_ngs['transition_speed']?>" style="width:60px;"></div>
              </div>

              <div style="clear:left; padding-top:10px;">
                <div style="width:120px; float:left;"> Transition Interval (in milliseconds, set to 0 for manual transitions) </div>
                <div style="width:120px; float:left;"> <input type="text" name="transition_interval" value="<?php echo $data_ngs['transition_interval']?>" style="width:60px;"></div>
              </div>
              
              <div style="clear:both; padding-top:10px;">
                <div style="width:120px; float:left;"> Fade Panels </div>
                <div style="width:120px; float:left;"> <input type="checkbox" id="fade_panels" name="fade_panels" <?php echo ($data_ngs['fade_panels']? "checked=\"checked\"": "") ?> > </div>
              </div>

          </fieldset>

          <div style="clear:both; padding-top:10px;">
            <div style="width:120px; float:left;"> Show Captions </div>
            <div style="width:120px; float:left;"> <input type="checkbox" id="show_captions" name="show_captions" <?php echo ($data_ngs['show_captions']? "checked=\"checked\"": "") ?> onClick="if(this.checked){document.getElementById('caption_options').style.display='';} else{document.getElementById('caption_options').style.display='none';};" > </div>
          </div>

          <fieldset id="caption_options" class="options" style="padding:20px; margin-top:0px; display:<?php echo ($data_ngs['show_captions']?'':'none')?>;">
            <legend> Caption Options </legend>

            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Caption Overlay Position </div>
              <div style="width:120px; float:left;"> 
                <select name="overlay_position">
                  <option value="top" <?php echo ($data_ngs['overlay_position'] == "top" ? "selected":"") ?>> Top       </option>
                  <option value="bottom" <?php echo ($data_ngs['overlay_position'] == "bottom" ? "selected":"") ?>> Bottom    </option>
                </select>
              </div>
            </div>

            <div style="clear:left; padding-top:10px;">
              <div style="width:120px; float:left;"> Caption Overlay Opacity (0.0 - 1.0) </div>
              <div style="width:120px; float:left;"> <input type="text" name="overlay_opacity" value="<?php echo $data_ngs['overlay_opacity']?>" style="width:60px;"></div>
            </div>

          </fieldset>

          <div style="clear:both; padding-top:10px;">
            <div style="width:120px; float:left;"> Show Filmstrip </div>
            <div style="width:120px; float:left;"> <input type="checkbox" id="show_filmstrip" name="show_filmstrip" <?php echo ($data_ngs['show_filmstrip']? "checked=\"checked\"": "") ?> onClick="if(this.checked){document.getElementById('filmstrip_options').style.display='';} else{document.getElementById('filmstrip_options').style.display='none';};" > </div>
          </div>

          <fieldset id="filmstrip_options" class="options" style="padding:20px; margin-top:0px; display:<?php echo ($data_ngs['show_filmstrip']?'':'none')?>;">
            <legend> Filmstrip Options </legend>
            
            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Filmstrip Position </div>
              <div style="width:120px; float:left;"> 
                <select name="filmstrip_position">
                  <option value="top" <?php echo ($data_ngs['filmstrip_position'] == "top" ? "selected":"") ?>> Top       </option>
                  <option value="bottom" <?php echo ($data_ngs['filmstrip_position'] == "bottom" ? "selected":"") ?>> Bottom    </option>
                  <option value="left" <?php echo ($data_ngs['filmstrip_position'] == "left" ? "selected":"") ?>> Left      </option>
                  <option value="right" <?php echo ($data_ngs['filmstrip_position'] == "right" ? "selected":"") ?>> Right     </option>
                </select>
              </div>
            </div>
            
            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Pointer Size </div>
              <div style="width:120px; float:left;"> <input type="text" name="pointer_size" value="<?php echo $data_ngs['pointer_size']?>" style="width:60px;">px </div>
            </div>
            
            <div style="clear:both;">
              <div style="width:120px; float:left;"> Frame Width </div>
              <div style="width:120px; float:left;"> <input type="text" name="frame_width" value="<?php echo $data_ngs['frame_width']?>" style="width:60px;">px </div>
            </div>

            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Frame Height </div>
              <div style="width:120px; float:left;"> <input type="text" name="frame_height" value="<?php echo $data_ngs['frame_height']?>" style="width:60px;">px </div>
            </div>
          
            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Filmstrip Scaling </div>
              <div style="width:120px; float:left;"> 
                <select name="frame_scale">
                  <option value="crop" <?php echo ($data_ngs['frame_scale'] == "crop" ? "selected":"") ?>> Crop to Fill       </option>
                  <option value="nocrop" <?php echo ($data_ngs['frame_scale'] == "nocrop" ? "selected":"") ?>> No Cropping    </option>
                </select>
              </div>
            </div>

            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Frame Gap </div>
              <div style="width:120px; float:left;"> <input type="text" name="frame_gap" value="<?php echo $data_ngs['frame_gap']?>" style="width:60px;">px </div>
            </div>
            
            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Frame Opacity (0.0 - 1.0) </div>
              <div style="width:120px; float:left;"> <input type="text" name="frame_opacity" value="<?php echo $data_ngs['frame_opacity']?>" style="width:60px;">px </div>
            </div>

            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Filmstrip Animation (easing effect) </div>
              <div style="width:120px; float:left;"> 
                <select name="easing_value">
                  <option value="swing" <?php echo ($data_ngs['easing_value'] == "swing" ? "selected":"") ?>> Swing       </option>
                  <option value="easeInQuad" <?php echo ($data_ngs['easing_value'] == "easeInQuad" ? "selected":"") ?>> easeInQuad    </option>
                  <option value="easeOutQuad" <?php echo ($data_ngs['easing_value'] == "easeOutQuad" ? "selected":"") ?>> easeOutQuad      </option>
                  <option value="easeInOutQuad" <?php echo ($data_ngs['easing_value'] == "easeInOutQuad" ? "selected":"") ?>> easeInOutQuad     </option>
                  <option value="easeInCubic" <?php echo ($data_ngs['easing_value'] == "easeInCubic" ? "selected":"") ?>> easeInCubic     </option>
                  <option value="easeOutCubic" <?php echo ($data_ngs['easing_value'] == "easeOutCubic" ? "selected":"") ?>> easeOutCubic       </option>
                  <option value="easeInOutCubic" <?php echo ($data_ngs['easing_value'] == "easeInOutCubic" ? "selected":"") ?>> easeInOutCubic    </option>
                  <option value="easeInQuart" <?php echo ($data_ngs['easing_value'] == "easeInQuart" ? "selected":"") ?>> easeInQuart      </option>
                  <option value="easeOutQuart" <?php echo ($data_ngs['easing_value'] == "easeOutQuart" ? "selected":"") ?>> easeOutQuart     </option>
                  <option value="easeInOutQuart" <?php echo ($data_ngs['easing_value'] == "easeInOutQuart" ? "selected":"") ?>> easeInOutQuart     </option>
                  <option value="easeInQuint" <?php echo ($data_ngs['easing_value'] == "easeInQuint" ? "selected":"") ?>> easeInQuint       </option>
                  <option value="easeOutQuint" <?php echo ($data_ngs['easing_value'] == "easeOutQuint" ? "selected":"") ?>> easeOutQuint    </option>
                  <option value="easeInOutQuint" <?php echo ($data_ngs['easing_value'] == "easeInOutQuint" ? "selected":"") ?>> easeInOutQuint      </option>
                  <option value="easeInSine" <?php echo ($data_ngs['easing_value'] == "easeInSine" ? "selected":"") ?>> easeInSine     </option>
                  <option value="easeOutSine" <?php echo ($data_ngs['easing_value'] == "easeOutSine" ? "selected":"") ?>> easeOutSine     </option>
                  <option value="easeInOutSine" <?php echo ($data_ngs['easing_value'] == "easeInOutSine" ? "selected":"") ?>> easeInOutSine       </option>
                  <option value="easeInExpo" <?php echo ($data_ngs['easing_value'] == "easeInExpo" ? "selected":"") ?>> easeInExpo    </option>
                  <option value="easeOutExpo" <?php echo ($data_ngs['easing_value'] == "easeOutExpo" ? "selected":"") ?>> easeOutExpo      </option>
                  <option value="easeInOutExpo" <?php echo ($data_ngs['easing_value'] == "easeInOutExpo" ? "selected":"") ?>> easeInOutExpo     </option>
                  <option value="easeInCirc" <?php echo ($data_ngs['easing_value'] == "easeInCirc" ? "selected":"") ?>> easeInCirc     </option>
                  <option value="easeOutCirc" <?php echo ($data_ngs['easing_value'] == "easeOutCirc" ? "selected":"") ?>> easeOutCirc       </option>
                  <option value="easeInOutCirc" <?php echo ($data_ngs['easing_value'] == "easeInOutCirc" ? "selected":"") ?>> easeInOutCirc    </option>
                  <option value="easeInElastic" <?php echo ($data_ngs['easing_value'] == "easeInElastic" ? "selected":"") ?>> easeInElastic      </option>
                  <option value="easeOutElastic" <?php echo ($data_ngs['easing_value'] == "easeOutElastic" ? "selected":"") ?>> easeOutElastic     </option>
                  <option value="easeInOutElastic" <?php echo ($data_ngs['easing_value'] == "easeInOutElastic" ? "selected":"") ?>> easeInOutElastic     </option>
                  <option value="easeInBack" <?php echo ($data_ngs['easing_value'] == "easeInBack" ? "selected":"") ?>> easeInBack       </option>
                  <option value="easeOutBack" <?php echo ($data_ngs['easing_value'] == "easeOutBack" ? "selected":"") ?>> easeOutBack    </option>
                  <option value="easeInOutBack" <?php echo ($data_ngs['easing_value'] == "easeInOutBack" ? "selected":"") ?>> easeInOutBack      </option>
                  <option value="easeInBounce" <?php echo ($data_ngs['easing_value'] == "easeInBounce" ? "selected":"") ?>> easeInBounce     </option>
                  <option value="easeOutBounce" <?php echo ($data_ngs['easing_value'] == "easeOutBounce" ? "selected":"") ?>> easeOutBounce     </option>
                  <option value="easeInOutBounce" <?php echo ($data_ngs['easing_value'] == "easeInOutBounce" ? "selected":"") ?>> easeInOutBounce     </option>
                </select>
              </div>
            </div>

          </fieldset>

          <div style="clear:both; padding-bottom:8px;"></div>
          <fieldset>
            <legend> Advanced Options </legend>
             <div style="width:120px; float:left;"> Theme </div>
              <div style="width:120px; float:left;"> 
                <select name="nav_theme">
                  <option value="dark" <?php echo ($data_ngs['nav_theme'] == "dark" ? "selected":"") ?>> Dark       </option>
                  <option value="light" <?php echo ($data_ngs['nav_theme'] == "light" ? "selected":"") ?>> Light    </option>
                </select>
              </div>
            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Starting Frame </div>
              <div style="width:120px; float:left;"> <input type="text" name="start_frame" value="<?php echo $data_ngs['start_frame']?>" style="width:60px;"></div>
            </div>
            <div style="clear:both; padding-top:10px;">
              <div style="width:120px; float:left;"> Pause On Hover </div>
              <div style="width:120px; float:left;"> <input type="checkbox" id="pause_on_hover" name="pause_on_hover" <?php echo ($data_ngs['pause_on_hover']? "checked=\"checked\"": "") ?> > </div>
            </div>
          </fieldset>

          <div style="clear:both; padding-bottom:8px;"></div>  

            <div class="submit"></div>          
  <?php }
}

function init_jquery() {
  wp_enqueue_script('jquery');
}

$galleryview = new GalleryView();

add_action('admin_menu' , array($galleryview, 'admin_menu'));
add_shortcode('galleryview', 'nggGalleryViewShow');
add_action('init', 'init_jquery');
add_action('wp_head'   , 'nggGalleryViewHead');

if ($_REQUEST["page"] == "specific_galleryview") add_action('admin_head', 'nggGalleryViewHeadAdmin');
//if ($_REQUEST["page"] == "soon_galleryview") add_action('admin_head', 'nggGalleryViewHeadAdmin');
if ($_REQUEST["page"] == plugin_basename( dirname(__FILE__))) add_action('admin_head', 'nggGalleryViewHeadAdmin');
?>