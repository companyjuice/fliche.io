<?php
/**  
 * Video "more" view file
 *
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Check FlicheMoreView class is exist */
if ( !class_exists ( 'FlicheMoreView' )) {
  /**
   * FlicheMoreView class is used to display home page player and thumbnails
   *
   * @author user
   */
  class FlicheMoreView extends FlicheMoreController {   
    /**
     * FlicheMoreView constructor starts
     */
    public function __construct() {
        parent::__construct ();
        global $wp_query;
        /**
         * Call plugin helper function
         * to get plugin settings
         * and more page id 
         * for more pages
         */
        $this->_settingsData          = getPluginSettings (); 
        $this->_player_colors         = unserialize ( $this->_settingsData->player_colors );
        $this->_recent_video_order    = $this->_player_colors ['recentvideo_order'];
        $this->_mPageid       = morePageID ();
        /**
         * Get video id , playlist id,
         * page number and userid
         * from request URL
         */
        $this->_vId           = absint ( filter_input ( INPUT_GET, 'vid' ) ); 
        $this->_pagenum       = absint ( filter_input ( INPUT_GET, 'pagenum' ) ); 
        $this->_playid        = &$wp_query->query_vars ['playid'];
        $this->_userid        = &$wp_query->query_vars ['userid'];
        /** Get search keyword */ 
        $searchVal            = str_replace ( ' ', '%20', __ ( 'Video Search ...', FLICHE_VGALLERY ) );
        if(!empty($wp_query->query_vars) && isset($wp_query->query_vars ['video_search'])) {
            $video_search         = $wp_query->query_vars ['video_search'];
            if ($video_search !== $searchVal) {
                $video_search       = $video_search;
            } else {
                $video_search       = '';
            }
            /** Get serach value */
            $this->_video_search  = stripslashes ( urldecode ( $video_search ) );
        }
        /**
         * Get row, column for more
         * videos from settings
         */        
        $this->_rowF          = $this->_settingsData->rowMore;
        $this->_colF          = $this->_settingsData->colMore; 
        $this->_perMore       = $this->_rowF * $this->_colF;
        /**
         * Get row, column for category 
         * videos from settings
         * and calculate total values
         */
        $this->_colCat        = $this->_settingsData->colCat; 
        $this->_rowCat        = $this->_settingsData->rowCat; 
        $this->_perCat        = $this->_colCat * $this->_rowCat;
        /**
         * Get plugin images directory path
         * and upload path 
         * from plugin helper
         */
        $this->_imagePath     = getImagesDirURL();
    }   
    
    /**
     * Function to get videos for 
     * recent, feature, random, popular,
     * user and category page     
     *
     * @param   $type     
     */
    function getTypeOfVideos ( $type, $arguments ) {

        $TypeOFvideos = $CountOFVideos = $typename = $type_name = $morePage = $where = '';
        
        /** Check if short code is uesed */
        if (isset ( $arguments ['rows'] ) && isset ( $arguments ['cols'] )) {
          /** Get row, column value from shortcode */
            $dataLimit    = $arguments ['rows'] * $arguments ['cols'];
        } else if ( $type == 'cat') {
          /** Check the page is category page */
          /** Get datalimit for category page from settings */ 
            $dataLimit      = $this->_perCat;
        } else {
          /** Get data limit for more pages from settings */
            $dataLimit      = $this->_perMore;
        }
        
        /** Get recent video order */
        $default_order  = getVideoOrder ( $this->_recent_video_order );
        

        switch ($type) {
            case 'popular' :
                $thumImageorder = ' w.hitcount DESC ';
                $typename       = __ ( 'Popular', FLICHE_VGALLERY );
                $type_name      = 'popular';
                $morePage       = '&more=pop';
                $TypeOFvideos   = $this->home_thumbdata ( $thumImageorder, $where, $this->_pagenum, $dataLimit );
                $CountOFVideos  = $this->countof_videos ( '', '', $thumImageorder, $where );
                break;
            case 'recent' :
                $typename       = __ ( 'Recent', FLICHE_VGALLERY );
                $thumImageorder = ' w.vid DESC ';
                $type_name      = 'recent';
                $morePage       = '&more=rec';
                $TypeOFvideos   = $this->home_thumbdata ( $thumImageorder, $where, $this->_pagenum, $dataLimit );
                $CountOFVideos  = $this->countof_videos ( '', '', $thumImageorder, $where );
                break;
            case 'random' :
                $thumImageorder = ' rand() ';                
                $type_name      = 'random';
                $typename       = __ ( 'Random', FLICHE_VGALLERY );
                $morePage       = '&more=rand';
                $TypeOFvideos   = $this->home_thumbdata ( $thumImageorder, $where, $this->_pagenum, $dataLimit );
                $CountOFVideos  = $this->countof_videos ( '', '', $thumImageorder, $where );
                break;
            case 'featured' :
                $where          = 'AND w.featured=1';
                $thumImageorder = getVideoOrder ( $this->_recent_video_order );
                $typename       = __ ( 'Featured', FLICHE_VGALLERY );
                $morePage       = '&more=fea';
                $type_name      = 'featured';
                $TypeOFvideos   = $this->home_thumbdata ( $thumImageorder, $where, $this->_pagenum, $dataLimit );
                $CountOFVideos  = $this->countof_videos ( '', '', $thumImageorder, $where );
                break;
        

            case 'cat' : 
                $thumImageorder = absint ( $this->_playid );
                $typename       = __ ('Category', FLICHE_VGALLERY );
                $type_name      = 'Category';
                $morePage       = '&playid=' . $thumImageorder;
        
                /* -||- VIDEOS QUERY -||- */
                $TypeOFvideos   = $this->home_catthumbdata ( $thumImageorder, $this->_pagenum, $dataLimit, $default_order );
        

                $CountOFVideos  = $this->countof_videos ( absint ( $this->_playid ), '', $thumImageorder, $where );
                break;
        

            case 'user' : 
                $thumImageorder = $this->_userid;            
                $typename       = __ ('User', FLICHE_VGALLERY );
                $type_name      = 'User';
                $morePage       = '&userid=' . $thumImageorder;
                $TypeOFvideos   = $this->home_userthumbdata ( $thumImageorder, $this->_pagenum, $dataLimit );
                $CountOFVideos  = $this->countof_videos ( '', $this->_userid, $thumImageorder, $where );
                break;
            default: break;
        }

        /** Return video details for more pages */
        return array ( $TypeOFvideos, $CountOFVideos, $typename, $type_name, $morePage, $dataLimit );
    }
}
/** Check FlicheMoreView class is exist if ends */
} else {
  /** Display flicheMore exists message */
  echo 'class flicheMore already exists';
}
    
