<?php
/*
Plugin Name: AFG D5 Shortcodes
Description: Adds useful shortcodes.
Version:     0.0.1
Author:      BET
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Function Prefix: afgd5sh_
*/

defined( 'ABSPATH' ) or die( 'AFGD5sh No script please!' );

function afgd5sh_announcements( $query ) {
  if ( is_admin() )
    return;
  
  if ( $query->is_category('announcement') ){
    $query->set("orderby",'meta_value_num');
    $query->set('meta_key', 'start_date');
    $meta_q = $query->get('meta_query');
    if( ! is_array($meta_q) )
      $meta_q = array();
    
    $date_now = strtotime(date('Y-m-d H:i:s'));
    
    if ( $query->get("order") == "ASC" ){
      $comp_str = '>=';
    } else {
      $comp_str = '<';
    }
    $query->set('meta_query',
      array_merge($meta_q,
        array(
          'relation' => 'AND',
          array(
				        'key' => 'end_date',
				        'compare' => $comp_str,
				        'type' => 'NUMERIC',
				        'value' => $date_now,
				  ),
        )
      )
    );
  }  
}
add_action( 'pre_get_posts',__NAMESPACE__.'\afgd5sh_announcements');


/** 
 * add addToCalendar button to title in "category posts widget"
 */
function afgd5sh_cpw_title_addtocalendar(){
  
}

/**
* Get site url for links
*
* @author bet
*/
function afgd5sh_url_shortcode() {
	#return get_bloginfo('url').'/';#deprecated
  return network_home_url().'/';	
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'afgd5sh_register_custom_plugin_styles' );

/**
 * Register style sheet.
 */
function afgd5sh_register_custom_plugin_styles() {
  wp_register_style( 'atc-style-blue', 'https://addtocalendar.com/atc/1.5/atc-style-blue.css' );
  wp_register_style( 'atc-style-glow-orange', 'https://addtocalendar.com/atc/1.5/atc-style-glow-orange.css');
  wp_register_style( 'atc-style-button-icon', 'https://addtocalendar.com/atc/1.5/atc-style-button-icon.css');
  wp_register_style( 'atc-style-menu-wb', 'https://addtocalendar.com/atc/1.5/atc-style-menu-wb.css');
  
  wp_register_script( 'atc-script', 'https://addtocalendar.com/atc/1.5/atc.min.js' , array('jquery'), false, true );
}

function afgd5sh_addtocalbutton($atts = [], $content = null, $tag = '') {
  // user input date format is YYYY-MM-DD HH:MM:SS  where HH is 0-23
  wp_enqueue_style( 'atc-style-menu-wb' );
  wp_enqueue_style( 'atc-style-button-icon' );
  wp_enqueue_style( 'atc-style-blue' );
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $afgd5sh_addtocal_atts = shortcode_atts([   'start' => date( 'Y-m-d H:i:s' ),
                                     'end' => date( 'Y-m-d H:i:s',time()+60*60 ),
                                     'timezone' => 'America/Detroit',
                                     'title' => 'Al-Anon District 5 Event',
                                     'description' => 'Al-Anon',
                                     'location' => 'Ann Arbor, MI',
                                     'organizer' => 'afgdistrict5',
                                     'organizer_email' => 'afgdistrict5@gmail.com',
                                     'privacy' => 'public',
                                     'width' => '20',
                                 ], $atts, $tag);
  if( !empty($atts['start']) && empty($atts['end']) ){
    $afgd5sh_addtocal_atts['end'] = date( 'Y-m-d H:i:s',strtotime($afgd5sh_addtocal_atts['start'])+60*60);
  }
  // start output
  $output = '';
  //var_dump($afgd5sh_addtocal_atts);
  // enclosing tags
  if (!is_null($content)) {
    // secure output by executing the_content filter hook on $content
    $o = apply_filters('the_content', $content);
    // run shortcode parser recursively
    $output = do_shortcode($o);
  }
  
  ob_start(); //capture output to buffer
  require(plugin_dir_path( __FILE__ ).'addtocalendarbutton.php');
  return $output . ob_get_clean();
}

