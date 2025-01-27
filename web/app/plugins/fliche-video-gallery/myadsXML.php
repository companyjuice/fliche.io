<?php
/**
 * AdsXML files
 * 
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
/** Used to include plugin configuration */
require_once (dirname ( __FILE__ ) . '/fliche-config.php');

/** Get pre / post roll ad details from db */
$themediafiles  = getVideoAdDetails ( 'prepost', '' );
/** Set video ads clicks URL */
$clicksURL      = get_site_url () . '/wp-admin/admin-ajax.php?action=impressionclicks&click=click';
/** Set video ads Impression URL */
$impressionURL  = get_site_url () . '/wp-admin/admin-ajax.php?action=impressionclicks&click=impression';
/** Set XML header for pre / post roll ads */
xmlHeader ();
/**
 * Set xml version and encoding for ADs xml
 * Pre/ Post roll Ad XML starts here
 */
echo '<?xml version="1.0" encoding="utf-8"?>
    <ads random="false">';
/**
 * Check pre/ post roll ad exist
 * If yes, then display ads details  
 */
if (count ( $themediafiles ) > 0) {
  /** Looping through video ads detail */
  foreach ( $themediafiles as $rows ) { 
      /** Display Ads XML content */
      echo '<ad id="' . $rows->ads_id . '" url="' . $rows->file_path . '" targeturl="' . $rows->targeturl . '" 
          clickurl="' . $clicksURL . '" impressionurl="' . $impressionURL . '">
          <![CDATA[' . $rows->description . ']]>
          </ad>';    
  }
}
/** Video Ads xml ends here */
echo '</ads>';
?>