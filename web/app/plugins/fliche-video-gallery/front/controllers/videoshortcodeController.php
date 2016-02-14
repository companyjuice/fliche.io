<?php
/**  
 * Video detail and short tags controller file.
 *
 * @category   FishFlicks
 * @package    Fliche Video Gallery
 * @version    0.8.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Including FlicheVideoShortcode model file to get database information. */
include_once ($frontModelPath . 'videoshortcode.php');
/** Check FlicheVideoShortcodeController class is exists */
if ( !class_exists ( 'FlicheVideoShortcodeController' ) ) {
    /**
     * Class is used to get data for video home page
     *
     * @author user
     */
    class FlicheVideoShortcodeController extends FlicheShortcode {
        /**
         * Function get the video detail.
         *
         * @param   int    $vid          
         * @return  mixed  videodetails
         */
        function short_video_detail($vid, $number_related_video) {
            /** Return paricular video details with help of model */
            return $this->getshort_video_detail ( $vid, $number_related_video);
        }
        
        /**
         * Function to get videos playlist details.
         *
         * @param   int     $vid  
         * @return  mixed   playlistdetails   
         */
        function playlist_detail($vid) {
            /** Return paricular playlist details with help of model */
            return $this->get_playlist_detail ( $vid );
        }
        
        /**
         * Function to get related videos for details page 
         * 
         * @param unknown $vid
         * @param unknown $playlist_id
         * @param unknown $Limit
         * @return object
         */
        function relatedVideosDetails ( $vid, $playlist_id, $Limit ) {
          /** Return related video details with help of model */
          return $this->getRelatedVideosDetails ( $vid, $playlist_id, $Limit );
        }
        
        /**
         * Function  google adsense detail for  video.
         * 
         * @param unknown $vid
         * @return object
         */
        public function get_video_google_adsense_details($vid){
          return $this->get_googleads_detail($vid);
        }
    /** FlicheVideo shortcode class ends */
    }
/** Checking FlicheVideo class if ends */
} else {
    /** Else display FlicheVideoShortcodeController exists message */ 
    echo 'Class FlicheVideoShortcodeController already exists';
}
/** Including FlicheVideo shortcode view files. */
include_once ($frontViewPath . 'videoshortcode.php'); 
include_once ($frontViewPath . 'videodetailpage.php');
?>