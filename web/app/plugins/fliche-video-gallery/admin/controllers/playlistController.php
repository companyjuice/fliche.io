<?php
/**  
 * Video playlist admin controller file.
 *
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Including Playlist model file for get database information. */
include_once ($adminModelPath . 'playlist.php');

/** Checks if the PlaylistController class has been defined starts */
if ( !class_exists ( 'PlaylistController' )) { 
  /**
   * VideoadController class starts
   */
  class PlaylistController extends PlaylistModel { 
    /**
     * Constructor starts
     */
    public function __construct() 
    {  
      parent::__construct ();
      /** Get PlaylistssearchQuery from query parameters */
      $this->_playlistsearchQuery = filter_input ( INPUT_POST, 'PlaylistssearchQuery' );
      /** Get playlistadd from query parameters */
      $this->_addnewPlaylist      = filter_input ( INPUT_POST, 'playlistadd' );
      /** Get status from query parameters */
      $this->_status              = filter_input ( INPUT_GET, 'status' );
      /** Get playlistsearchbtn from query parameters */
      $this->_searchBtn           = filter_input ( INPUT_POST, 'playlistsearchbtn' );
      /** Get update from query parameters */
      $this->_update              = filter_input ( INPUT_GET, 'update' );
      /** Get add from query parameters */
      $this->_add                 = filter_input ( INPUT_GET, 'add' );
      /** Get del from query parameters */
      $this->_del                 = filter_input ( INPUT_GET, 'del' );
      /** Get order from query parameters */
      $this->_orderDirection      = filter_input ( INPUT_GET, 'order' );
      /** Get orderby from query parameters */
      $this->_orderBy             = filter_input ( INPUT_GET, 'orderby' );
    }

    /**
     * Function for add/ update playlist data.
     */
    public function add_playlist() 
    {
      global $wpdb;

      /** Check if sttus is exists */
      if ( isset ( $this->_status ) ) {
        /** Call function to update stauts */
        $this->status_update ( $this->_playListId, $this->_status );
      }

      /** Check whether to add new playlist */
      if ( isset ( $this->_addnewPlaylist ) ) { 

        /** Get playlistname parameters from request URL */
        $playlistName       = filter_input ( INPUT_POST, 'playlistname' );

        /** Sanitize playlist title */
        $playlist_slugname  = sanitize_title ( $playlistName );

        /* -||- */
        /** Get playlistdesc parameters from request URL */
        $playlistDesc       = filter_input ( INPUT_POST, 'playlistdesc' );
        /** Get playlistimage parameters from request URL */
        $playlistImage      = filter_input ( INPUT_POST, 'playlistimage' );
        /** Get playlistthumb parameters from request URL */
        $playlistThumb      = filter_input ( INPUT_POST, 'playlistthumb' );
        /** Get playlistparent parameters from request URL */
        $playlistParent     = filter_input ( INPUT_POST, 'playlistparent' );
        /* -||- */

        /** Get ispublish parameters from request URL */
        $playlistPublish    = filter_input ( INPUT_POST, 'ispublish' );


        /** Check if given playlist name exists in playlist table */
        $playlist_slug      = $this->_wpdb->get_var ( 
          'SELECT COUNT(playlist_slugname) FROM ' . $wpdb->prefix . 
          'hdflvvideoshare_playlist WHERE playlist_slugname LIKE "' . $playlist_slugname . '%"' 
        );

        /** Check playlist slug is already exist  */
        if ( $playlist_slug > 0 ) {
          /** If exist then increase count with slug name */
          $playlist_slugname = $playlist_slugname . '-' . intval ( $playlist_slug + 1 );
        }

        /** Store all parameters into single array */
        $playlistData = array ( 
          'playlist_name'     => $playlistName, 
          'playlist_slugname' => $playlist_slugname, 
          'parent_id'         => $playlistParent, 
          'playlist_desc'     => $playlistDesc, 
          'playlist_image'    => $playlistImage, 
          'playlist_thumb'    => $playlistThumb, 
          'is_publish'        => $playlistPublish 
        );
        
        /**
         * Check whether playlist id is exist
         * If exist, then update records in db, 
         * else insert new record  
         */
        if ( $this->_playListId ) {

          /** Update existing playlist in db */
          $updateflag = $this->playlist_update ( $playlistData, $this->_playListId );

          /** Redirect to playlist page after updating record */ 
          $this->redirectPlaylistPage ( $updateflag, 'update', '' );

        } else {

          /** Ordering starts from 0, 
           * so count of playlist is order + 1 value  */
          $ordering = getAllPlaylistCount ();
          $playlistData ['playlist_order'] = $ordering;

          /** Insert new playlist into db */
          $addflag = $this->insert_playlist ( $playlistData );

          /** Redirect to playlist page once action is done  */
          $this->redirectPlaylistPage ( $addflag, 'add', '' );

        }
      }
    }
    
    /**
     * Function to redirect to category page after done the action
     * 
     * @param unknown $statusFlag
     * @param unknown $action
     * @param unknown $type
     */
    public function redirectPlaylistPage ( $statusFlag, $action, $type ) 
    {
      /** Redirect to playlist page based on the status */
      if (! $statusFlag && $action != 'status' ) {
        /** For unsuccess action
         * Check action is update. 
         * If yes then redirect URL with playlist id */
        if($action == 'update') {
          $url = 'admin.php?page=newplaylist&playlistId=' . $this->_playListId . '&' . $action .'=0';
        } 
        else {
          $url = 'admin.php?page=playlist&' . $action .'=0';
        }
      } 
      else {
        /** For success action */
        if ( $action == 'update' ) {
          $url = 'admin.php?page=newplaylist&playlistId=' . $this->_playListId . '&' . $action .'=1';
        } 
        else {
          $url = 'admin.php?page=playlist&' . $action .'=1';
        }
      }
      /**
       * Check action is status. 
       * If yes check type. 
       * If type is 1 (publish) then redirect value with 1.
       * Else (unpublish) redirect with 0.
       */
      if ( $action == 'status' ) {
        if ( $type == 1) {
          $url = 'admin.php?page=playlist&' . $action .'=1';
        } 
        else {
          $url = 'admin.php?page=playlist&' . $action .'=0';
        }
      }
      /** Redirect to the corresponding URL */          
      echo '<script>window.open( "' . $url . '","_top",false )</script>';
    }
    
    /**
     * Function to set message starts
     * 
     * @param unknown $msg
     * @param unknown $message_div
     * @return multitype:unknown string
     */
    public function set_message ( $msg, $message_div ) 
    {
      /** Set status for the corresponding action */
      $this->_msg = __ ( 'Category', FLICHE_VGALLERY ) . ' ' . $msg . ' '. __ ( 'Successfully ...', FLICHE_VGALLERY ) ;
      /** Return message array */
      return array ( 0 => $this->_msg, 1 => $message_div );
    }
    
    /**
     * Function to display message starts
     *
     * @return multitype:string
     */
    public function get_message () 
    {
      $result = '';
      /** Check update action is success */
      if($this->_update == '1') {
        $result = $this->set_message( __ ( 'Updated', FLICHE_VGALLERY ), 'addcategory');
      }
      /** Check update action is unsuccess */
      if($this->_update == '0' ) {
          $result = $this->set_message( __ ( 'Not Updated', FLICHE_VGALLERY ), 'addcategory');
      }
      /** Check add action is success */
      if ($this->_add == '1') {
        $result = $this->set_message( __ ( 'Added', FLICHE_VGALLERY ), 'addcategory');
      }
      /** Check delete action is success */
      if ($this->_del == '1') {
        $result = $this->set_message( __ ( 'Deleted', FLICHE_VGALLERY ), 'addcategory');
      }
      /** Check publish action is success */
      if ($this->_status == '1') {
        $result = $this->set_message( __ ( 'Published', FLICHE_VGALLERY ), 'category');
      } 
      /** Check unpublish action is success */
      if( $this->_status == '0') {
          $result =  $this->set_message( __ ( 'Unpublished', FLICHE_VGALLERY ), 'category');
      }
      return $result;
    }
    
    /**
     * Function to order playlist data based on the selected fields
     */
    public function playlist_data () 
    {
      /** Store order by fileds into single array */
      $orderBy  = array ( 'id', 'title', 'desc', 'parent', 'publish', 'sorder' );
      $order    = 'id';
      /** Check if order-by field exists within an array
       * Set order by values  */
      if (isset ( $this->_orderBy ) && in_array ( $this->_orderBy, $orderBy )) {
        $order = $this->_orderBy;
      }
      /** Assign order field based on selection */
      switch ($order) {
        case 'id' :
          $order = 'pid';
          break;
        case 'title' :
          $order = 'playlist_name';
          break;
        /* -||- */
        case 'parent' :
          $order = 'parent_id';
          break;
        /* -||- */
        case 'desc' :
          $order = 'playlist_desc';
          break;
        /* -||- */
        case 'image' :
          $order = 'playlist_image';
          break;
        /* -||- */
        case 'thumb' :
          $order = 'playlist_thumb';
          break;
        /* -||- */
        case 'publish' :
          $order = 'is_publish';
          break;
        case 'sorder' :
          $order = 'playlist_order';
          break;
        default :
          $order = 'pid';
          break;
      }
      /** Call function to order grid data based on the selection */
      return $this->get_playlistdata ( $this->_playlistsearchQuery, $this->_searchBtn, $order, $this->_orderDirection );
    }

      
    /**
     * Function to delete, publish or unpublish the playlist
     */
    public function get_delete () 
    {
      /** Get playlistapply parameter from request URL */
      $playlistApply      = filter_input ( INPUT_POST, 'playlistapply' );
      /** Get playlistactionup parameter from request URL */
      $playlistActionup   = filter_input ( INPUT_POST, 'playlistactionup' );
      /** Get playlistactiondown parameter from request URL */
      $playlistActiondown = filter_input ( INPUT_POST, 'playlistactiondown' );
      /** Get pid parameter from request URL */
      $playListcheckId    = filter_input ( INPUT_POST, 'pid', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
      /** Get selected playlist id's array and combine */
      if ( is_array ( $playListcheckId ) ) {
        $playListId = implode ( ',', $playListcheckId );
      }
      /** Check apply action is done */
      if ( isset ( $playlistApply ) && !empty ( $playListId ) ) {
        /** Check playlist delete action is done */
        if ($playlistActionup == 'playlistdelete' || $playlistActiondown == 'playlistdelete') {
          /** Call function to delete playlist */
          $deleteflag = $this->playlist_delete ( $playListId );
          $this->redirectPlaylistPage ( $deleteflag, 'del', '1' );
        } 
        elseif ($playlistActionup == "playlistpublish" || $playlistActiondown == 'playlistpublish') {
          /** Check playlist publish action is done 
           * Ccall function to publish data */
          $publishflag = $this->playlist_multipublish ( $playListId );
          $this->redirectPlaylistPage ( $publishflag, 'status', '1' );
        } 
        else {  
          /** Check playlist publish action is done 
           * Call function to publish data */
          if ($playlistActionup == "playlistunpublish" || $playlistActiondown == "playlistunpublish") {
            $unpublishflag = $this->playlist_multiunpublish ( $playListId );
            $this->redirectPlaylistPage ( $unpublishflag, 'status', '0' );
          }
        }
      /** Checking apply action ends */
      }
    }
  /** PlaylistController class ends */
  } 
/** Checking playlist controller class if ends */
}

/** Creating object for PlaylistController class */ 
$playlistOBJ    = new PlaylistController (); 

/** Assign class variables into local variables */
$playListId     = $playlistOBJ->_playListId;
$searchMsg      = $playlistOBJ->_playlistsearchQuery;
$searchBtn      = $playlistOBJ->_searchBtn;
$status         = $playlistOBJ->_status;
$update         = $playlistOBJ->_update;

/** Call function with PlaylistController object */
$playlistOBJ->add_playlist ();

/** Call function to delete playlist data */
$playlistOBJ->get_delete ();

/** Call function to display playlist data in grid section */
$gridPlaylist   = $playlistOBJ->playlist_data ();

/** Call function to count playlist data */
$playlist_count = $playlistOBJ->playlist_count ( $searchMsg, $searchBtn );

/** Check playlist id exists */
if (! empty ( $playListId )) {  
  /** Check status or update is done. */
  if (isset ( $_GET ['status'] ) || isset ( $_GET ['update'] )) {
    $playlistEdit = '';
  } 
  else {
    /** If not call function to edit playlist data */
    $playlistEdit = $playlistOBJ->playlist_edit ( $playListId );
  }
} 
else {
  $playlistEdit = '';
}

/** Call function to get message for the corresponding action */
$displayMsg = $playlistOBJ->get_message ();

/** Include playlist view page */
require_once (FLICHE_VGALLERY_BASEDIR . DS . 'admin/views/playlist/playlist.php');

/* -|end|- */