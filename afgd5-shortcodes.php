<?php
/*
Plugin Name: AFG D5 Shortcodes
Description: Adds useful shortcodes.
Version:     0.0.1
Author:      BET <bet6556@gmail.com>
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Function Prefix: afgd5sh_
*/

defined( 'ABSPATH' ) or die( 'AFGD5sh No script please!' );

add_action('wp_head', 'google_devoloper_meta');
function google_devoloper_meta(){
  /* Place this in page header for site verification by Google
    https://www.google.com/webmasters/
  */
  echo('<meta name="google-site-verification" content="vWHAnHz2rzv-LxQa2z0b4mkd7ajrNRA9vxPcll0Khjo" />');
}

add_shortcode('open','afgd5sh_open_meeting_footer_text');
function afgd5sh_open_meeting_footer_text() {
#  return "OPEN: This meeting welcomes everyone including those who are interested in Al-Anon/Alateen or wish to observe as a student or professional.";
  return "OPEN: This meeting is open to the public and welcomes everyone interested in Al-Anon/Alateen, including those who wish to observe as a student or professional.";
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

/**
* Get site url for links
*
* @author Bryan T <bet6556@gmail.com> 
*/

add_shortcode('url','afgd5sh_url_shortcode');
function afgd5sh_url_shortcode() {
	return get_bloginfo('url').'/';
}


add_shortcode('afgd5_plist','afgd5sh_shortcode_position_list');
function afgd5sh_shortcode_position_list($atts = [], $content = '', $tag = '') {
  if( '' === $content ) return;
  $input=explode(';',$content,999);
  $count=0;
  $plist=[];
  while( $count < sizeof($input) ){
    array_push( $plist,[
      'position' => trim($input[$count++]),
      'name' => trim($input[$count++]),
      'email' => trim($input[$count++]),
      ]);
  }

  ob_start(); //capture output to buffer
  render_position_list($plist);
  return ob_get_clean();
}

function render_position_list($plist){
  $odd=false;
  foreach( $plist as $person ){
    if( $odd ){
      $odd=false;
    } else {
      $odd=true;
    }
  ?><div class="afgd5_contact_entry <?php if($odd) echo('row-odd'); ?>"><div class="position-title">
 <?php echo($person['position']); ?></div><div class="person"><div class="person-name">
<?php echo($person['name']); ?>
</div><div class="person-email">
<a href="mailto:<?php echo($person['email']); ?>"><?php echo($person['email']);//echo(str_replace('@','@<wbr>',$person['email'])); ?></a></div></div></div>
<?php
  }
  return;
}


function afgd5sh_install() {
    // Clear the permalinks
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'afgd5sh_install' );

function afgd5sh_deactivation() {
    // Clear the permalinks
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'afgd5sh_deactivation' );