/** This class is used to get category and search results 
 * Display the category page results
 */ 
class MoreCategoryView extends FlicheMoreView { 
    /**
     * Function to get videos for search 
     * and more videos page
     *
     * @param   $type
     */
    function getSearchCategoryVideos ( $type ) {
       /** Get current page number for search, video more pages */
        $pagenum        = $this->_pagenum;
        if (empty ( $pagenum )) {
          $pagenum      = 1;
        }
        /** Check whether the page is videomore page or search page */
        switch ($type) {   
          case 'search' :
            $dataLimit      = $this->_perMore;
            $thumImageorder   = str_replace ( '%20', ' ', $this->_video_search );
            if ($this->_video_search == __ ( 'Video Search ...', FLICHE_VGALLERY )) {
                $thumImageorder = '';
            }
            $TypeOfSearchvideos   = $this->home_searchthumbdata ( $thumImageorder, $pagenum, $dataLimit );
            $CountOfSearchVideos  = $this->countof_videosearch ( $thumImageorder );
            break;
          case 'categories' :
          default :
            $dataLimit      = $this->_perCat;        
            $default_order  = getVideoOrder ( $this->_recent_video_order );            
            $TypeOfCatvideos   = $this->home_categoriesthumbdata ( $pagenum, $dataLimit );
            $CountOfCatVideos  = getPlaylistCount ();
            break;
        }
        /** Check current page is search page */
        if ($type == 'search') {
          /** Call function to display search results */ 
            return $this->searchlist ( $thumImageorder, $CountOfSearchVideos, $TypeOfSearchvideos, $this->_pagenum, $dataLimit );
        } else if ($type == 'categories') {
          /** Call function to display videomore results */
            return $this->categorylist ( $CountOfCatVideos, $TypeOfCatvideos, $this->_pagenum, $dataLimit, $default_order );
        } else {
            return $this->categorylist ( $CountOfCatVideos, $TypeOfCatvideos, $this->_pagenum, $dataLimit, $default_order );
        }
    }
      
