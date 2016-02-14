<?php
/**  
 * Video home and short code [videohome] view file.
 *
 * @category   FishFlicks
 * @package    Fliche Video Gallery
 * @version    0.8.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
/**
 * FlicheHomeVideoView class is exist
 */
if(! class_exists( 'FlicheHomeVideoView' )) {
  /**
   * FlicheHomeVideoView class is used to display category thumbs in home page
   *
   * @author user
   */
  class FlicheHomeVideoView extends FlicheVideoController {
    /**
     * FlicheHomeVideoView constructor starts
     */
    public function __construct() {
      parent::__construct();
      /**
       * Call plugin helper function
       * to get plugin settings
       * and more page id */
      $this->_settingsData      = getPluginSettings();
      $this->_player_colors     = unserialize( $this->_settingsData->player_colors );
      $this->_mPageid           = morePageID();
      /** Get video id from request URL for home page */
      $this->_vId               = absint( filter_input( INPUT_GET, 'vid' ) );
      /** Get pid from request URL for home page */
      $this->_pId               = absint( filter_input( INPUT_GET, 'pid' ) );
      /** Get pagenum from request URL for home page */
      $this->_pagenum           = filter_input( INPUT_GET, 'pagenum' );
      /** Get Category column count from settings */
      $this->_colCat            = $this->_settingsData->colCat;
      /** Get featured videos data */
      $this->_featuredvideodata = $this->home_featuredvideodata();
      /** Get WordPress admin URL */
      $this->_site_url          = get_site_url();
      /** Get WordPress site URL */
      $this->_siteURL           = home_url();
      /** Set banner player swf path */
      $this->_bannerswfPath     = FLICHE_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer_banner.swf';
      /** Set plugin player swf path */
      $this->_swfPath           = FLICHE_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer.swf';
      /**
       * Get protocol URL,
       * uploads / videogallery
       * and plugin images directory path
       * from plugin helper
       */
      $this->_imagePath         = getImagesDirURL();
      $this->_pluginProtocol    = getPluginProtocol();
      $this->_uploadPath        = getUploadDirURL();
    }
    
    /**
     * Function for get the video from category based.
     *
     * @global type $wpdb
     * @param type $CountOFVideos
     * @param type $TypeOFvideos
     * @param type $pagenum
     * @param type $dataLimit
     * @param type $category_page
     * @return $category_videos
     */
    function categorylist($CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit, $category_page, $thumImageorder) {
      global $wpdb;
      $div       = '';
      /** Calculating page number for home page category videos */
      $pagenum   = isset( $pagenum ) ? absint( $pagenum ) : 1;
      $div       .= '<style scoped> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important;float:left;} </style>';
      foreach( $TypeOFvideos as $catList ) {
        /** Get video details for particular playlist */
        $playLists      = getCatVideos($catList->pid, $dataLimit, $thumImageorder);
        /** Get count of home page category videos */
        $playlistCount  = count( $playLists );    
        /** Get count of video assigned in this category. */
        $category_video = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid
                                                      WHERE m.playlist_id=' . intval( $catList->pid ) . ' AND p.is_publish=1' );
        /** Get count of videos for category */
        $video_count    = count( $category_video );   
        /** Display home page category title */ 
        $div    .= '<div class="titleouter"> <h4 class="more_title">' . $catList->playlist_name . '</h4></div>';    
        if( !empty( $playlistCount ) ) {
          $inc    = 1;
          /** Video container starts */
          $div    .= '<ul class="video-block-container">'; 
          /** Dsplay videos for category */
          foreach( $playLists as $playList ) {
            /** Get home category videos duration */
            $duration   = $playList->duration;
            /** Get home category videos file type  */
            $file_type  = $playList->file_type;
            /** Get home category videos slug */
            $guid       = get_video_permalink( $playList->slug );
            /** Get home category videos thumb image  */
            $imageFea   = $playList->image;
            $imageFea   = getImagesValue($playList->image, $file_type, $playList->amazon_buckets, ''); 
            /** Display home cat thumb image */   
            $div .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '"><img src="' . $imageFea . '" alt="" class="imgHome" title=""></a>';
            /** Display video duration */
            if( $duration ) {
              $div  .= '<span class="video_duration">' . $duration . '</span>';
            }   
            /** Display home cat video name */ 
            $div  .= '</div><div class="vid_info"><a href="' . $guid . '" title="' . $playList->name . '" class="videoHname"><span>' .  limitTitle( $playList->name ) . '</span></a>';
            /** Rating for home category video */
            if( $this->_settingsData->ratingscontrol == 1 ) {
              $div      .= getRatingValue( $playList->rate, $playList->ratecount, '' );
            }
            /** Show views count for home page category videos */
            if( $this->_settingsData->view_visible == 1 ) {
              $div .= displayViews( $playList->hitcount );
            }
            $div  .= '</div></li>';    
            if(($inc % $this->_colCat) == 0 && $inc != 0) {
              /** Column count */
              $div .= '</ul><div class="clear"></div><ul class="video-block-container">';
            }
            $inc ++;
          }
          $div    .= '</ul>';    
          /** Video category thumb based on gallery setting rows, cols */
          $colF       = $this->_settingsData->colCat;
          $rowF       = $this->_settingsData->rowCat;
          $CatLimit   = $colF * $rowF;    
          if( $video_count > $CatLimit ) {
            /** Get more videos permalink for home cat videos */
            $more_playlist_link   = get_playlist_permalink( $this->_mPageid, $catList->pid, $catList->playlist_slugname );
            /** Display more videos link for home categories */
            $div    .= '<a class="video-more" href="' . $more_playlist_link . '">' . __( 'More&nbsp;Videos', FLICHE_VGALLERY ) . '</a>';
          } else {
            $div    .= '<div align="clear"> </div>';
          }
        } else {
          /** If there is no video for category */
          $div    .= '<div class="titleouter">' . __( 'No Videos for this Category', FLICHE_VGALLERY ) . '</div>';
        }
      }    
      $div .= '<div class="clear"></div>'; 
      /** Check tha page is category page */   
      if($category_page != 0) {
        /** Pagination starts
         * Call helper function to get pagination values for category videos */
        $div .= paginateLinks($CountOFVideos, $category_page, $pagenum, '', '' );
      }
      return $div;
    }
  /** FlicheHomeVideoView class ends */
  }
/** FlicheHomeVideoView class is exist ends */
}

