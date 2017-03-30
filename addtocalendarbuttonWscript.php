<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;
?>
  <script type="text/javascript">(function () {
    if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
    if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
      var d = document, s = d.createElement("script"), g = "getElementsByTagName";
      s.type = "text/javascript";s.charset = "UTF-8";s.async = true;
      s.src = ("https:" == window.location.protocol ? "https" : "http")+"://addtocalendar.com/atc/1.5/atc.min.js";
      var h = d[g]("body")[0];h.appendChild(s); }})();
  </script>

  <!-- 3. Place event data -->
  <? //var_dump($afgd5sh_addtocal_atts); ?>
  <span class="addtocalendar atc-style-icon atc-style-menu-wb" id="atc_icon_blue1">
    <a class="atcb-link">
      <img src="https://addtocalendar.com//static/cal-icon/cal-red-03.png" width="20">
    </a>
    <var class="atc_event">
      <var class="atc_date_start"><? echo( get_field('start'));
      //echo ( $afgd5sh_addtocal_atts['start'] ); 
      ?></var>
      <var class="atc_date_end"><? 
        $value = get_field('end');
        if( $value ) {
          echo( $value );
        } else {
          echo( date( 'Y-m-d H:i:s',strtotime(get_field('start'))+60*60) ); 
        }
        //echo ( $afgd5sh_addtocal_atts['end'] ); ?>
      </var>
      <var class="atc_timezone"><? echo ( $afgd5sh_addtocal_atts['timezone'] ); ?></var>
      <var class="atc_title"><? echo ( $afgd5sh_addtocal_atts['title'] ); ?></var>
      <var class="atc_description"><? echo ( $afgd5sh_addtocal_atts['description'] ); ?></var>
      <var class="atc_location"><? echo( $afgd5sh_addtocal_atts['location'] ); ?></var>
      <var class="atc_organizer"><? echo ( $afgd5sh_addtocal_atts['organizer'] ); ?></var>
      <var class="atc_organizer_email"><? echo ( $afgd5sh_addtocal_atts['organizer_email'] ); ?></var>
    </var>
  </span>