    /**
     * Function to display category thumbs 
     * 
     * @param unknown $CountOfCatVideos
     * @param unknown $TypeOfCatvideos
     * @param unknown $pagenum
     * @param unknown $dataLimit
     * @param unknown $default_order
     * @return string
     */
    function categorylist($CountOfCatVideos, $TypeOfCatvideos, $pagenum, $dataLimit, $default_order) {
        global $wpdb;
        $div        = '';
        /** Calculating page number for category videos */
        $pagenum    = absint ( $pagenum ) ? absint ( $pagenum ) : 1; 
        $div        .= '<style> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
        foreach ( $TypeOfCatvideos as $catList ) { 
            /** Fetch videos for every category
             * Get more page id */ 
            $playLists      = getCatVideos ($catList->pid, $dataLimit,  $default_order);
            $moreName       = morePageID ();
            $playlistCount  = count ( $playLists );
            #$div    .= '<div class="titleouter"><h4 class="clear more_title">' . $catList->playlist_name . '</h4></div>';
            $div    .= '<div class="titleouter"><h3 class="more_title">' . $catList->playlist_name . '</h3></div>';
            if (! empty ( $playlistCount )) {
                $i      = 0;
                $inc    = 1;
                $div    .= '<ul class="video-block-container">';
                foreach ( $playLists as $playList ) {
                    $duration     = $playList->duration;
                    $file_type    = $playList->file_type; 
                    $guid         = get_video_permalink ( $playList->slug );              
                    $imageFea     = getImagesValue ($playList->image, $file_type, $playList->amazon_buckets, '');
                    $playlist_more_link = get_playlist_permalink ( $moreName, $catList->pid, $catList->playlist_slugname );
                    /** To display the category videos thumb image and video duration  */
                    $div    .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '" title="' . $playList->name . '" ><img src="' . $imageFea . '" alt="" class="imgHome" title="" /></a>';

                 if (!empty($duration) && $duration != '0:00') {
                        $div  .= '<span class="video_duration">' . $duration . '</span>';
                    }
                    /** To display playlist name as linkable */
                    $div    .= '</div><div class="vid_info"><h5><a href="' . $guid . '" class="videoHname" title="' . $playList->name . '">' . limitTitle ( $playList->name ) . '</a></h5>';                  
                    if ($this->_settingsData->categorydisplay == 1) {
                        $div  .= '<a class="playlistName" href="' . $playlist_more_link . '"><span>' . $catList->playlist_name . '</span></a>';
                    }
                    if ($this->_settingsData->ratingscontrol == 1) {
                        /** Rating starts here for category videos */
                        $div  .= getRatingValue ( $playList->rate, $playList->ratecount, '' ); 
                    }
                    
                    /** Views starts here for category videos */ 
                    if ($this->_settingsData->view_visible == 1) {
                        $div  .= displayViews($playList->hitcount); 
                    }                    
                    $div   .= '</div></li>';
                    if ($i > ($this->_perCat - 2)) {
                      break;
                    } else {
                        $i = $i + 1;
                    }
                    if (($inc % $this->_colCat) == 0 && $inc != 0) { 
                        $div  .= '</ul><div class="clear"></div><ul class="video-block-container">';
                    }
                    $inc ++;
                }
                $div      .= '</ul>';
                /** Calculate datalimit for video more page */
                if ( $this->_rowCat && $this->_colCat ) {
                    $dataLimit = $this->_perCat;
                } else if ( $this->_rowCat || $this->_colCat ) {
                  $dataLimit = '';
                } else {
                    $dataLimit = 8;
                }
                /** Display more videos link for category thumbs  */
                if (($playlistCount > $dataLimit)) {
                    $div .= '<a class="video-more" href="' . $playlist_more_link . '">' . __ ( 'More&nbsp;Videos', FLICHE_VGALLERY ) . '</a>';
                } else {
                    $div .= '<div align="clear"> </div>';
                }
            } else {
                /** If there is no video for category */
                $div .= '<div class="titleouter">' . __ ( 'No&nbsp;Videos&nbsp;for&nbsp;this&nbsp;Category', FLICHE_VGALLERY ) . '</div>';
            }
        }      
        $div .= '<div class="clear"></div>';
        /** Pagination starts - Call helper function to get pagination values for categories */
        if($dataLimit != 0) { 
            $div .= paginateLinks ($CountOfCatVideos, $dataLimit, $pagenum, '', '' );
        }  
        /** Pagination ends */
        /** Display videmore page content */       
        echo $div;
    }     
} 