add_shortcode('open','afgd5sh_open_meeting_footer_text');
function afgd5sh_open_meeting_footer_text($atts = [], $content = '', $tag = '') {
  #WP removes enclosing p elements around shortcode in post content, so have to manually add where needed, which is triggered by including "p" inside shortcode, i.e., [closed p]
  $short_atts = shortcode_atts([
    'p' => false,
    'div' => false,
  ], normalize_empty_atts($atts), $tag);
  
  $pretags = '';
  $appendtags = '';
  if( $short_atts['div'] ){
    $pretags .= "<div>";
    $appendtags .= "</div>";
  }
  if( $short_atts['p'] ){
    $pretags .= "<p>";
    $appendtags .= "</p>";
  }
#  return "OPEN: This meeting welcomes everyone including those who are interested in Al-Anon/Alateen or wish to observe as a student or professional.";
  return $pretags."OPEN: This meeting is open to the public and welcomes everyone interested in Al-Anon/Alateen, including those who wish to observe as a student or professional.".$appendtags;
}

add_shortcode('closed','afgd5sh_closed_shortcode');
if (!function_exists('normalize_empty_atts')) {
  function normalize_empty_atts ($atts) {
    if( is_array($atts) ) {
      foreach ($atts as $attribute => $value) {
        if (is_int($attribute)) {
          $atts[strtolower($value)] = true;
          unset($atts[$attribute]);
        }
      }
    }
    return $atts;
  }
}
function afgd5sh_closed_shortcode($atts = [], $content = '', $tag = '') {
  #WP removes enclosing p elements around shortcode in post content, so have to manually add where needed, which is triggered by including "p" inside shortcode, i.e., [closed p]
  $short_atts = shortcode_atts([
    'p' => false,
    'div' => false,
  ], normalize_empty_atts($atts), $tag);
  
  $pretags = '';
  $appendtags = '';
  if( $short_atts['div'] ){
    $pretags .= "<div>";
    $appendtags .= "</div>";
  }
  if( $short_atts['p'] ){
    $pretags .= "<p>";
    $appendtags .= "</p>";
  }
  if( '' === $content ){
    return $pretags.afgd5sh_closed_meeting_footer_text(false).$appendtags;
  } 
  return $pretags.afgd5sh_closed_meeting_footer_text(true).$appendtags;
}
function afgd5sh_closed_meeting_footer_text($flag) {
  $desc_str = '';
  $desc_str .= "CLOSED: This meeting of ";
    if( true === $flag ){
      $desc_str .= "Alateen";
    }else {
      $desc_str .= "Al-Anon";
    }
    $desc_str .= " is closed to the public. "
               . "Closed meetings are for anyone whose personal life is or has been deeply "
               . "affected by close contact with a problem drinker. If you are interested "
               . "in Al-Anon/Alateen or wish to observe as a student or professional, we "
               . "welcome you to attend one of our meetings designated as ‘open’.";
  return $desc_str;
}
////////////////////////////////////////////////////////////////////////////////
add_action('init', 'afgd5sh_shortcodes_init');
function afgd5sh_shortcodes_init()
{
  add_shortcode('url','afgd5sh_url_shortcode');
  add_shortcode('addtocalbutton','afgd5sh_addtocalbutton');
}
 
////////////////////////////////////////////////////////////////////////////////
register_activation_hook( __FILE__, 'afgd5sh_install' );
function afgd5sh_install() {
    // Clear the permalinks
    flush_rewrite_rules();
}

////////////////////////////////////////////////////////////////////////////////
register_deactivation_hook( __FILE__, 'afgd5sh_deactivation' );
function afgd5sh_deactivation() {
    // Clear the permalinks
    flush_rewrite_rules();
}


