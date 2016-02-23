<?php
/**
 * Video detail page view file
 *
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/**
 * Check FlicheRelatedVideoView class exists
 */
if ( !class_exists ( 'FlicheRelatedVideoView' )) {
  /**
   * FlicheRelatedVideoView class
   * 
   * @author user
   */
  class FlicheRelatedVideoView extends FlicheSocialCommentView {
    /**
     * Function to display related videos slider in video detail page
     * 
     * @param unknown $vid
     * @param unknown $video_playlist_id
     * @param unknown $pluginflashvars
     * @param unknown $width
     * @param unknown $height
     * @return string
     */
    public function relatedVideoSlider ( $vid, $video_playlist_id, $pluginflashvars, $width, $height, $video_div_id) {
      global $wpdb;
     
      $reavideourl = $player_div = $result = $output = '';
      $related1 = array  ();
      /** Get related videos count */
      $Limit    = get_related_video_count ();
      /** Check related videos count. If it is empty then assign default value as 100 */
      if ( empty ( $Limit ) ) {
      	/** set limit as 100 */
        $Limit  = 100;
      } 
      
      /** Get video details for the current video id from helper */
      $vidDetails = videoDetails ( $vid, 'related' );
      
      if ( !empty ( $vidDetails ) ) {
        
        $related1 = array ( $vidDetails );

        /** Get related videos for the given video id */
        $related = $this->relatedVideosDetails ( $related1[0]->vid, $video_playlist_id, $Limit );

        #echo '<pre>';
        #var_dump($related);
        #echo '</pre>';

        /** Merge the current video, related video details */
        /* WHY ?????????
        $related = array_merge ( $related1, $related );
        */
        #echo '<pre>';
        #var_dump($related);
        #echo '</pre>';

      }
      if ( !empty ( $related )) {
        $result = count ( $related );
      }
      if ($result < 4) {
        $output .= '<style>.jcarousel-next , .jcarousel-prev {display:none!important;}</style>';
      }

      /** Display related videos in slider */
      $output   .= '
        <div class="player_related_video">
          <h2 class="related-videos">' . __ ( 'Featured Shows', FLICHE_VGALLERY ) . '</h2>
          <div style="clear: both;"/>
      ';

      if ($result != '') {
        
        
        /** Check for mobile platform */
        $mobile = vgallery_detect_mobile ();
        #$mobile = false;


        /** Slide display starts here */
        $output .= '<ul id="mycarousel" class="jcarousel-skin-tango">';
        
        /** Looping related video details */
        foreach ( $related as $relFet ) {
          $file_type  = $relFet->file_type;
          /** Get featured images list */
          $imageFea   = getImagesValue ($relFet->image, $file_type, $relFet->amazon_buckets, '');
          $reafile    = $relFet->file;
          $guid       = get_video_permalink ( $relFet->slug );
          

          /** Check the post type is videogallery page */
          if ( $this->_post_type === FLICHEVIDEOGALLERY 
            || $this->_page_post_type === FLICHEVIDEOGALLERY ) {

            /* -||- */
            $thumb_href   = 'href="' . $guid . '"';
            /* -||- */

          } 
          else {

            /* -||- */
            $player_div   = 'mediaspace';
            $embedplayer  = "videogallery_change_player( '$player_values',$video_div_id,'$player_div',$file_type,$relFet->vid,'$relFet->name' )";
            $thumb_href   = 'href="javascript:void( 0 );" onclick="' . $embedplayer . '"';
            /* -||- */

          }


          $output .= '
            <li>
          ';

          $output .= '
              <div class="imgSidethumb">
                <a title="' . $relFet->name . '" ' . $thumb_href . '>
                  <img src="' . $imageFea . '" alt="' . $relFet->name . '" class="related" />
                </a>
              </div>
          ';
          
          /** Display title under thumbnails */
          $output .= '
              <div class="vid_info">
                <span>
                  <a ' . $thumb_href . ' class="videoHname" title="' . $relFet->name . '">' . limitTitle ($relFet->name) . '</a>
                </span>
              </div>
          ';
          
          $output .= '
            </li>
          ';
        }
        $output .= '
          </ul>
        ';
        /** Slide display ends here and if ends */

      }
      $output .= '
        </div>
      ';

      return $output;
    }
    /** Check FlicheRelatedVideoView class ends */
  }
/** Check FlicheRelatedVideoView class exists if ends */  
}


