<?php
/**
 *  Wordpress video gallery Featured videos widget.
 *  
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/**
 * This class is used to create featured videos widget
 *
 * @author user
 */
class Widget_FlicheVideosWidget_init extends WP_Widget {
  
  /** Init featured videos widget */
  function __construct() {
    /** Set classname, description in an array */
    $widget_ops = array ( 'classname' => 'Widget_FlicheVideosWidget_init ', 'description' => 'Display Popular / Recent / Featured / Random and Related videos widget' );
    #$this->WP_Widget ( 'Widget_FlicheVideosWidget_init', 'Fliche Video Widgets', $widget_ops );
    parent::__construct ( 'Widget_FlicheVideosWidget_init', 'Fliche Video Widgets', $widget_ops );
  }
  
  /**
   * This function is used to create featured videos widget in admin
   *
   * @param object $instance          
   */
  function form($instance) {
    $instance = wp_parse_args ( ( array ) $instance, array ( 'title' => 'Videos widget', 'videowidgettype' => '', 'show' => '3' ) );
    
    /** Set title, number of videos to be shown option in widget */
    $title = esc_attr ( $instance ['title'] );
    
    /** Set widget type */
    $videowidgettype = esc_attr ( $instance ['videowidgettype'] );
    $show = isset ( $instance ['show'] ) ? absint ( $instance ['show'] ) : 3;
    $selected = 'selected=selected';
    
    /**
     * Admin fields to display feature widget title
     * and get display input field video limit
     */
    ?>
    <p> <label for="<?php echo $this->get_field_id('title'); ?>">Title: 
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
    name="<?php echo $this->get_field_name('title'); ?>" type="text" 
    value="<?php echo $title; ?>" /> 
    </label> </p>
    <p> <label>Select category: <?php 
    /** 
     * Select option to display widget type
     * Featured,popular,random,recent,related
     */ ?> 
     <select class="widefat" name="<?php echo $this->get_field_name('videowidgettype'); ?>" 
     id="<?php echo $this->get_field_id('videowidgettype'); ?>"> 
     <option>--Select option--</option> 
     <option <?php if ($videowidgettype == 'featured') {
      echo $selected;
      } ?> value='featured'>Featured videos</option> 
      <option <?php if ($videowidgettype == 'popular') { 
        echo $selected; 
      } 
      ?> value='popular'>Popular videos</option> 
      <option <?php if ($videowidgettype == 'random') {
       echo $selected;
      } ?> 	value='random'>Random videos</option> 
      <option <?php if ($videowidgettype == 'recent') { 
        echo $selected; 
      } ?> 
      value='recent'>Recent videos</option> 
      <option <?php if ($videowidgettype == 'related') {
        echo $selected;
      } ?> 
      value='related'>Related videos</option> </select> 
     </label> </p>
<?php 
    /**
     * Get no of videos to be shown
     * Display number of videos to be shown in widget if avail
     */ ?>
      <p> <label for="<?php echo $this->get_field_id('show'); ?>">Show: <input 
      class="widefat" id="<?php echo $this->get_field_id('show'); ?>" 
      name="<?php echo $this->get_field_name('show'); ?>" type="text" 
      value="<?php echo $show; ?>" /> </label> </p>
<?php 
}
  
  /**
   * Function is used to update widget
   *
   * @param unknown $new_instance          
   * @param unknown $old_instance          
   * @return unknown
   */
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    /** Get new title */
    $instance ['title'] = $new_instance ['title'];
    
    /** Get new widget type */
    $instance ['videowidgettype'] = $new_instance ['videowidgettype'];
    
