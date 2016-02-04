<?php
/***/
/* NONE OF THIS RUNS RIGHT NOW */

          /** Embed player code in detail page
           * Get width and height for player */
          if ($file_type == 5 && !empty ( $relFet->embedcode )) {
          	/** Embed code for fearured 
          	 * Width for related videos */
            $relFetembedcode    = stripslashes ( $relFet->embedcode );
            $relFetiframewidth  = preg_replace ( array ( '/width="\d+"/i' ), array ( sprintf ( 'width="%d"', $width ) ), $relFetembedcode );
            if ($mobile === true) {
              /** Embed code type for mobile view */
              $player_values  = htmlentities ( $relFetiframewidth );
            } else {
              $player_values  = htmlentities ( preg_replace ( array ( '/height="\d+"/i' ), array ( sprintf ( 'height="%d"', $height ) ), $relFetiframewidth ) );
            }

          } 
          else {

            /** Code for mobile device */
            if ( 1 == 0 && $mobile) {

              /** Check for youtube video  */
              if ((strpos ( $reafile, 'youtube' ) > 0 )) {
                $reavideourl  = 'http://www.youtube.com/embed/' . getYoutubeVideoID ( $reafile );
                /** Generate youtube embed code for html5 player */
                $player_values = htmlentities ( '<iframe  width="100%" type="text/html" src="' . $reavideourl . '" frameborder="0"></iframe>' );
              } else if ($file_type != 5) {
                /** Check for upload, URL and RTMP videos */
                switch ( $file_type ) {
                  case 2:
                    $reavideourl = $this->_uploadPath . $reafile;
                    break;
                  case 4:
                    $streamer = str_replace ( 'rtmp://', 'http://', $media->streamer_path );
                    $reavideourl = $streamer . '_definst_/mp4:' . $reafile . '/playlist.m3u8';
                    break;
                  default: break;
                }
                /** Generate video code for html5 player */
                $player_values = htmlentities ( '<video width="100%" id="video" poster="' . $imageFea . '"   src="' . $reavideourl . '" autobuffer controls onerror="failed( event )">' . $htmlplayer_not_support . '</video>' );
              } else {
                $player_values = '';
              }

            } 
            else {

              /* CUSTOM CODE -- MM -- HTML5 Video Player ???????????????? */
              if ( 1 == 0 ) {

                /* Flash player code 
                $player_values   = htmlentities ( '<embed src="' . $this->_swfPath . '" flashvars="' . $pluginflashvars . '&amp;mtype=playerModule&amp;vid=' . $relFet->vid . '" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" wmode="transparent">' );
                */


                /* If browser is detect then play videos via flash player using embed code 
                $div            .= '<embed id="player" src="' . $swf . '"  flashvars="baserefW=' . $this->_siteURL . $baseref . $showplaylist . '&amp;mtype=playerModule" width="' . $settingsData->width . '" height="' . $settingsData->height . '"   allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" wmode="transparent" />';*/
                $player_values .='-------------';
                $player_values .='<pre>';
                $player_values .= var_dump($swf);
                $player_values .='</pre>';
                $player_values .='-------------';
                $player_values .='<pre>';
                $player_values .= var_dump($this->_siteURL);
                $player_values .= var_dump($baseref);
                $player_values .= var_dump($showplaylist);
                $player_values .='</pre>';
                $player_values .='-------------';
                $player_values .='<pre>';
                $player_values .= var_dump($settingsData->height);
                $player_values .= var_dump($settingsData->width);
                $player_values .='</pre>';
                $player_values .='-------------';

                $player_values .= '
                  <link href="http://vjs.zencdn.net/5.0/video-js.min.css" rel="stylesheet">
                  <script src="http://vjs.zencdn.net/5.0/video.min.js"></script>
                ';
                $player_values .= '
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
                $player_values .="
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
              
              }
              /* END CUSTOM CODE -- MM -- HTML5 Video Player ????????????????????????????? */


            }
          }

/* end NONE OF THIS RUNS RIGHT NOW */
/***/