/** Check FlicheVideoDetailRatingView class exists */
if ( !class_exists ( 'FlicheVideoDetailRatingView' )) {
    /**
     * FlicheVideoDetailRatingView class
     * 
     * @author user
     */
    class FlicheVideoDetailRatingView extends FlicheRelatedVideoView {
      /**
       * Function to display ratings in video detail page
       *
       * @param unknown $ratestar
       * @param unknown $ratecount
       * @param unknown $video_div_id
       * @param unknown $vid
       * @param unknown $video_id
       * @return string
       */
      function displayRatings($ratestar, $ratecount, $video_div_id, $vid, $video_id) {
        $output = NULL;
        /** Display rating content */
        $output   .= '<div class="video-page-rating">
                        <div class="centermargin floatleft" >
                            <div class="rateimgleft" id="rateimg" onmouseover="displayRating( 0 );" onmouseout="resetValue(' . $ratecount . ');" >
                                <div id="a' . $video_div_id . $vid . '" class="floatleft"></div>
                                <ul class="ratethis " id="rate' . $video_div_id . $vid . '" >
                                    <li class="one" >
                                        <a title="1 Star Rating"  onclick="getRating( 1 );"  onmousemove="displayRating( 1 );" onmouseout="resetValue(' . $ratecount . ');">1</a>
                                    </li>
                                    <li class="two" >
                                        <a onclick="getRating( 2 );"  title="2 Star Rating" onmousemove="displayRating( 2 );" onmouseout="resetValue(' . $ratecount . ');">2</a>
                                    </li>
                                    <li class="three" >
                                        <a title="3 Star Rating" onmousemove="displayRating( 3 );" onclick="getRating( 3 );" onmouseout="resetValue(' . $ratecount . ');">3</a>
                                    </li>
                                    <li class="four" >
                                        <a  title="4 Star Rating" onclick="getRating( 4 );" onmouseout="resetValue(' . $ratecount . ');" onmousemove="displayRating( 4 );">4</a>
                                    </li>
                                    <li class="five" >
                                        <a title="5 Star Rating" onmouseout="resetValue(' . $ratecount . ');" onclick="getRating( 5 );"  onmousemove="displayRating( 5 );" >5</a>
                                    </li>
                                </ul>
                                <input type="hidden" name="videoid" id="videoid' . $video_div_id . $vid . '" value="' . $vid . '" />
                                <input type="hidden" value="" id="storeratemsg' . $video_div_id . $vid . '" />
                            </div>
                            <div class="rateright-views floatleft" >
                                <span  class="clsrateviews"  id="ratemsg' . $video_div_id . $vid . '" onmouseover="displayRating( 0 );" onmouseout="resetValue(' . $ratecount . ');"> </span>
                                <span  class="rightrateimg" id="ratemsg1' . $video_div_id . $vid . '" onmouseover="displayRating( 0 );" onmouseout="resetValue(' . $ratecount . ');">  </span>
                            </div>
                        </div>
                    </div> ';
        $output   .= '<div class="clear"></div>'; 
        /** Assign site url into script variable */
        /** Assign videodiv id into script variable */
        /** Assign video id into script variable */
        $output   .= '<script type="text/javascript">
              var baseurl = "' . $this->_site_url . '";
              var adminurl = "' . admin_url() . '";
              var videodiv = "'. $video_div_id .'";
              var videoid = "' . $vid . '";';
        /** Check if rate and count is exists */
        if (isset ( $ratestar ) && isset ( $ratecount )) {
          /** Call script function to get rate count */
          $output   .= 'rateCalc(' . $ratestar . ',' . $ratecount . ');';
        }
        /** Return rating content */
        return   $output . '</script>';
      }
   /** FlicheVideoDetailRatingView class ends */ 
    }
/** Check FlicheVideoDetailRatingView class exists if ends */
}


/** Check FlicheVideoDetailRatingView class exists */
if ( !class_exists ( 'FlicheReportVideoView' )) {
  /**
   * FlicheReportVideoView class starts
   * 
   * @author user 
   */
  class FlicheReportVideoView extends FlicheVideoDetailRatingView {
    /**
     * Function is used to display report video option under player
     *
     * @param unknown $embed_code
     * @param unknown $video_slug
     * @param unknown $currentUserEmail
     * @return string
     */
    function displayReportVideo ($embed_code, $video_slug, $currentUserEmail ){
      $output = NULL;
      /** Code to display report video section  */
      /** Display embed code */
      $output .= '<textarea onclick="this.select()" id="embedcode" name="embedcode" style="display:none;" rows="7" >' . $embed_code . '</textarea>
                                  <input type="hidden" name="flagembed" id="flagembed" />';
      /** Display report video form */
      $output .= '<div id="report_video_response"></div><form name="reportform" id="reportform" style="display:none;" method="post" >';
      /** Display report video title */
      $output .= '<div class="report-video-title">Report this video</div>';
      $output .= '<img id="reportform_ajax_loader"  src="' . getImagesDirURL() .'ajax-loader.gif" />';
      /** Display the options in report video form */
      $output .= '<input type="radio" name="reportvideotype" id="reportvideotype" value="Violent or repulsive content">Violent or repulsive content<label class="reportvideotype" title="Violent or grapical content or content posted to shock viewers"></label><br>';
      $output .= '<input type="radio" name="reportvideotype" id="reportvideotype" value="Hateful or abusive content" >Hateful or abusive content<label class="reportvideotype" title="Content that promotes harted against protected groups, abuses vulnerable individuals , or enganges in cyberling"></label><br>';
      $output .= '<input type="radio" name="reportvideotype" id="reportvideotype" value="Harmful dangerous acts" >Harmful dangerous acts<label class="reportvideotype" title="Content  that includes acts that many results in  physical harm"></label><br>';
      $output .= '<input type="radio" name="reportvideotype" id="reportvideotype" value="Spam or misleading">Spam or misleading<label class="reportvideotype" title="Content that is massively posted or otherwise misleading in nature"></label><br>';
      $output .= '<input type="radio" name="reportvideotype" class="reportvideotype" id="reportvideotype" value="Child abuse">Child abuse<label class="reportvideotype" title="Content that includes sexual,predatory or abusive communication  towards minors"></label><br>';
      $output .= '<input type="radio" name="reportvideotype" class="reportvideotype" id="reportvideotype" value="Sexual content">Sexual content<label class="reportvideotype" title="Includes graphic sexual activity, nutity and other sexual content"></label><br>';
      /** Set slug id as hidden value for report video  option */
      $output .= '<input type="hidden" id="redirect_url" value="' . $video_slug . '" name="redirect_url" />';
      /** Set current user email as hidden value for report video  option */
      $output .= '<input type="hidden" id="reporter_email" value="' . $currentUserEmail . '" name="reporter_email" />';
      /** Display send button in report video form */
      $output .= '<input type="button" class="reportbutton" value="Send" onclick="return reportVideoSend();" name="reportsend" />';
      /** Display cancel button */
      $output .= '&nbsp;&nbsp;<input type="reset" onclick="return hideReportForm();" class="reportbutton" value="Cancel" id="ReportFormreset" style="margin: 2% 0;" name="reportclear" />';
      /** Return report video form content */
      return $output . '</form> <input type="hidden" name="reportvideo" id="reportvideo" />';
    }
  } 
/** Check FlicheReportVideoView class exists if ends */
}