/**
 *  This class is used to display the search page results
 */
class MoreSearchView extends MoreCategoryView {
  /**
   * Function to display search results
   *
   * @param unknown $video_search
   * @param unknown $CountOfSearchVideos
   * @param unknown $TypeOfSearchvideos
   * @param unknown $pagenum
   * @param unknown $dataLimit
   * @return string
   */
  function searchlist($video_search, $CountOfSearchVideos, $TypeOfSearchvideos, $pagenum, $dataLimit) {
    $div        = '';
    /**
     * Calculating page number
     * for search videos
     */
    $pagenum    = isset ( $pagenum ) ? absint ( $pagenum ) : 1;
    $div        .= '<div class="video_wrapper" id="video_search_result"><h3 class="entry-title">' . __ ( 'Search Results', FLICHE_VGALLERY ) . ' - ' . $video_search . '</h3>';
    $div        .= '<style> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
    /** Fetch videos based on search  */
    if (! empty ( $TypeOfSearchvideos )) {
      $inc    = 0;
      $div    .= '<ul class="video-block-container">';
      foreach ( $TypeOfSearchvideos as $playList ) {
        $duration     = $playList->duration;
        $file_type    = $playList->file_type;
        $guid         = get_video_permalink ( $playList->slug );
        $imageFea     = getImagesValue ($playList->image, $file_type, $playList->amazon_buckets, '');
         
        if (($inc % $this->_colF) == 0 && $inc != 0) {
          /** Column count for search page */
          $div  .= '</ul><div class="clear"></div><ul class="video-block-container">';
        }
        /** Display search videos
         * thumb and duration */
        $div    .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '" title="' . $playList->name . '"><img src="' . $imageFea . '" alt="" class="imgHome" title="" /></a>';
        if (!empty($duration) && $duration != '0:00') {
          $div .= '<span class="video_duration">' . $duration . '</span>';
        }
  
        /** Display video title, playlist name and link  */
        $div    .= '</div><div class="vid_info"><a href="' . $guid . '" class="videoHname" title="' . $playList->name . '" >' . limitTitle ( $playList->name ) . '</a>';
        if (! empty ( $playList->playlist_name )) {
          $playlist_url = get_playlist_permalink ( $this->_mPageid, $playList->pid, $playList->playlist_slugname );
          $div  .= '<a class="playlistName" href="' . $playlist_url . '">' . $playList->playlist_name . '</a>';
        }
        /** Rating starts here
         * for search videos */
        if ($this->_settingsData->ratingscontrol == 1) {
          $div  .= getRatingValue( $playList->rate, $playList->ratecount, '' );
        }
        if ($this->_settingsData->view_visible == 1) {
          /** Views starts here
           * for search videos */
          $div    .= displayViews ( $playList->hitcount );
        }
        $div .= '</div></li>';
        $inc ++;
      }
      $div  .= '</ul>';
    } else {
      /** If there is no video
       * for search result */
      $div  .= '<div>' . __ ( 'No&nbsp;Videos&nbsp;Found', FLICHE_VGALLERY ) . '</div>';
    }
    $div .= '</div> <div class="clear"></div>';
    /** Pagination starts
     * Call helper function
     * to get pagination values */
    if($dataLimit != 0) {
      $div .= paginateLinks ($CountOfSearchVideos, $dataLimit, $pagenum, '', '' );
    }
    echo $div;
    /** Search result function ends  */
  }
}
?>