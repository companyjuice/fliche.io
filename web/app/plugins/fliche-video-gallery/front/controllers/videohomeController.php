<?php
/** 
 * Video home page controller file.
 *
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Including FlicheVideo model file to get database information. */
include_once ($frontModelPath . 'videohome.php');

/** Check FlicheVideoController class is exists */
if ( !class_exists ( 'FlicheVideoController' ) ) {
  /**
   * Class is used to get data for video home page
   * 
   * @author user
   */
  class FlicheVideoController extends FlicheVideo {         
      /**
       * Function get playlists video
       * 
       * @param unknown $thumImageorder          
       * @param unknown $dataLimit          
       */
      function home_catthumbdata($thumImageorder, $dataLimit) {
          /** Return home category thumb data with help of model */
          return $this->get_home_catthumbdata ( $thumImageorder, $dataLimit );
      }
      
      /**
       * Function get home thumb data.
       * 
       * @param unknown $thumImageorder          
       * @param unknown $where          
       * @param unknown $dataLimit          
       */
      function home_thumbdata($thumImageorder, $where, $dataLimit) {
          /** Return home thumb data with help of model */
          return $this->get_thumdata ( $thumImageorder, $where, $dataLimit );
      }
  
      /**
       * Get count of home thumb data
       * 
       * @param unknown $thumImageorder          
       * @param unknown $where          
       * @return number
       */
      function countof_home_thumbdata($thumImageorder, $where) {
          /** Return count of home thumb data with help of model */
          return $this->get_countof_thumdata ( $thumImageorder, $where );
      }

      /**
       * Function player related video.
       * 
       * @param unknown $getVid          
       * @param unknown $thumImageorder          
       * @param unknown $where          
       * @param unknown $dataLimit          
       */
      function home_playxmldata($getVid, $thumImageorder, $where, $dataLimit) {
          /** Return home play xml data with help of model */
          return $this->get_playxmldata ( $getVid, $thumImageorder, $where, $dataLimit );
      }
      
      /**
       * Function get home categories thumb data 
       * from plugin helper
       * 
       * @param unknown $pagenum          
       * @param unknown $dataLimit          
       * @return Ambigous <type, mixed, NULL, multitype:, multitype:multitype: , multitype:Ambigous <multitype:, NULL> >
       */
      function home_categoriesthumbdata($pagenum, $dataLimit) {
          /** Set start and limit to get category thumbs */
          $pagenum  = isset ( $pagenum ) ? absint ( $pagenum ) : 1;
          /** Set the pagination limit */
          $offset   = ($pagenum - 1) * $dataLimit;
          $limit    = $offset . ',' . $dataLimit;
          /** Return home categories data with help of model */
          return getPlaylist (' playlist_order ASC ' , $limit);
      }
      
      /**
       * Function to get get featured video 
       */
      function home_featuredvideodata() {
         /** Return home featured data with help of model */
          return $this->get_featuredvideodata ();
      }
      
      /**
       * Function to get featured video
       */
      function home_featuredvideodata_banner() {
          /** Return home featured data for videostream banner with help of model */
          return $this->get_featuredvideodata_banner ();
      }
      
      /**
       * Function video detail /info under video player
       * 
       * @param unknown $vid          
       */
      function video_detail($vid) {
          /** Return particular video details with help of model */
          return $this->get_video_detail ( $vid );
      }
  /** flicheVideo class ends */
  }
  /** Checking flicheVideo class exist if ends */
} else {
    /** Else display message */
    echo 'class flicheVideo already exists';
}
/** Including FlicheVideo view file to display data */
include_once ($frontViewPath . 'videohome.php'); 
?>