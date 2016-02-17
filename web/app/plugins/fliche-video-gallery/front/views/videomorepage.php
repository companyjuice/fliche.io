<?php
/**  
 * Video more pages view file.
 *
 * @category   FishFlicks
 * @package    Fliche Video Gallery
 * @version    0.8.1
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Check FlicheMorePageView class is exist */
if ( !class_exists ( 'FlicheMorePageView' )) {
  /**
   * FlicheMorePageView class is used to display home page player and thumbnails
   *
   * @author user
   */
  class FlicheMorePageView extends MoreSearchView {

    /**
     * Function to display
     * recent, feature, category, popular,
     * random, user, search pages
     *
     * @param $type
     * @param $arguments
     */
    function video_more_pages( $type, $arguments ) {

      $TypeOFvideos = $CountOFVideos = $typename = $type_name = $morePage = $dataLimit = $div = $pagenum ='';
      
      /** Check homeVideo function is exists */
      if ( !function_exists ( 'homeVideo' ) ) {
        
        if ( $type == 'search' 
          || $type == 'categories' 
          || $type == '' ){
          /** Get details for search and videomore page */
          $this->getSearchCategoryVideos ( $type ) ;
        } 
        else {
          /** Get details for other more pages */ 
          $moreResult = $this->getTypeOfVideos ( $type, $arguments ) ;
        }
        
        if (  isset( $moreResult ) 
          && !empty( $moreResult ) ){
          $TypeOFvideos   = $moreResult [0];
          $CountOFVideos  = $moreResult [1];
          $typename       = $moreResult [2];
          $type_name      = $moreResult [3];
          $morePage       = $moreResult [4];
          $dataLimit      = $moreResult [5];
        }


        

        /* -||- */
        $moreFeaturedImages = array(
          '25' => 'http://fishflicks.vidflix.co/vidcat_adventure_angler_841059f3-f86e-498f.jpg',
          '24' => 'http://fishflicks.vidflix.co/vidcat_adventurebound_7bd27c2e-fd3d-4c99.jpg',
          '00' => 'http://fishflicks.vidflix.co/vidcat_escape_with_et_efb332ab-28bc-4adc.jpg',
          '22' => 'http://fishflicks.vidflix.co/vidcat_fishing_downunder_14d9ba1c-87a4-4173.jpg',
          '23' => 'http://fishflicks.vidflix.co/vidcat_fishingedge_3adb6a5b-63d7-42c6.jpg',
          '21' => 'http://fishflicks.vidflix.co/vidcat_hooklinesinker_e5d1bb15-9d22-4ab5.jpg',
          '3'  => 'http://fishflicks.vidflix.co/vidcat_markberg_5d776560-a7e8-463b.jpg',
          '26' => 'http://fishflicks.vidflix.co/vidcat_reel_action_d8e16125-9ce9-4852.jpg',
          '00' => 'http://fishflicks.vidflix.co/vidcat_rexhunt_67be20d1-e07a-490d.jpg'
        );
        /* -||- */
        /* -||- */
        $moreFeaturedImages = $moreFeaturedImages;
        

        /*
        #if ( $type == 'cat' ){
          echo '<pre>';
          echo '-||- videomorepage.php <br>';
          echo '--------------------<br>';
          echo '-||- $type <br>';
          var_dump($type);
          echo '--------------------<br>';
          echo '-||- $arguments <br>';
          var_dump($arguments);
          echo '--------------------<br>';
          echo '-||- $moreResult[0] = $TypeOFvideos <br>';
          var_dump($moreResult[0][0]);
          echo '--------------------<br>';
          echo '-||- $moreResult[1] = $CountOFVideos <br>';
          var_dump($moreResult[1]);
          echo '--------------------<br>';
          echo '-||- $moreResult[2] = $typename <br>';
          var_dump($moreResult[2]);
          echo '--------------------<br>';
          echo '-||- $moreResult[3] = $type_name <br>';
          var_dump($moreResult[3]);
          echo '--------------------<br>';
          echo '-||- $moreResult[4] = $morePage <br>';
          var_dump($moreResult[4]);
          echo '--------------------<br>';
          echo '-||- $moreResult[5] = $dataLimit <br>';
          var_dump($moreResult[5]);
          echo '--------------------<br>';
          echo '</pre>';
        #}
        */

        
        $div = '
          <style type="text/css"> 
            .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } 
          </style>
          <div class="video_wrapper" id="' . $type_name . '_video">
        ';

/* -||- */

if ( !isset($arguments["image"]) || $arguments["image"] == 'on' || $arguments["image"] == '1' ){
  $div        .= $this->morePageFeaturedImage ( $type_name, $typename );
}


        /** Call function to display more video page title */ 
        if ( !isset($arguments["title"]) || $arguments["title"] == 'on' || $arguments["title"] == '1' ){
          $div        .= $this->morePageTitle ( $type_name, $typename );
        }
        
        
        if (! empty ( $TypeOFvideos )) {
          $pagenum    = absint ( $this->_pagenum ) ? absint ( $this->_pagenum ) : 1;
          $videolist  = 0;

          foreach ( $TypeOFvideos as $video ) {

            $vidF [$videolist]      = $video->vid;
            $nameF [$videolist]     = $video->name;
            $hitcount [$videolist]  = $video->hitcount;
            $ratecount [$videolist] = $video->ratecount;
            $rate [$videolist]      = $video->rate;
            $duration [$videolist]  = $video->duration;
            $file_type              = $video->file_type;
            $guid [$videolist]      = get_video_permalink ( $video->slug );
            $imageFea [$videolist]  = getImagesValue ($video->image, $file_type, $video->amazon_buckets, '');
            
            if (! empty ( $this->_playid )) {
              $fetched [$videolist]       = $video->playlist_name;
              $fetched_pslug [$videolist] = $video->playlist_slugname;
              $playlist_id [$videolist]   = absint ( $this->_playid );
            } 
            else {
              
              $getPlaylist = $this->_wpdb->get_row ( 'SELECT playlist_id FROM ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play WHERE media_id="' . intval ( $vidF [$videolist] ) . '"' );

              if (isset ( $getPlaylist->playlist_id )) {
                $playlist_id [$videolist]   = $getPlaylist->playlist_id;
                $fetPlay [$videolist]       = playlistDetails ($playlist_id [$videolist]);
                $fetched [$videolist]       = $fetPlay [$videolist]->playlist_name;
                $fetched_pslug [$videolist] = $fetPlay [$videolist]->playlist_slugname;
              }
            }
            $videolist ++;
          }


          $div .= '<div> <ul class="video-block-container">';

          /** Display thumbnails starts */
          for($videolist = 0; $videolist < count ( $TypeOFvideos ); $videolist ++) {
            
            if (($videolist % $this->_colF) == 0 && $videolist != 0) {
              $div    .= '</ul><div class="clear"></div><ul class="video-block-container">';
            }  
            
            /** Display thumb and duration */
            $div      .= '<li class="video-block"> <div  class="video-thumbimg"><a href="' . $guid [$videolist] . '" title="' . $nameF [$videolist] . '"><img src="' . $imageFea [$videolist] . '" alt="' . $nameF [$videolist] . '" class="imgHome" title="' . $nameF [$videolist] . '" /></a>';
            
            if (!empty($duration [$videolist]) && $duration [$videolist] != '0:00') {
              $div    .= '<span class="video_duration">' . $duration [$videolist] . '</span>';
            }
            
            /** End Display thumb and duration */
            $div      .= '</div>';


            /** Display video title */
            $div      .= '<div class="vid_info">
              <a href="' . $guid [$videolist] . '" title="' . $nameF [$videolist] . '" class="videoHname">
              <span>' . limitTitle ( $nameF [$videolist] ) . '</span>
              </a>
            ';
            


            /* -||- */
            /** Display playlist name starts here */
            if (! empty ( $fetched [$videolist] ) && ($this->_settingsData->categorydisplay == 1)) {
              $playlist_url   = get_playlist_permalink ( $this->_mPageid, $playlist_id [$videolist], $fetched_pslug [$videolist] );
              $div    .= '<a  class="playlistName"   href="' . $playlist_url . '"><span>' . $fetched [$videolist] . '</span></a>';
            }
            /* -||- */



            /** Rating starts here */
            if ($this->_settingsData->ratingscontrol == 1) {
              $div  .= getRatingValue ($ratecount [$videolist],$rate [$videolist],'');
            }
            
            /** Views starts here */
            if ($this->_settingsData->view_visible == 1) {
              $div .= displayViews ($hitcount [$videolist]);
            }
            
            $div       .= '</div></li>';
            /** Foreach ends */
          }
          $div         .= '</ul>';
          $div         .= '</div>';
          $div         .= '<div class="clear"></div>';

        } else {
          if ($type != 'search' && $type != 'categories' && $type != '') {
            if ($typename == 'Category') {
              /** Display no videos link for category page */
              $div     .= __ ( 'No', FLICHE_VGALLERY ) . '&nbsp;' . __ ( 'Videos', FLICHE_VGALLERY ) . '&nbsp;' . __ ( 'Under&nbsp;this&nbsp;Category', FLICHE_VGALLERY );
            } else {
              /** Display no videos link for other more pages */
              $div     .= __ ( 'No', FLICHE_VGALLERY ) . '&nbsp;' . $typename . '&nbsp;' . __ ( 'Videos', FLICHE_VGALLERY );
            }
          }
        }
        /** END video_wrapper */
        $div           .= '</div>';

        /** Pagination starts
         * Call helper function to get pagination values for more pages */
        if($dataLimit != 0) {
          $div .= paginateLinks ($CountOFVideos, $dataLimit, $pagenum, '', '' );
        }

        /** RETURN html string */
        return $div;
      }
    }


    /**
     * Function to display more videos page title 
     * 
     * @param unknown $type_name
     * @param unknown $typename
     * @return string
     */
    function morePageTitle ( $type_name, $typename ) {
      $div = '';
      /** Check type name and get title for the more pages */
      switch( $type_name ) {
        case 'Category':
          /** Get playlist name based on play id */
          $playlist_name  = get_playlist_name ( intval ( absint ( $this->_playid ) ) );
          /** Display playlist title in category page*/
          $div            .= '<h2 class="titleouter" >' . $playlist_name . ' </h2>';
          break;
        case 'User':
          /** Get user name based on user id */
          $user_name      = get_user_name ( intval ( $this->_userid ) );
          /** Display user name in user videos page */ 
          $div            .= '<h2 >' . $user_name . ' </h2>';
          break;
        case 'popular': case 'recent': case 'random': case 'featured':
          /** Get current more type and display as page title */ 
          $div            .= '<h2 >' . $typename . ' ' . __ ( 'Videos', FLICHE_VGALLERY ) . ' </h2>';
          break;
        default:
          break;
      }
      /** Return more page title */ 
      return $div;
    }

    /**
     * -||-
     * Function to get Category featured image
     * -||-
     * @param string $type_name
     * @param string $typename
     * @return string
     */
    function morePageFeaturedImage ( $type_name, $typename ) {
      $div = '';
      /** Check type name and get title for the more pages */
      switch( $type_name ) {
        case 'Category':
          
          $playlist_image = get_playlist_image ( intval ( absint ( $this->_playid ) ) );

          #echo '<pre>';
          #var_dump($playlist_image);
          #echo '</pre>';

          $div            .= '<img src="' . $playlist_image . '" title="Featured Image">';
          break;

        default:
          break;
      }
      /** Return more page title */ 
      return $div;
    }


  /** FlicheMorePageView class ends */
  }
/** Check FlicheMorePageView class is exist if ends */
} else {
  /** Display message FlicheMorePageView exists */
  echo 'class FlicheMorePageView already exists';
}
?>