/** Check FlicheVideoDetailView class exists  */
if ( !class_exists ( 'FlicheVideoDetailView' )) {
    /**
     * FlicheVideoDetailView class starts
     * 
     * @author user
     */
    class FlicheVideoDetailView extends FlicheReportVideoView {
        /**
         * Function is used to display player and video information
         *
         * @param array $arguments          
         * @return unknown number string
         */
        function hdflv_sharerender($arguments = array()) {
            global $wpdb, $current_user;

            /** Variable initialization for FlicheVideoDetailView */
            $output = $videourl = $imgurl = $vid = $playlistid = $video_data = $rate = $no_views = $windo = $post_date = '';
            $video_playlist_id = $video_id = $hitcount = $show_posted_by = $show_added_on = $show_social_icon = $ratecount = $video_div_id = 0;
            
            $fetched = array();
            
            /** Get random number to attach */
            $video_div_id = rand();
            
            /** Check admin logged in */
            $isAdmin = absint( filter_input( INPUT_GET, 'admin' ) );
            
            /**  Query to get settings data from db */
            $configXML = getPluginSettings();
            #echo '<pre>';
            #echo 'getPluginSettings() => configXML';
            #var_dump($configXML);
            #echo '</pre>';

            # -||- moved up here ( and not using :)
            $mobile = vgallery_detect_mobile();
            #var_dump($mobile);
            

            $fliche_plugin_dir_url = plugin_dir_url( dirname ( dirname ( __FILE__ ) ) );
            #echo '<pre>';
            #echo 'plugin_dir_url: ';
            #echo $fliche_plugin_dir_url;
            #echo '</pre>';


            /** Generate flashvars detail 
             * for player starts here */
            # -||- #
            ##$flashvars = $pluginflashvars = 'baserefW=' . home_url ();
            $flashvars = $pluginflashvars = 'baserefW=' . site_url ();
            # -||- #


            /**  Get width from settings */
            $width = $configXML->width;
            if (isset ( $arguments['width'] ) && !empty($arguments['width'])) {
                /** Get width from shortcode arguments */
                $width = $arguments['width'];
            }            
            /** Get height from settings */
            $height = $configXML->height;
            if (isset ( $arguments['height'] ) && !empty( $arguments['height']) ) { 
                /** Get height from shortcode arguments */
                $height = $arguments['height'];
            }
            
            /** Get playor colors, posted by, social icon, rss icon 
             * and related videos count from settings object */
            $player_controls         = unserialize ( $configXML->player_colors );
            $show_posted_by       = $player_controls['show_posted_by'];
            $show_social_icon     = $player_controls['show_social_icon'];
            $show_rss_icon        = $player_controls['show_rss_icon'];
            
            $number_related_video = get_related_video_count ();
            /** If related video is not given in settings page,
             * then assign default value 100 */
            if (empty ( $number_related_video )) {
                $number_related_video = 100;
            }      
            
            /** Get show added on option from settings*/      
            if (isset ( $player_controls['show_added_on'] )) {
                $show_added_on        = $player_controls['show_added_on'];
            }
            

            /** Send report for video */
            if (isset ( $arguments['id'] )) {
                /** Get video id from shortcode */
                $video_div_id   .= $arguments['id'];
                $vid           = $arguments['id'];
            }        
            
            /** Call function to get video details  */
            if ( !empty ( $vid )) {

                $video_data         = $this->short_video_detail ( $vid, $number_related_video );
                $fetched[]          = $video_data;
                
            } 
            
            /** Store video details in variables */
            if ( !empty( $video_data ) ) {

                /** Get video detials from model */
                $video_url          = $video_data->file;
                $video_id           = $video_data->vid;
                $video_title        = $video_data->name;
                $video_slug         = $video_data->slug;
                $video_file_type    = $video_data->file_type;
                $video_duration     = $video_data->duration;
                $video_playlist_id  = $video_data->playlist_id;
                $video_playlist     = $video_data->playlist_name;
                $description        = $video_data->description;
                $tag_name           = $video_data->tags_name;
                $hitcount           = $video_data->hitcount;
                $uploadedby         = $video_data->display_name;
                $uploadedby_id      = $video_data->ID;
                $ratecount          = $video_data->ratecount;
                $rate               = $video_data->rate;
                $post_date          = $video_data->post_date;
                $video_thumb        = getImagesValue( $video_data->image, $video_file_type, $video_data->amazon_buckets, '' );
                $video_image_url    = getImagesValue( $video_data->image, $video_file_type, $video_data->amazon_buckets, '' );
                /** Check if video url is YouTube */
                #if ( strpos ( $video_url, 'youtube' ) > 0 ) {
                if ( $video_file_type == 1 && strpos ( $video_url, 'youtube' ) > 0 ) {
                    $video_thumb  = $this->_protocolURL . 'img.youtube.com/vi/' . getYoutubeVideoID ( $video_url ) . '/hqdefault.jpg';
                    $video_image_url = $video_thumb;
                    $video_is_yt = true;
                }
            }

            /*
            echo '<pre style="overflow: auto; height: 100px;">';
            echo '$video_data <br>';
            var_dump($video_data);
            echo '</pre>';
            *//*
            echo '<pre style="overflow: auto; height: 100px;">';
            echo '$fetched[0] <br>';
            var_dump($fetched[0]);
            echo '</pre>';
            */


            /** Get playlist id from shortcode */
            if (isset ( $arguments['playlistid'] )) {
            	/** Get playlist id */
                $video_div_id   .= $arguments['playlistid'];
                /** Get playlist id */
                $playlistid   = $arguments['playlistid'];
                /** Set flash vars */
                $flashvars    .= '&amp;mtype=playerModule';
            }
            /** Check view is from admin */
            if( !empty($isAdmin)){
            	$flashvars      .= '&amp;adminview=true';
            }

            /*
            echo '<pre style="overflow: auto; height: 60px;">';
            echo '$playlistid <br>';
            var_dump($playlistid);
            echo '</pre>';
            */


            /** Generate flashvars detail for player starts here */
            if ( !empty ( $playlistid ) && !empty ( $vid )) {
            
                $flashvars      .= '&amp;pid=' . $playlistid . '&amp;vid=' . $vid;

            
            } elseif ( !empty ( $playlistid )) {
            
                $flashvars      .= '&amp;pid=' . $playlistid . '&showPlaylist=true';
            
                $playlist_videos = $this->_contOBJ->video_pid_detail ( $playlistid, 'detailpage', $number_related_video );
            
                /** Get video details based on the playlist id */
                if ( !empty ( $playlist_videos )) {
                    
                    $fetched[]          = $playlist_videos[0];

                    $video_id           = $playlist_videos[0]->vid;
                    $video_playlist_id  = $playlist_videos[0]->playlist_id;
                    $hitcount           = $playlist_videos[0]->hitcount;
                    $uploadedby         = $playlist_videos[0]->display_name;
                    $uploadedby_id      = $playlist_videos[0]->ID;
                    $ratecount          = $playlist_videos[0]->ratecount;
                    $rate               = $playlist_videos[0]->rate;
                }
            
            } else if ($this->_post_type !== FLICHEVIDEOGALLERY && $this->_page_post_type !== FLICHEVIDEOGALLERY) {
                $flashvars .= '&amp;vid=' . $vid . '&showPlaylist=false';
            
            } else {
                $flashvars .= '&amp;vid=' . $vid;
            }
            
            /** Set flashvars based on the shortcode arguments */
            if (isset ( $arguments['flashvars'] )) {
                $flashvars .= '&amp;' . $arguments['flashvars'];
            }

            /** Set flashvars for video autoplay and video playlist autoplay */
            if ( !isset ( $arguments['playlistid'] ) && isset ( $arguments['id'] ) && $this->_post_type !== FLICHEVIDEOGALLERY && $this->_page_post_type !== FLICHEVIDEOGALLERY) {
              $flashvars .= '&amp;playlist_autoplay=false&amp;playlist_auto=false';
            }
            /** Generate flashvars detail for player ends here */


            $player_not_support = __ ( 'Player doesnot support this video.', FLICHE_VGALLERY );
            $htmlplayer_not_support = __ ( 'Html5 Not support This video Format.', FLICHE_VGALLERY );
            


            $output   .= ' <script> var videoPage; videoPage = "' . $this->_mPageid . '"; </script>';



            if (isset ( $arguments['title'] ) && $arguments['title'] == 'on') {
              $output .= '<h2 id="video_title' . $video_div_id . '" class="videoplayer_title" ></h2>';
              $pluginflashvars .= $flashvars .= '&amp;videodata=current_video_' . $video_div_id;
            }

            /*
            echo '<pre style="overflow: auto; height: 100px;">';
            echo '$flashvars <br>';
            var_dump($flashvars);
            echo '$pluginflashvars <br>';
            var_dump($pluginflashvars);
            echo '</pre>';
            */


        /** Player starts here */
            
            $output   .= '<div id="mediaspace' . $video_div_id . '" class="videoplayer" style="border: 0px solid green;">';
                        

            # moved to top ^
            #$mobile = vgallery_detect_mobile ();
            #var_dump($mobile);



            /** Embed player code */

            if ( 1 == 0 

              && !isset ( $fetched ) 

              && $fetched[0]->file_type == 5 
              
              && !empty ( $fetched[0]->embedcode ) ) {


                $playerembedcode    = stripslashes ( $fetched[0]->embedcode );
                $playeriframewidth  = str_replace ( 'width=', 'width="' . $width . '"', $playerembedcode );

                if ($mobile) {
                    $output .= $playerembedcode;
                } else {
                    $output .= str_replace ( 'height=', 'height="' . $height . '"', $playeriframewidth );
                }

                $output .= '<script> current_video( ' . $fetched[0]->vid . ',"' . $fetched[0]->name . '" ); </script>';



            } 

            /** Check mobile device is detected -- 1 == 1 || */

            else if ( 1 == 0 && $mobile ) {
              
                $output .= '<script> current_video( ' . $fetched[0]->vid . ',"' . $fetched[0]->name . '" ); </script>';

                /** Get video detail for HTML5 player
                 * Load video details */
                foreach ( $fetched as $media ) {
                    #echo '<pre>';
                    #var_dump($media);
                    #echo '</pre>';
                    $videourl             = $media->file;
                    $file_type            = $media->file_type;
                    $imgurl               = getImagesValue ( $media->image, $file_type, $media->amazon_buckets, '');
                }


                /** Check file type youtube/viddler/dailymotion (1) OR custom url (3) */

                if ( $file_type == 1 || $file_type == 3 ) {

                    if (strpos ( $videourl, 'youtube' ) > 0) {
                        $videourl   = $this->_protocolURL . 'www.youtube.com/embed/' . getYoutubeVideoID ( $videourl );
                        /** Generate youtube embed code for html5 player */
                        $output     .= '<iframe  type="text/html" width="100%" height="' . $height . '" src="' . $videourl . '" frameborder="0"></iframe>';
                    } else if (strpos ( $videourl, 'viddler' ) > 0) {
                        /** For viddler videos in URL method */
                        $imgstr       = explode ( '/', $videourl );
                        $viddler_id   = $imgstr[4];
                        $output       .= '<iframe id="viddler-' . $viddler_id . '" width="100%" height="' . $height . '" src="' . $this->_protocolURL . 'www.viddler.com/embed/' . $viddler_id . '/?f=1&autoplay=0&player=full&secret=26392356&loop=false&nologo=false&hd=false" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>';
                    } elseif (strpos ( $videourl, 'dailymotion' ) > 0) {
                        /** For dailymotion videos in URL method */
                        $split_id     = getDailymotionVideoID ( $videourl );
                        $video        = $videourl =  $this->_protocolURL . 'www.dailymotion.com/embed/video/' . $split_id[0];
                        $output       .= '<iframe src="' . $video . '?allowed_in_playlists=0" width="' . $width . '" height="' . $height . '"  class="iframe_frameborder" ></iframe>';
                    } else {
                        $output       .= '<video width="auto" height="' . $height . '" id="video" poster="' . $imgurl . '"   src="' . $videourl . '" autobuffer controls onerror="failed( event )">' . $htmlplayer_not_support . '</video>';
                    }

                } else {

                    /** For uploaded videos, get video URL */
                    /* CUSTOM CODE -- MM -- bug fix */
                    #$video_url  = getVideosValue ( $videoFile, $file_type, $amazonBucket );
                    $video_url  = $videourl;
                    /* END CUSTOM CODE -- MM -- bug fix */

                    /** Check for RTMP videos */
                    if ($file_type == 4) {                        
                        $streamer   = str_replace ( 'rtmp://', 'http://', $media->streamer_path );
                        $video_url  = $streamer . '_definst_/mp4:' . $videourl . '/playlist.m3u8';
                    }


                    /** Generate video code for html5 player */
                    $output .= '<video width="auto" height="' . $height . '" id="video" poster="' . $imgurl . '"   src="' . $video_url . '" autobuffer controls onerror="failed( event )">' . $htmlplayer_not_support . '</video>';

                }



            /* CUSTOM CODE -- MM -- HTML5 or Flash Video Player */

            } else {



              /* CUSTOM CODE -- MM -- Flash Video Player */

              if ( 1 == 0 && $video_file_type == 1 ) {

                /* old flashplayer */
                $output .= '<div id="flashplayer"><embed src="' . $this->_swfPath . '" flashvars="' . $flashvars . '" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" wmode="transparent"></div>';
                
                /* old flashvars */
                echo '<pre>';
                $output .= var_dump($this->_swfPath);
                $output .= var_dump($flashvars);
                #$output .= var_dump($height);
                #$output .= var_dump($width);
                echo '</pre>';

                // Google adsense code Start
                if ($player_controls['googleadsense_visible'] == 1 && !( $mobile) && ($this->_post_type === FLICHEVIDEOGALLERY || $this->_page_post_type === FLICHEVIDEOGALLERY)) {
                  if ( $video_data->google_adsense && $video_data->google_adsense_value ) {
                    // Call function to display google adsense on player
                    $output .= '<div>';
                    $output .= $this->displayGoogleAdsense( $width, $vid, $video_data->vid );
                    $output .= '</div>';
                  }
                }

              }
              /* END CUSTOM CODE -- MM -- Flash Video Player */


              /* CUSTOM CODE -- MM -- HTML5 Video Player */

              if ( 1 == 1 && $video_file_type == 1 || $video_file_type == 2 ) {

                // Store the shortcode in a variable
                $do_video = '-||- video not set yet -||-';
                
                // local video
                //
                if ( $video_file_type == 2 ) {
                  //
                  $do_video = do_shortcode('
                    [video 
                      width="1280" 
                      height="720" 
                      mp4="'.$video_url.'"
                      poster=""
                      autoplay="on" 
                      preload="metadata"
                    ]
                  '); 
                      #ogv="'.$video_url.'.ogv" 
                      #webm="'.$video_url.'.webm"
                      #poster="'.$video_image_url.'"
                  //

                  /*
                  $do_video = do_shortcode('
                    [TS_VCSC_HTML5_Video 
                      video_mp4_source="false" 
                      video_mp4_remote="'.$video_url.'" 
                      video_webm_source="false" 
                      video_webm_remote="'.$video_url.'.webm" 
                      video_ogg_source="false" 
                      video_ogg_remote="'.$video_url.'.ogv" 
                      content_iframe="true" 
                      video_theme="minimum"  
                      video_title="'.$video_title.'" 
                      video_poster="'.$video_thumb.'"
                      video_posterfit="fill"
                      video_auto="true" 
                      video_loop="false" 
                      video_share="false"
                      video_logo_show="logotop" 
                      video_logo_image="10" 
                      video_logo_position="bottomleft" 
                      video_logo_opacity="15" 
                      el_file=""
                    ]
                  ');
                      #content_tooltip_css="true" 
                      #content_tooltip_encoded="VElQJTIwVElQJTIwVElQ" 
                      #content_tooltip_position="ts-simptip-position-bottom"
                  */

                  // hide html5 video context menu (right-click)
                  $output .= '
                    <script>
                      jQuery(document).ready(function(){
                        jQuery("video").bind("contextmenu",function(){
                          return false;
                        });
                      });
                    </script>
                  ';
                }


                // youtube video
                else if ( $video_file_type == 1 ) {
                //
                  #$video_url = 'https://youtu.be/RVWtSsMUBD0';
                  #$video_url = 'https://www.youtube.com/watch?v=RVWtSsMUBD0';

                  #echo "HEY HEY HEY";
                  #exit;

                  $ytid = getYoutubeVideoID( $video_url );
                  /*
                  $do_video = do_shortcode('
                    [youtube id="'.$ytid.'" ]
                  ');
                  *//*
                  [youtube id="Q6goNzXrmFs" parameters="start=30&fs=0" aspect_ratio="16:9" align="center" 
                    thumbnail="https://nextgenthemes.com/wp-content/uploads/2015/11/wp-logo.png" ]
                  */
                  

                  //
                  $do_video  = '
                    <script src="' . $fliche_plugin_dir_url . 'mejs/build/mediaelement-and-player.min.js"></script>
                    <link rel="stylesheet" href="' . $fliche_plugin_dir_url . 'mejs/build/mediaelementplayer.css" />
                    <link rel="stylesheet" href="' . $fliche_plugin_dir_url . 'mejs/build/mejs-skins.css" />
                  ';
                  //
                  #<script src="' . $fliche_plugin_dir_url . 'mejs/build/jquery.js"></script>
                  #<script src="' . $fliche_plugin_dir_url . 'mejs/build/mediaelement-and-player.min.js"></script>
                  #<link rel="stylesheet" href="' . $fliche_plugin_dir_url . 'mejs/build/mediaelementplayer.css" />
                  #<script src="http://mediaelementjs.com/js/mejs-2.9.2/mediaelement-and-player.min.js"></script>
                  #<link rel="stylesheet" href="http://mediaelementjs.com/js/mejs-2.9.2/mediaelementplayer.min.css" />
                  #<link rel="stylesheet" href="' . $fliche_plugin_dir_url . 'mejs/build/mejs-skins.css" />
                  
                  /**/
                  $do_video .= '
                    <video 
                      id="fliche-player" 
                      width="854" 
                      height="480" 
                      preload="auto"
                      class="mejs-player"
                      controls="controls" 
                    >';
                      #class="mejs-ted"
                      #poster="'.$video_image_url.'" 
                      #<video id="fliche-video" class="video-js vjs-default-skin" controls
                      #preload="auto" width="1080" height="auto" poster="' . $video_image_url . '"
                      #data-setup="{}">
                  if ( $video_file_type == 1 ){
                    $do_video .= '
                        <source type="video/youtube" src="'.$video_url.'" />
                    ';
                  }
                  else if ( $video_file_type == 2 ){
                    $do_video .= '
                        <source type="video/mp4" src="'.$video_url.'" />
                        <source type="video/mp4" src="'.$video_url.'" />
                        <source type="video/mp4" src="'.$video_url.'" />
                    ';
                  }  
                  $do_video .= '
                    <p class="vjs-no-js">
                      To view this video please enable JavaScript, and consider upgrading to a web browser
                      that supports HTML5 video.
                    </p>
                  </video>
                  ';
                  $do_video .= '
                    <script type="text/javascript">
                      jQuery(document).ready(function() {
                        jQuery("#fliche-player").mediaelementplayer({
                          success: function(mediaElement, domObject) {
                              mediaElement.play();
                          },
                          error: function() {
                              alert("Error setting media!");
                          }
                        });
                      });
                    
                    </script>
                  ';
                  /**/
                  # OR
                  /*
                  $do_video .= '
                    <div class="video-overlay" id="video-overlay">
 
                        <div class="fliche-player">
                            <video width="940" height="524" id="fliche-player" class="mejs-player">
                                <source type="video/youtube" src="'.$video_url.'" />
                            </video>
                        </div>
                        <div class="overlay-text">
                            <p class="initial-text">Start Video</p>
                            <p class="paused-text">Resume</p>
                            <p class="ended-text">Replay</p>
                        </div>
                        
                    </div>
                  ';
                  $do_video .= '
                    <script type="text/javascript">

                        jQuery(document).ready(function() {
    
                            $videoOverlay = jQuery(".video-overlay");
                            var isPlaying = false;
                            
                            var player = new MediaElementPlayer( "#fliche-player", {
                                success: function( mediaElement ) { 
                                        
                                        if ( false === isPlaying ) {
                                            player.play();
                                        }

                                        alert("HEY HEY HEY");
                                    
                                    // Show the initial overlay once the media player is set up
                                    $videoOverlay.addClass("video-overlay-visible initial);
                                    
                                    // The overlay is on top of the player, so we have to make it respond to clicks
                                    $videoOverlay.click( function() {
                                        
                                        if ( false === isPlaying ) {
                                            player.play();
                                        }

                                        alert("HEY HEY HEY");
                                        
                                    });
                                    
                                    // Event listener for when the video starts playing
                                    mediaElement.addEventListener( "playing", function( e ) {
                                        $videoOverlay.removeClass( "video-overlay-visible initial paused ended’ );
                                        isPlaying = true; //keep track of if it’s playing or not
                                    }, false);
                                    
                                    // Event listener for when the video is paused
                                    mediaElement.addEventListener( "pause", function( e ) {
                                        $videoOverlay.addClass( ‘video-overlay-visible paused" );
                                        isPlaying = false;
                                    }, false);
                                    
                                    // Event listener for when the video ends
                                    mediaElement.addEventListener( "ended", function( e ) {
                                        $videoOverlay.addClass( "video-overlay-visible ended" );
                                        isPlaying = false;
                                    }, false);
                                    
                                }
                            } );
                            
                        } );

                    </script>
                  ';
                  */

                }

$do_video_shortcode = do_shortcode('
[vc_row]
  [vc_column width="3/4"] 
  ' . $do_video . '
    [vc_empty_space height="10px"]

    [vc_column_text]
      <strong>TITLE:</strong> ' . $video_title . '
      <strong>SERIES:</strong> ' . $video_playlist . '
      <strong>LENGTH:</strong> ' . $video_duration . '
    [/vc_column_text]

  [/vc_column]

  [vc_column width="1/4"]
    [vc_single_image image="538" img_size="full"]
    [vc_empty_space height="10px"]
  [/vc_column]
[/vc_row]
');

    #[vc_single_image source="external_link" external_img_size="full" onclick="custom_link" custom_src="'.$video_thumb.'" link="#GoTo"]
    #[vc_empty_space height="10px"]

/*
[vc_row equal_height="yes"]
  [vc_column width="1/4"]
    [vc_single_image image="15" img_size="460x268" alignment="center"]
  [/vc_column]
  [vc_column width="1/4"]
    [vc_single_image image="16" img_size="220x124" alignment="center"]
    [vc_single_image image="16" img_size="220x124" alignment="center"]
  [/vc_column]
  [vc_column width="1/4"]
    [vc_single_image image="16" img_size="220x124" alignment="center"]
    [vc_single_image image="16" img_size="220x124" alignment="center"]
  [/vc_column]
  [vc_column width="1/4"]
    [vc_single_image image="16" img_size="220x124" alignment="center"]
    [vc_single_image image="16" img_size="220x124" alignment="center"]
  [/vc_column]
[/vc_row]
*/

                // Output that variable
                #echo $do_video;
                #$output .= $do_video;
                $output .= $do_video_shortcode;

              }
              /* END CUSTOM CODE -- MM -- HTML5 Video Player */


              /* CUSTOM CODE -- MM -- Related Videos ( in category/channel/playlist ) */
              /** Enable/disable Related videos slider */
              $flag = 1;
              if( $vid  && isset ( $arguments['playlistid'] ) && isset ( $arguments['relatedvideos'])  && $arguments['relatedvideos'] == 'on') {
                $flag = 1;
              }
              if ( $flag == 1 
                || ( $this->_post_type === FLICHEVIDEOGALLERY 
                  || $this->_page_post_type === FLICHEVIDEOGALLERY ) 
                && $player_controls['show_related_video'] == 1 ) 
              {
                /** Call function to display related videos slider */
                $output .= $this->relatedVideoSlider( $vid, $video_playlist_id, $pluginflashvars, $width, $height, $video_div_id );
              }
              /* END CUSTOM CODE -- MM -- Related Videos */





            }
            /** End Embed player code */


            /**  Check platform */

            /** Get current user agent */
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            
            /** Check for windows phone */
            if (strpos ( $useragent, 'Windows Phone' ) > 0) {
                $windo = 'Windows Phone';
            }

            /** Call script to display error message within player 
            
            $output .= '<script type="text/javascript">
                function current_video_' . $video_div_id . '( video_id,d_title ){  
                    if( d_title == undefined ) { 
                      document.getElementById( "video_title' . $video_div_id . '" ).innerHTML=""; 
                    } else { 
                      document.getElementById( "video_title' . $video_div_id . '" ).innerHTML=""; 
                      document.getElementById( "video_title' . $video_div_id . '" ).innerHTML=d_title; 
                    } 
                } var txt =  navigator.platform ; 
                var windo = "' . $windo . '"; 
                function failed( e ) { 
                    if( txt =="iPod"|| txt =="iPad" || txt == "iPhone" || windo=="Windows Phone" || txt == "Linux armv7l" || txt == "Linux armv6l" ) { 
                      alert( "' . $player_not_support . '" ); 
                    } 
                } </script>';*/

          
        /** Player ends here */


        /** Extras start here */
        if ( 1 == 0 ) {


            /* Display description, views, tags, playlist names detail under player */
            if (isset ( $arguments['views'] ) && $arguments['views'] == 'on') {
                $videogalleryviews = true;
            } else {
                if (($this->_post_type === FLICHEVIDEOGALLERY || $this->_page_post_type === FLICHEVIDEOGALLERY) && $configXML->view_visible == 1) {
                    $videogalleryviews = true;
                } else {
                    $videogalleryviews = false;
                    $no_views = 'noviews';
                }
            }

            
            /** Call function to display view count, posted on details in video detail page   */
            $output   .= $this->displayViewsPostedON ($show_added_on, $videogalleryviews, $no_views, $post_date, $hitcount);
            
            $output .= '<div class="clearfix"></div>';

            /** Display user name under player */
            if ($this->_post_type === FLICHEVIDEOGALLERY || $this->_page_post_type === FLICHEVIDEOGALLERY) {
                $user_url = get_user_permalink ( $this->_mPageid, $uploadedby_id, $uploadedby );
                if ($show_posted_by) {
                    $output .= '<div class="video-page-username"><strong>' . __ ( 'Posted&nbsp;by', FLICHE_VGALLERY ) . '&nbsp;:&nbsp;</strong><a href="' . $user_url . '">' . $uploadedby . '</a></div>';
                }
                /** Display category name under player  */
                if ($configXML->categorydisplay == 1) {
                    /** Category display function */
                    $output .= $this->displayCategory( $vid );
                }
            }
            

            /**  Rating starts here for video details page  */
            if ($this->_post_type === FLICHEVIDEOGALLERY || ($this->_page_post_type === FLICHEVIDEOGALLERY)) {
                /** Set ratings control if enabled for player */
            	if ($configXML->ratingscontrol == 1) {
                    $ratingscontrol = true;
                } else {
                    $ratingscontrol = false;
                }
            } /** Set ratings control if enabled for video */
             else if (isset ( $arguments['ratingscontrol'] ) && $arguments['ratingscontrol'] == 'on') {
                $ratingscontrol = true;
            } else {
                $ratingscontrol = false;
            }
            /** Show ratings star avg */
            if ($ratingscontrol) {
                $ratestar = getRatingValue ( $rate, $ratecount, 'calc' );
                /** Display ratings under player */
                $output   .= $this->displayRatings ($ratestar, $ratecount, $video_div_id, $vid, $video_id);
            }
            /** Rating ends here */
            $output   .= '</div>';
            
            

            if ($this->_post_type === FLICHEVIDEOGALLERY || $this->_page_post_type === FLICHEVIDEOGALLERY) {
                
                /** Display tag info */
                if ( !empty ( $tag_name ) && $configXML->tagdisplay == 1) { 
                    $output   .= '<div class="video-page-tag"><strong>' . __ ( 'Tags', FLICHE_VGALLERY ) . '          </strong>: ' . $tag_name . ' ' . '</div>';
                }
                
                /** Check if video url is YouTube */
                if (strpos ( $video_url, 'youtube' ) > 0) {
                    $video_thumb  = $this->_protocolURL . 'img.youtube.com/vi/' . getYoutubeVideoID ( $video_url ) . '/hqdefault.jpg';
                }
                
                /** Display description */
              	if( !empty ($description)) {
        				    $removequotedescription = str_replace ( '"', '', $description );
        				    $videodescription = str_replace ( "'", '', $removequotedescription );
                }
        				/** Show blog content when description is not available */
        				else {
        				    $videodescription = get_bloginfo('name');
        				}



                /** Check amazon s3 bucket is enabled */
                if ($fetched[0]->amazon_buckets == 1) {
                    $video_thumb = '';
                }



                /** Load rss url link */
                $rs_url     = $this->_site_url . '/wp-admin/admin-ajax.php?action=rss&type=video&vid=' . $vid;
                $rss_image  = getImagesDirURL() .'/rss_icon.png' ;
                /** Show social icons below player */
                if ($show_social_icon) {
                    /** Function to display social icons
                     * and rss icon */
                    $output .= $this->displaySocialIcons ( $configXML->keyApps, $videodescription,  $video_thumb, $video_title);
                    /** Show rss url link and image
                     * Build rss icon div */
                    if ($show_rss_icon) {
                      $output .= '<div class="floatleft rssfeed">&nbsp;&nbsp;<a href="' . $rs_url . '"><img src="' . $rss_image . '"></a></div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix">';
                }

                
                $output .= '<div class="video-cat-thumb">';
                /** Show rss icon enable / disable  */
                if ($show_rss_icon && !$show_social_icon) {
                    $rs_url = $this->_site_url . '/wp-admin/admin-ajax.php?action=rss&type=video&vid=' . $vid;
                    $rss_image = getImagesDirURL() .'/rss_icon.png' ;
                    $output .= '<div class="video-socialshare"><div class="floatleft rssfeed">&nbsp;&nbsp;<a href="' . $rs_url . '"><img src="' . $rss_image . '"></a></div></div>';
                }
                /** Show or hide embed|iframe|report video option */
                if ($configXML->embed_visible == 1) {
                  $output .= '<a href="javascript:void( 0 )" onclick="enableEmbed();" class="embed" id="allowEmbed"><span class="embed_text">' . __ ( 'Embed&nbsp;Code', FLICHE_VGALLERY ) . '</span><span class="embed_arrow"></span></a>';
                }
                if (isset ( $player_controls['iframe_visible'] ) && ($player_controls['iframe_visible'])) {
                  $output .= '<a href="javascript::void(0);" onclick="view_iframe_code();" id="iframe_code" class="embed"><span class="embed_text">' . __ ( 'Iframe', FLICHE_VGALLERY ) . '</span><span class="embed_arrow"></span></a>';
                }
                if (isset ( $player_controls['report_visible'] ) && ($player_controls['report_visible'])) {
                  $output .= '<a href="javascript:void(0)" onclick="reportVideo();" class="embed" id="allowReport"><span class="embed_text">' . __ ( 'Report&nbsp;Video', FLICHE_VGALLERY ) . '</span><span class="embed_arrow"></span></a>';
                }


                /** Condition for embed code */
                if ($fetched[0]->file_type == 5 && !empty ( $fetched[0]->embedcode )) {

                  $embed_code = stripslashes ( $fetched[0]->embedcode );

                } else {

                  /** Display embed code */
                  $embed_code = '<embed src="' . $this->_swfPath . '" flashvars="' . $flashvars . 
                    '&amp;shareIcon=false&amp;email=false&amp;showPlaylist=false&amp;zoomIcon=false&amp;copylink=' . 
                    get_permalink () . '&amp;embedplayer=true" width="' . $width . '" height="' . $height . 
                    '" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" wmode="transparent">';

                }
                

                /** Call function to display report video option */
                $output .= $this->displayReportVideo($embed_code, $video_slug, $current_user->user_email);


                /** Load embed code */
                if ($fetched[0]->file_type == 5 && $fetched[0]->embedcode) {
                  $iframe_code = stripslashes ( $fetched[0]->embedcode );
                } else {
                	/** Load iframe code */
                  $iframe_code = '<iframe src="' . $this->_swfPath . '?' . $flashvars . '&amp;shareIcon=false&amp;email=false&amp;showPlaylist=false&amp;zoomIcon=false&amp;copylink=' . get_permalink () . '&amp;embedplayer=true" frameborder="0" width="' . $width . '" height="' . $height . '" ></iframe>';
                }
                $output .=  '<textarea row="7" col="60" id="iframe-content" name="iframe-content" style="display:none;" onclick="this.select();">' . 
                                $iframe_code . 
                            '</textarea><input type="hidden" value="" id="iframeflag" name="iframeflag" />';


                /** Show /hide video description. */
                if ($configXML->showTag) {
                  $output .= '<div style="clear: both;"></div><div class="video-page-desc">' . apply_filters ( 'the_content', $description ) . '</div>';
                }


                $output .= '</div></div>';
            }
            $output .= '</div></div>';
            

            /** Enable/disable Related videos slider */
            $flag = 0;
            if( $vid  && isset ( $arguments['playlistid'] ) && isset ( $arguments['relatedvideos'])  && $arguments['relatedvideos'] == 'on') {
              $flag = 1;
            }
            if ( $flag == 1 || ($this->_post_type === FLICHEVIDEOGALLERY || $this->_page_post_type === FLICHEVIDEOGALLERY) && $player_controls['show_related_video'] == 1 ) {
              /** Call function to display related videos slider */
              $output .= $this->relatedVideoSlider ( $vid, $video_playlist_id, $pluginflashvars, $width, $height, $video_div_id );
            }

        }
        /** END 1 == 0 */


            /** RETURN HTML OUTPUT */
            #return $output;
            /** To display video comments section */
            return $output . $this->videoComments($configXML);
            

        }  
    /** FlicheVideoDetailView class ends  */
    } 
/** Check FlicheVideoDetailView class exists if ends */
} else {
    /** Else display flicheVideo exists message */
    echo 'class flicheVideo already exists';
}
?>