    /** Get new no of videos to show */
    $instance ['show'] = $new_instance ['show'];
    return $instance;
  }
  
  /**
   * Function is used to ftech related videos
   *
   * @param videos $show          
   * @param title $title          
   * @return unknown
   */
  function related($show, $title) {
    /** Get $wpdb and $post variable */
    global $wpdb;
    global $post;
    
    /** initalise variables to empty */
    $playlistID = $playlist_slugname = $relatedVideos = '';
    
    /** Get current page or post type */
    $post_type = filter_input ( INPUT_GET, 'post_type' );
    
    /** Get post type */
    $page_post_type = get_post_type ( get_the_ID () );
    /** Check whether the page is video detail page */
    if ($post_type == FLICHEVIDEOGALLERY || $page_post_type == FLICHEVIDEOGALLERY) {
      /** Related videos widget starts here */
      /** If exists then get video id from db */
      $videoID = $wpdb->get_var ( 'SELECT vid FROM ' . $wpdb->prefix . 'hdflvvideoshare WHERE slug=' . $post->ID );
      
      /** Check video id is exist */
      if (! empty ( $videoID )) {
        /** If exist then get playlist id for that video id */
        $video_playlist_id = $wpdb->get_var ( 'SELECT playlist_id FROM ' . $wpdb->prefix . 'hdflvvideoshare_med2play WHERE media_id=' . $videoID );
        
        /** Set order for relate videos query */
        $thumImageorder = ' w.vid DESC ';
        
        /** Get related videos from db */
        $relatedVideos = getWidgetVideos ( 'related', $thumImageorder, $show, $videoID, $video_playlist_id );
        
        /** Check related videos are exist */
        if (! empty ( $relatedVideos )) {
          /** Get playlit id, playlist slug name */
          $playlistID = $relatedVideos [0]->playlist_id;
          $playlist_slugname = $relatedVideos [0]->playlist_slugname;
        } 
        /** If playist id not available get video Id */
        else {
          $playlistID = $videoID;
        }
      }
      if (! empty ( $playlistID )) {
        /** Display related videos widget */
        echo displayWidgetVideos ( $title, 'related', $relatedVideos, $show, $playlistID, $playlist_slugname );
      }
    }
  }
  
  /**
   * Function is used to display videos in widget
   *
   * @param unknown $args          
   * @param unknown $instance          
   */
  function widget($args, $instance) {
    /** Get featured widget title and set default number of videos to be shown as limit 3 */
    $title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );
    $videowidgettype = $instance ['videowidgettype'];
    $show = 3;
    
    /** Get "number of featured videos to be shown" from admin */
    if ($instance ['show'] && absint ( $instance ['show'] )) {
      $show = $instance ['show'];
    }
    if ($instance ['videowidgettype']) {
      $videowidgettype = $instance ['videowidgettype'];
    }
    
    /** Before widget functions */
    echo $args ['before_widget'];
    
    /** Get video order from settings data
     * Switch condition for each widget */
    switch ($videowidgettype) {
      /** Case featured video widget */
      case 'featured' :
        $player_color = getPlayerColorArray ();
        $recent_video_order = $player_color ['recentvideo_order'];        
        /** Get order to fetch featured videos */
        $thumImageorder = getVideoOrder ( $recent_video_order );
        $features = getWidgetVideos ( 'feature', $thumImageorder, $show, '', '' );
        /** Display featured videos */
        echo displayWidgetVideos ( $title, 'featured', $features, $show, '', '' );
        echo $args ['after_widget'];
        break;
      /** Case popular video widget */
      case 'popular' :
        $thumImageorder = ' w.hitcount DESC ';
        /** Get popular videos from db */
        $populars = getWidgetVideos ( 'popular', $thumImageorder, $show, '', '' );
        /** Display popular videos */
        $output = displayWidgetVideos ( $title, 'popular', $populars, $show, '', '' );
        /** echo popular widget closing tag */
        echo $output . $args ['after_widget'];
        break;
      /** Case random video widget */
      case 'random' :
        $thumImageorder = ' RAND() ';
        $posts = getWidgetVideos ( 'random', $thumImageorder, $show, '', '' );
        /** Display random videos */
        $output = displayWidgetVideos ( $title, 'random', $posts, $show, '', '' );
        /** echo random videos widget opening, closing tag */
        echo $output . $args ['after_widget'];
        break;
      /** Case recent video widget */
      case 'recent' :
        $thumImageorder = ' w.vid DESC ';
        $posts = getWidgetVideos ( 'recent', $thumImageorder, $show, '', '' );
        /** Display recent videos */
        echo displayWidgetVideos ( $title, 'recent', $posts, $show, '', '' );
        echo $args ['after_widget'];
        break;
      /** Case recent video widget */
      case 'related' :
        /** Function call for related videos
         * Show and title params are send for helper */
        $this->related ( $show, $title );
        /** Related videos widget ends here */
        /** After widget functions */
        echo $args ['after_widget'];
        break;
      default :
        break;
    }
  }
}
/** Run code and init videos widget */
add_action ( 'widgets_init', create_function ( '', 'return register_widget("Widget_FlicheVideosWidget_init");' ) );
?>