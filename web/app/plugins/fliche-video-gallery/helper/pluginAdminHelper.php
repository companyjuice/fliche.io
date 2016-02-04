<?php
/**
 * Function to display admin tabs
 * 
 * @param unknown $section
 * @return string
 */
function displayAdminTabs ( $section ) {
  /** Check whether the tab is playlist / ads / gads / settings and videos and set styles */
  switch( $section ) {
      case 'playlist' :
          /** Set playlist tab is active */
          $playlist = 'nav-tab-active';
          $video = $ads = $settings = $gads = '';
          break;
      case 'ads' :
          /** Set ads tab is active */
          $ads = 'nav-tab-active';
          $video = $playlist = $settings = $gads = '';
          break;
      case 'gads' :
          /** Set google ads tab is active */
          $gads = 'nav-tab-active';
          $video = $playlist = $settings = $ads = '';
          break;
      case 'settings' :
          /** Set settings tab is active */
          $settings = 'nav-tab-active';
          $video = $playlist = $gads = $ads = '';
          break;
      case 'videos' :
      default:
          /** Set videos tab is active */
          $video = 'nav-tab-active';
          $playlist = $ads = $settings = $gads = '';
          break;
  }
  /** Display admin tabs for plugin */
  return '<h2 class="nav-tab-wrapper">
    <a href="?page=video" class="nav-tab ' . $video . ' "> '. __( 'Videos', FLICHE_VGALLERY ) . '</a>
    <a href="?page=playlist" class="nav-tab ' . $playlist .'">' .__( 'Video Categories', FLICHE_VGALLERY ) . '</a>
    <a href="?page=hdflvvideosharesettings" class="nav-tab ' . $settings . ' ">'. __( 'Video Settings', FLICHE_VGALLERY ) .'</a>
  </h2>';
  /*
    <a href="?page=videoads" class="nav-tab ' . $ads . '">' . __( 'Video Ads', FLICHE_VGALLERY ) . '</a>
    <a href="?page=googleadsense" class="nav-tab '. $gads .'">'. __( 'Google AdSense', FLICHE_VGALLERY ) .'</a>
  */
}

/**
 * Function to display status when action is done
 * 
 * @param string $displayMsg
 */
function displayStatusMeassage ( $displayMsg ) {
    /** Check meassage is received */
    if ( $displayMsg ) {
        /** Display the meassage and return */
        return '<div class="updated below-h2"> <p> '.  $displayMsg . '</p> </div>';
    }
}

/**
 * Function to display filter option in playlist / video ads and google adsense page
 * 
 * @param unknown $name
 * @param unknown $position
 * @return string
 */
function adminFilterDisplay ( $name,  $position ) {
  $content = '';   
  /** Set filter action name */
  $filterName   = $name . 'action' . $position;
  /** Set filter action publish name */
  $publishVal   = $name . 'publish';
  /** Set filter action unublish name */
  $unpublishVal = $name . 'unpublish';
  /** Set filter action delete name */
  $deleteVal    = $name . 'delete';
  /** Set apply button name */
  $applyName    = $name . 'apply';
  
  /** Check whether the page is google adsense page */
  $content = '<option value="' . $publishVal . '">' . __( 'Publish',FLICHE_VGALLERY) . '</option>
  <option value="' . $unpublishVal . '">'. __( 'Unpublish', FLICHE_VGALLERY ) .'</option>';
  
  /** Display filter option in plugin admin pages */ 
  return '<select name="' . $filterName .'" id="' . $filterName . '">
                  <option value="-1" selected="selected"> ' . __( 'Bulk Actions', FLICHE_VGALLERY ) . '</option>
                  <option value="' . $deleteVal . '">' . __( 'Delete', FLICHE_VGALLERY ) . '</option>'
                      . $content . '
                  </select> 
                  <input type="submit" name="' . $applyName . '"  class="button-secondary action" value="' . __( 'Apply', FLICHE_VGALLERY ) . '"> ';
} 
/**
 * Function to get TouTube data from remote URL
 *
 * @param unknown $url
 * @return unknown
 */
function hd_getyoutubepage( $url ) {
  /** Get information from remote URL */
  $apidata = wp_remote_get($url);
  /** return fetched information */
  return wp_remote_retrieve_body($apidata);
}
/**
 * Video Detail Page action Ends Here
 *
 * Function for adding video ends
 *
 * @param unknown $youtube_media
 * @return void|unknown
 */
function hd_getsingleyoutubevideo( $youtube_media ) {
  /** Check YouTube video id is exist */
  if ( $youtube_media == '' ) {
    /** If not then return empty */
    return;
  }
  /** Get YouTube Api key form settings */
  $setting_youtube = getPlayerColorArray();
  $youtube_api_key = $setting_youtube['youtube_key']; 
  /** Check Api key is applied in settings */
  if( !empty($youtube_api_key)) {
    /** Get URL to get Youtube video details */
    $url = 'https://www.googleapis.com/youtube/v3/videos?id='.$youtube_media.'&part=contentDetails,snippet,statistics&key='.$youtube_api_key;
    /** Call function to get detila from the given URL */
    $video_details =  hd_getyoutubepage( $url ); 
    /** Decode json data and get details */
    $decoded_data = json_decode($video_details);
    /** return YouTube video details */
    return get_object_vars($decoded_data); 
  } else{
    /** If key is not applied, then dipslya error message */
    render_error( __( 'Could not retrieve Youtube video information. API Key is missing', FLICHE_VGALLERY ) );
  }
  exitAction ('');
}

/**
 * Function to set message starts
 *
 * @return multitype:string
 */
function set_message ( $type, $msg ) {
  /** Return status based on the performed action in all videos page */
  return $type . ' ' . $msg . ' '. __ ( 'Successfully ...', FLICHE_VGALLERY ) ;
}
?>