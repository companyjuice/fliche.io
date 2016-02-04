<?php
/**  
 * Video more page controller file.
 *
 * @category   FishFlicks
 * @package    Fliche Video Gallery
 * @version    0.7.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Including FlicheVideomore model file to get database information. */
include_once ($frontModelPath . 'videomore.php'); 
/**
 * Check FlicheMoreController class is exists
 */
if ( !class_exists ( 'FlicheMoreController' ) ) {
  /**
   * Class is used to get data for video more pages
   *
   * @author user
   */
  class FlicheMoreController extends FlicheMore {
      /**
       * Function for the home thumb data.
       *
       * @param unknown $thumImageorder          
       * @param unknown $where          
       * @param unknown $pagenum          
       * @param unknown $dataLimit          
       * @return type <mixed int>
       */
      function home_thumbdata($thumImageorder, $where, $pagenum, $dataLimit) {
          /** Return more page thumb data with help of model */
          return $this->get_thumdata ( $thumImageorder, $where, $pagenum, $dataLimit );
      }
      
      /**
       * Function to get categories thumb data from plugin herlper
       */
      function home_categoriesthumbdata($pagenum, $dataLimit) {
        /** Set start and limit to fetch category thumbs */
        $pagenum  = isset ( $pagenum ) ? absint ( $pagenum ) : 1;
        /** Set pagination for videos more page */
        $offset   = ($pagenum - 1) * $dataLimit;
        /** Set the liimit */
        $limit    = $offset . ',' . $dataLimit;
        /** Call helper function to fetch playlist details */
        return getPlaylist (' playlist_order ASC ' , $limit);
      }
  
      /**
       * Function to get search page thumb data
       *
       * @param unknown $thumImageorder          
       * @param unknown $pagenum          
       * @param unknown $dataLimit          
       */
      function home_searchthumbdata($thumImageorder, $pagenum, $dataLimit) {
          /** Return search page thumb data with help of model */
          return $this->get_searchthumbdata ( $thumImageorder, $pagenum, $dataLimit );
      }
      /**
       * Function to get video count
       *
       * @param unknown $playid          
       * @param unknown $userid          
       * @param unknown $thumImageorder          
       * @param unknown $where          
       */
      function countof_videos($playid, $userid, $thumImageorder, $where) {
          /** Return count of videos with help of model */
          return $this->get_countof_videos ( $playid, $userid, $thumImageorder, $where );
      }
      
      /**
       * Function to get count for search videos
       *
       * @return type int
       */
      function countof_videosearch($thumImageorder) {
          /** Return video search count with help of model */
          return $this->get_countof_videosearch ( $thumImageorder );
      }
  /** FlicheMore class ends */
  }
/** Checking FlicheMore if ends */
} else {
  /** Else FlicheMore already exists */
  echo 'class FlicheMore already exists';
}
/** Including FlicheVideomore view files to display data */
include_once ($frontViewPath . 'videomore.php'); 
include_once ($frontViewPath . 'videomorepage.php');
?>