/** Check FlicheVideoView class is exist  */
if(! class_exists( 'FlicheVideoView' )) {
  /**
   * FlicheVideoView class is used to display home page player and thumbnails
   * 
   * @author user
   */
  class FlicheVideoView extends FlicheHomeVideoView {
      /**
       * Show video players
       */
      function home_player() {


        /* CUSTOM CODE -- MM */
        // return nothing
        return '';
        /* END CUSTOM CODE -- MM */


          /** Varaiable Initialization for home page display */
          $videoUrl = $videoId = $thumb_image = $homeplayerData = $file_type = $baseref = $showplaylist = $windo = '';
          /** Get settings data from constructor */
          $settingsData   = $this->_settingsData;
          /** Get featured videos for home page player */
          if(! empty( $this->_featuredvideodata [0] )) {
            $homeplayerData = $this->_featuredvideodata [0];
          }
          if(! empty( $homeplayerData )) {
              /** Get home page player video details  */
              $videoId      = $homeplayerData->vid;
              $videoUrl     = $homeplayerData->file;
              $file_type    = $homeplayerData->file_type;
              $video_title  = $homeplayerData->name; 
              $thumb_image  = getImagesValue($homeplayerData->image, $file_type, $homeplayerData->amazon_buckets, '');
          }
          /** Call query helper to detect mobile or browser */
          $mobile = vgallery_detect_mobile();
          /** Home Page Player starts */
          $div      = '<div> <style type="text/css" scoped> .video-block {margin-left:' . $settingsData->gutterspace . 'px !important; float:left;} </style>';
          $div      .= ' <script> var baseurl = "' . $this->_site_url . '"; </script>';
          $baseref  .= '&amp;featured=true';
          if(! empty( $this->_vId )) {
              $baseref  .= '&amp;vid=' . $this->_vId;
          }
          /** Show / hide the video title of the home page players */        
          $div      .= '<div id="mediaspace" class="mediaspace" style="color: #999999;">';
          if(isset( $this->_player_colors ['showTitle'] ) && $this->_player_colors ['showTitle']) {
              $div  .= '<script type="text/javascript">function current_video( vid, title ){ document.getElementById("video_title").innerHTML = title; }</script>';
              $div  .= '<h3 id="video_title" style="width:' . $settingsData->width . ';text-align: right;"  class="more_title">';
              /** Display for video title in mobile */
              if( $mobile ) {
                  $div .= $video_title;
              }
              $div  .= '</h3>';
          }
          $div      .= '<div id="flashplayer" class="videoplayer" style="border: 1px solid #222222;">';
          /**
           * Check player is enabled
           * If yes then assign plugin player path 
           * Else assign banner player path 
           */
          $swf      = $this->_swfPath;
          if($settingsData->default_player == 1) { 
              $swf            = $this->_bannerswfPath;
              $showplaylist   = '&amp;showPlaylist=true';
          }
          /** Check embed code method or not  */
          if($file_type == 5 && ! empty( $homeplayerData->embedcode )) {
              $div              .= str_replace( 'width=', 'width="' . $settingsData->width . '"', stripslashes( $homeplayerData->embedcode ) );
              $div              .= '<script> current_video( ' . $homeplayerData->vid . ',"' . $homeplayerData->name . '" ); </script>';
          } else {
            /** Check mobile device is detect */
            if($mobile) {
                if((preg_match( '/vimeo/', $videoUrl )) &&($videoUrl != '')) {
                    /** Iframe code for vimeo videos */
                    $vresult    = explode( '/', $videoUrl );
                    $div        .= '<iframe width="100%" height="' . $settingsData->height . '" type="text/html" src="' . $this->_pluginProtocol . 'player.vimeo.com/video/"' . $vresult [3] . '" frameborder="0"></iframe>';
                } elseif(strpos( $videoUrl, 'youtube' ) > 0) {
                    /** Iframe code for youtube videos */
                    $videoId1   = getYoutubeVideoID( $videoUrl );
                    $div        .= '<iframe width="100%" height="' . $settingsData->height . '" type="text/html" src="' . $this->_pluginProtocol . 'www.youtube.com/embed/' . $videoId1 . '" frameborder="0"></iframe>';
                } elseif(strpos( $videoUrl, 'dailymotion' ) > 0) { 
                    /** Iframe code for dailymotion videos */ 
                    $split_id = getDailymotionVideoID( $videoUrl );
                    $video    = $this->_pluginProtocol . 'www.dailymotion.com/embed/video/' . $split_id [0];
                    $div      .= '<iframe src="' . $video . '" width="100%" height="' . $settingsData->height . '" class="iframe_frameborder" ></iframe>';
                } else if(strpos( $videoUrl, 'viddler' ) > 0) { 
                    /** Iframe code for viddler videos */ 
                    $imgstr     = explode( '/', $videoUrl );
                    $div        .= '<iframe id="viddler-' . $imgstr [4] . '" width="100%" height="' . $settingsData->height . '" src="' . $this->_pluginProtocol . 'www.viddler.com/embed/' . $imgstr [4] . '/?f=1&autoplay=0&player=full&secret=26392356&loop=false&nologo=false&hd=false" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>';
                } else {
                    if($file_type == 4) { 
                        $streamer   = str_replace( 'rtmp://', 'http://', $homeplayerData->streamer_path );
                        $videoUrl   = $streamer . '_definst_/mp4:' . $videoUrl . '/playlist.m3u8';
                    } else {
                        $videoUrl = getVideosValue( $videoUrl, $file_type, $homeplayerData->amazon_buckets );
                    }
                    $div          .= '<video width="100%" height="' . $settingsData->height . '" id="video" poster="' . $thumb_image . '"   src="' . $videoUrl . '" autobuffer controls onerror="failed( event )">' . __( 'Html5 Not support This video Format.', FLICHE_VGALLERY ) . '</video>';
                }
            } else {
                
              /* CUSTOM CODE -- MM -- HTML5 Video Player */

                /** If browser is detect then play videos via flash player using embed code 
                $div            .= '<embed id="player" src="' . $swf . '"  flashvars="baserefW=' . $this->_siteURL . $baseref . $showplaylist . '&amp;mtype=playerModule" width="' . $settingsData->width . '" height="' . $settingsData->height . '"   allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" wmode="transparent" />';*/
                $div .='-------------';
                $div .='<pre>';
                $div .= var_dump($swf);
                $div .='</pre>';
                $div .='-------------';
                $div .='<pre>';
                $div .= var_dump($this->_siteURL);
                $div .= var_dump($baseref);
                $div .= var_dump($showplaylist);
                $div .='</pre>';
                $div .='-------------';
                $div .='<pre>';
                $div .= var_dump($settingsData->height);
                $div .= var_dump($settingsData->width);
                $div .='</pre>';
                $div .='-------------';

                $div .= '
                  <link href="http://vjs.zencdn.net/5.0/video-js.min.css" rel="stylesheet">
                  <script src="http://vjs.zencdn.net/5.0/video.min.js"></script>
                ';
                $div .= '
                  <video id="really-cool-video" class="video-js vjs-default-skin" controls
                  preload="auto" width="640" height="264" poster="really-cool-video-poster.jpg"
                  data-setup="{}">
                    <source src="really-cool-video.mp4" type="video/mp4">
                    <source src="really-cool-video.webm" type="video/webm">
                    <p class="vjs-no-js">
                      To view this video please enable JavaScript, and consider upgrading to a web browser
                      that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                    </p>
                  </video>
                ';
                $div .="
                  <script>
                    var player = videojs('really-cool-video', { /* Options */ }, function() {
                      console.log('Good to go!');

                      this.play(); // if you don't trust autoplay for some reason

                      // How about an event listener?
                      this.on('ended', function() {
                        console.log('awww...over so soon?');
                      });
                    });
                  </script>
                ";

              /* END CUSTOM CODE -- MM -- HTML5 Video Player */

            }
          }
          $div        .= '</div>';
          /** Get user agent */
          $useragent  = $_SERVER ['HTTP_USER_AGENT'];
          if(strpos( $useragent, 'Windows Phone' ) > 0) {
            $windo    = 'Windows Phone';
          }
          /** Section to notify not support video format */ 
          $div        .= '<script>  var txt =  navigator.platform ;  var windo = "' . $windo . '";  function failed( e ) { if( txt =="iPod"|| txt =="iPad" || txt == "iPhone" || windo=="Windows Phone" || txt == "Linux armv7l" || txt == "Linux armv6l" ) { alert( "' . __( 'Player doesnot support this video.', FLICHE_VGALLERY ) . '" ); } } </script>';
          $div        .= '<div id="video_tag" class="views"></div> </div>';
          return       $div . '</div>';
       }
      
      /**
       * Function to display  recent ,feature ,category and popular video in home page after player
       *  
       * @param unknown $type
       * @return Ambigous <$category_videos, string>|string
       */      
       function home_thumb($type) {
        /** Check homeVideo function is exists */
        if(!function_exists( 'homeVideo' )) {
          $TypeSet = $recent_video_order = $class = $divOutput = '';          
          $player_colors      = $this->_player_colors;
          $recent_video_order = $player_colors ['recentvideo_order'];
          /** Get popular, recent, featured  video settings status and row, column values
           * Get home page category video settings status and row, column values
           * Call function to display home page category videos */
          $where          = '';
          switch($type) {
              case 'popular' : 
                $TypeSet        = $this->_settingsData->popular; 
                $rowF             = $this->_settingsData->rowsPop;
                $colF             = $this->_settingsData->colPop;
                $dataLimit        = $rowF *  $colF;
                $thumImageorder = 'w.hitcount DESC';
                $typename       = __( 'Popular', FLICHE_VGALLERY );
                $type_name      = $morePage = 'popular';
                break;            
              case 'recent' :
                $TypeSet        = $this->_settingsData->recent; 
                $rowF             = $this->_settingsData->rowsRec;
                $colF             = $this->_settingsData->colRec;
                $dataLimit        = $rowF *  $colF;
                $thumImageorder = 'w.vid DESC';
                $typename       = __( 'Recent', FLICHE_VGALLERY );
                $type_name      = $morePage = 'recent';
                break;            
              case 'featured' :
                $TypeSet          = $this->_settingsData->feature;
                $rowF             = $this->_settingsData->rowsFea;
                $colF             = $this->_settingsData->colFea;
                $dataLimit        = $rowF *  $colF;
                $where            = ' AND w.featured=1 ';              
                $thumImageorder   = getVideoOrder( $recent_video_order );                 
                $typename         = __( 'Featured', FLICHE_VGALLERY );
                $type_name        =  $morePage = 'featured';
                break;            
              case 'cat' :
                if($this->_settingsData->homecategory == 1) {
                  $category_page  = $this->_settingsData->category_page;
                  $rowF           = $this->_settingsData->rowCat;
                  $colF           = $this->_settingsData->colCat;
                  $dataLimit      = $rowF *  $colF;                
                  $thumImageorder = getVideoOrder( $recent_video_order );
                  $typename       = __( 'Video Categories', FLICHE_VGALLERY );
                }
                break;
              default:
                break;
          }  
          if($type == 'popular' ||  $type == 'recent' ||  $type == 'featured' ) {
              /** Get home page thumb data and get count of videos */
              $TypeOFvideos     = $this->home_thumbdata( $thumImageorder, $where, $dataLimit );
              $CountOFVideos    = $this->countof_home_thumbdata( $thumImageorder, $where );
          }
          if($type == 'cat' && isset($category_page)) {
              /** Get home page category thumb data and get count of videos */
              $TypeOFvideos   = $this->home_categoriesthumbdata( $this->_pagenum, $category_page );
              $CountOFVideos  = getPlaylistCount();
              /** Call function to display category videos in home page */
              return $this->categorylist( $CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit, $category_page, $thumImageorder );
          }
          if( $TypeSet ) { 
              /** Display thumbnail block strats */
              $divOutput      = '<div class="video_wrapper" id="' . $type_name . '_video">';
              $divOutput      .= '<style type="text/css" scoped> .video-block {margin-left:' . $this->_settingsData->gutterspace . 'px !important;float:left;}  </style>';
              if(! empty( $TypeOFvideos )) {
                  /** Display videos title in home page */ 
                  $divOutput .= '<h2 class="video_header">' . $typename . ' ' . __( 'Videos', FLICHE_VGALLERY ) . '</h2>';
                  $videolist = 0;
                    foreach( $TypeOFvideos as $video ) {
                      /** Get video duration, image, filetype, slug, video id,
                       * video name, view and rate count */
                      $duration [$videolist]      = $video->duration;
                      $file_type                  = $video->file_type;
                      $guid [$videolist]          = get_video_permalink( $video->slug );
                      $imageFea [$videolist]      = getImagesValue($video->image, $file_type, $video->amazon_buckets, '');
                      $nameF [$videolist]         = $video->name;
                      $ratecount [$videolist]     = $video->ratecount;
                      $rate [$videolist]          = $video->rate;
                      $hitcount [$videolist]      = $video->hitcount;
                      /** Get playlist id, name and slugname */
                      $playlist_id [$videolist]   = $video->pid;
                      $fetched [$videolist]       = $video->playlist_name;
                      $fetched_pslug [$videolist] = $video->playlist_slugname;                      
                      $videolist ++;
                  }                  
                  /** Code to display thumbs for popular / recent and featured videos */
                  $divOutput    .= '<div class="video_thumb_content">';
                  $divOutput    .= '<ul class="video-block-container">';
                  /** Display video list container */
                  for($videolist = 0; $videolist < count( $TypeOFvideos ); $videolist ++) {                    
                      $class = '<div class="clear"></div>';                      
                      if(($videolist % $colF) == 0 && $videolist != 0) { 
                        $divOutput  .= '</ul><div class="clear"></div><ul class="video-block-container">';
                      }                      
                      $divOutput    .= '<li class="video-block">';
                      /** Video thumb image display block starts */
                      $divOutput    .= '<div  class="video-thumbimg"><a href="' . $guid [$videolist] . '"><img src="' . $imageFea [$videolist] . '" alt="' . $nameF [$videolist] . '" class="imgHome" title="' . $nameF [$videolist] . '" /></a>';
                      if($duration [$videolist]) {
                        $divOutput  .= '<span class="video_duration">' . $duration [$videolist] . '</span>';
                      }
                      $divOutput    .= '</div>';                      
                      /** Display video details block starts */
                      $divOutput    .= '<div class="vid_info"><a title="' . $nameF [$videolist] . '" href="' . $guid [$videolist] . '" class="videoHname"><span>' . limitTitle( $nameF [$videolist] ) . '</span></a>';
                      $divOutput    .= '';
                      if($fetched [$videolist] != '' &&($this->_settingsData->categorydisplay == 1)) {
                        $playlist_url   = get_playlist_permalink( $this->_mPageid, $playlist_id [$videolist], $fetched_pslug [$videolist] );
                        /** Display output videos */
                        $divOutput            .= '<a class="playlistName"  href="' . $playlist_url . '"><span>' . $fetched [$videolist] . '</span></a>';
                      }
                      /** Display rating for video home page */
                      if($this->_settingsData->ratingscontrol == 1) {
                          $divOutput        .= getRatingValue( $rate [$videolist], $ratecount [$videolist] , '' );
                      }
                      /** Display views for video home page */
                      if($this->_settingsData->view_visible == 1) {
                        $divOutput          .= displayViews( $hitcount [$videolist] );
                      }
                      /** Display video details block ends */ 
                      $divOutput .= '</div> </li>';
                  }
                  $divOutput     .= '</ul></div> <div class="clear"></div>';
                  /** Code to display more videos link for featured / popular/ recent videos */
                  if($dataLimit < $CountOFVideos) { 
                      $more_videos_link = get_morepage_permalink( $this->_mPageid, $morePage );
                      /** Display more title for category */
                      $divOutput    .= '<span class="more_title" ><a class="video-more" href="' . $more_videos_link . '">' . __( 'More&nbsp;Videos', FLICHE_VGALLERY ) . '&nbsp;&#187;</a></span>';
                      $divOutput    .= '<div class="clear"></div>';
                  } 
                  /** View more to the right */
                  if($dataLimit == $CountOFVideos) {
                      $divOutput    .= '<div style="float:right"></div>';
                  }
              } else {
                $divOutput    .= __( 'No', FLICHE_VGALLERY ) . ' ' . $typename . ' ' . __( 'Videos', FLICHE_VGALLERY );
              }
              $divOutput    .= '</div>';
          }          
          return $divOutput;
        }
      } 
  /** End flicheVideo class */ 
  } 
/** FlicheVideoView class is exist */
} else {
  echo 'class FlicheVideoView already exists';
}
?>