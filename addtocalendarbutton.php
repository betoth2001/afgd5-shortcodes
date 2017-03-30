<?php
/**
 * The template for displaying addToCalendar button.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;
?>
  <span class="addtocalendar atc-style-icon atc-style-menu-wb" id="atc_icon_blue1">
    <a class="atcb-link">
      <img src="https://addtocalendar.com//static/cal-icon/cal-red-03.png" width="<?php echo ( $afgd5sh_addtocal_atts['width'] ); ?>">
    </a>
    <var class="atc_event">
      <var class="atc_date_start"><? echo ( $afgd5sh_addtocal_atts['start'] ); ?></var>
      <var class="atc_date_end"><? echo ( $afgd5sh_addtocal_atts['end'] ); ?></var>
      <var class="atc_timezone"><? echo ( $afgd5sh_addtocal_atts['timezone'] ); ?></var>
      <var class="atc_title"><? echo ( $afgd5sh_addtocal_atts['title'] ); ?></var>
      <var class="atc_description"><? echo ( $afgd5sh_addtocal_atts['description'] ); ?></var>
      <var class="atc_location"><? echo( $afgd5sh_addtocal_atts['location'] ); ?></var>
      <var class="atc_organizer"><? echo ( $afgd5sh_addtocal_atts['organizer'] ); ?></var>
      <var class="atc_organizer_email"><? echo ( $afgd5sh_addtocal_atts['organizer_email'] ); ?></var>
    </var>
  </span>
