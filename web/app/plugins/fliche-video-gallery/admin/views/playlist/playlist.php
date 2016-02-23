<?php
/**
 * Video gallery admin playlist view+add+edit file
 * 
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** Get page values for playlist page */
$page = $class = '';
if (isset ( $_GET ['pagenum'] )) {
  $page = '&pagenum=' . $_GET ['pagenum'];
}
/** Get sort order URL to perform drg and drop for ordering  */
$sortOrderURL = get_site_url() . '/wp-admin/admin-ajax.php?action=vg_sortorder&type=2'. $page;
/** Assign sortorder url, plugin path in script */

/** Function to truncate elegantly to end of word */
function truncate ( $string, $length = 100, $append = "&hellip;" )
{
  $string = trim($string);

  if(strlen($string) > $length) {
    $string = wordwrap($string, $length);
    $string = explode("\n", $string, 2);
    $string = $string[0] . $append;
  }

  return $string;
}

?>
<script type="text/javascript">
    var sortorderURL = '<?php echo $sortOrderURL; ?>';
    var	videogallery_plugin_folder =  '<?php echo getImagesDirURL() ; ?>' ;
</script>

<div class="fliche_gallery hi"> 
<?php /** Playlist admin page Starts
       * Call function to display admin tabs in playlist page */
      #echo displayAdminTabs ( 'playlist' ) ;
      
      /**  Playlist add/ grid section Starts */ ?>
    <div class="wrap">

      <?php /** Display page title and icon image */ ?>
      <h1 class="option_title"> 
        <?php /* echo "<img src='" . getImagesDirURL() ."manage_list.png' alt='move' width='30'/>"; */ ?> 
        <?php esc_attr_e( 'Video Categories', FLICHE_VGALLERY ); ?> 
      </h1> 
      <?php /** Display page title and icon image */ ?>

<?php  /** Playlist add page starts "floatleft category_addpages" */ ?>
      <div class="">  
          <div class="fliche_gallery hi"> 
              
              
        <?php /** Gets status message from controller 
               * Then display the message for the coresponding action   */
              if ( $displayMsg && $displayMsg[1] == 'addcategory' ) {  
                  echo displayStatusMeassage ( $displayMsg [0] );
              } 
              
              /** Playlist add page form Starts  */ ?>
               <div id="post-body" class="has-sidebar"> 
                  <div id="post-body-content" class="has-sidebar-content"> 
                      <div class="stuffbox"> 
                          <div class="inside">

                            <?php 
                            /** 
                             * Check action: add || update
                             * Then display: page sub-title 
                             */
                            if ( $playListId  ) { ?> 
                              <h2 class="option_title"> <?php esc_attr_e( 'Update Category', FLICHE_VGALLERY ); ?> </h2> 
                            <?php } else { ?> 
                              <h2 class="option_title"> <?php esc_attr_e( 'Add New Category', FLICHE_VGALLERY ); ?> </h2> 
                            <?php } ?>

                            <?php /** Display playlist form to add playlist */?>
                              <form name="adsform" method="post" enctype="multipart/form-data" >
                              
                                  <table class="form-table">
                                      <?php 
                                        /** -||- Display form field to enter playlist title */ 
                                      ?>
                                      <tr> 
                                          <th scope="row"> <?php esc_attr_e( 'Title / Name', FLICHE_VGALLERY ) ?> </th>
                                          <td> <?php if ( isset( $playlistEdit->playlist_name ) ) { 
                                                        $playlist_name = $playlistEdit->playlist_name; 
                                                     } else { 
                                                        $playlist_name = ''; 
                                                     } ?>
                                                <input type="text" maxlength="200" id="playlistname" name="playlistname" value="<?php echo htmlentities( $playlist_name ); ?>" style="width: 400px;"  />
                                                <span id="playlistnameerrormessage" style="display: block;color:red; "></span> 
                                          </td>
                                      </tr>

                                      <?php 
                                        /** -||- Display form field to enter playlist parent id */ 
                                      ?>
                                      <tr> 
                                          <th scope="row"> <?php esc_attr_e( 'Parent', FLICHE_VGALLERY ) ?> </th>
                                          <td> <?php if ( isset( $playlistEdit->parent_id ) ) { 
                                                        $parent_id = $playlistEdit->parent_id; 
                                                     } else { 
                                                        $parent_id = ''; 
                                                     } ?>
                                                <input type="text" id="playlistparent" name="playlistparent" value="<?php echo htmlentities( $parent_id ); ?>" style="width: 400px;"  />
                                                <span id="playlistparenterrormessage" style="display: block;color:red; "></span> 
                                          </td>
                                      </tr>

                                      <?php 
                                        /** -||- Display form field to enter playlist description */ 
                                      ?>
                                      <tr> 
                                          <th scope="row"> <?php esc_attr_e( 'Description', FLICHE_VGALLERY ) ?> </th>
                                          <td>  <?php if ( isset( $playlistEdit->playlist_desc ) ) { 
                                                        $playlist_desc = $playlistEdit->playlist_desc; 
                                                     } else { 
                                                        $playlist_desc = ''; 
                                                     }
                                                /*<input type="text" id="playlistdesc" name="playlistdesc" value="<?php echo htmlentities( $playlist_desc ); ?>" style="width: 400px;"  />*/
                                                ?>
                                                <textarea id="playlistdesc" name="playlistdesc" style="width: 400px;"><?php echo htmlentities( $playlist_desc ); ?></textarea>
                                                <span id="playlistdescerrormessage" style="display: block;color:red; "></span> 
                                          </td>
                                      </tr>


                                      <?php 
                                        /** -||- Display form field to enter playlist image */ 
                                      ?>
                                      <tr> 
                                          <th scope="row"> <?php esc_attr_e( 'Featured Image URL', FLICHE_VGALLERY ) ?> </th>
                                          <td> <?php if ( isset( $playlistEdit->playlist_image ) ) { 
                                                        $playlist_image = $playlistEdit->playlist_image; 
                                                     } else { 
                                                        $playlist_image = ''; 
                                                     } ?>
                                                <input type="text" id="playlistimage" name="playlistimage" value="<?php echo htmlentities( $playlist_image ); ?>" style="width: 400px;"  />
                                                <span id="playlistimageerrormessage" style="display: block;color:red; "></span> 
                                          </td>
                                      </tr>

                                      <?php 
                                        /** -||- Display form field to enter playlist thumbnail */ 
                                      ?>
                                      <tr> 
                                          <th scope="row"> <?php esc_attr_e( 'Thumbnail Image URL', FLICHE_VGALLERY ) ?> </th>
                                          <td> <?php if ( isset( $playlistEdit->playlist_thumb ) ) { 
                                                        $playlist_thumb = $playlistEdit->playlist_thumb; 
                                                     } else { 
                                                        $playlist_thumb = ''; 
                                                     } ?>
                                                <input type="text" id="playlistthumb" name="playlistthumb" value="<?php echo htmlentities( $playlist_thumb ); ?>" style="width: 400px;"  />
                                                <span id="playlistthumberrormessage" style="display: block;color:red; "></span> 
                                          </td>
                                      </tr>

                                      
                                      <?php 
                                        /** -||- Display radio checkboxes for boolean publish option */ 
                                      ?>
                                      <tr> 
                                          <th scope="row"> <?php esc_attr_e( 'Publish', FLICHE_VGALLERY ); ?> </th>
                                          <td> <input type="radio" name="ispublish" id="published" value="1" checked="checked" 
                                                    <?php if ( isset( $playlistEdit->is_publish ) && $playlistEdit->is_publish == 1 ) {
                                                      echo 'checked="checked"';
                                                    } ?> /> 
                                                <label><?php esc_attr_e( 'Yes', FLICHE_VGALLERY ); ?></label>
                                                <input type="radio" name="ispublish" id="published" 
                                                    <?php if ( isset( $playlistEdit->is_publish ) && $playlistEdit->is_publish == 0 ) { 
                                                      echo 'checked="checked"';
                                                    } ?> value="0" />
                                                <label> <?php esc_attr_e( 'No', FLICHE_VGALLERY ); ?></label>
                                          </td>
                                      </tr>
                                  </table>
                                  
                            <?php /** Check whether the playlist id already exists.
                                   * If exists then display button to update playlist details 
                                   * Else display button to add playlist details */
                                  if ( $playListId ) { ?>
                                        <input type="submit" name="playlistadd" onclick="return validateplyalistInput();" class="button-primary"  value="<?php esc_attr_e( 'Update', FLICHE_VGALLERY ); ?>" class="button" />
                                        <input type="button" onclick="window.location.href = 'admin.php?page=playlist'" class="button-secondary" name="cancel" value="<?php esc_attr_e( 'Cancel' ); ?>" class="button" />
                                  <?php } else { ?>
                                        <input type="submit" name="playlistadd" onclick="return validateplyalistInput();" class="button-primary"  value="<?php esc_attr_e( 'Save', FLICHE_VGALLERY ); ?>" class="button" /> 
                                  <?php } ?>
                              </form>
                          </div>
                      </div>
                  </div>
                  <p>
              </div>
             <?php /** end Playlist add page form */ ?>
          </div>
      </div>
<?php /** end Playlist add page */ ?>
      


<?php /** Playlist records start "floatleft category_addpages" */ ?>
      <div class="">

      <?php /** Code to display playlist status */
        if ( $displayMsg && $displayMsg[1] == 'category' ) { 
            echo displayStatusMeassage ( $displayMsg [0] );  
        }
        /** Get playlist order direction */
        $orderField         = filter_input( INPUT_GET, 'order' );
        $direction          = isset( $orderField ) ? $orderField : 'DESC';
        $reverse_direction  = ( $direction == 'DESC' ? 'ASC' : 'DESC' );
        /** Playlist search message display starts */
        if ( isset( $_REQUEST['playlistsearchbtn'] ) ) { ?>
          <div  class="updated below-h2">
              <?php $url = get_site_url() . '/wp-admin/admin.php?page=playlist';
              $searchmsg = filter_input( INPUT_POST, 'PlaylistssearchQuery' );
              /** Check more than one seasrch result is exists */
              if ( count( $gridPlaylist ) ) {
                  echo esc_attr_e( 'Search Results for', FLICHE_VGALLERY ) . ' "' . $searchMsg . '"';
              } else {
                  echo esc_attr_e( 'No Search Results for', FLICHE_VGALLERY ) . ' "' . $searchMsg . '"';
              } ?> 
          </div> 
      <?php /** Playlist search message display starts */  
        } 
       
      /** Playlist search form starts */
      ?>
      <form name="Playlists" class="admin_video_search alignright" id="searchbox-playlist" action="" method="post" onsubmit="return Playlistsearch();">
          <p class="search-box"> 
              <?php /** Display text box to get search text */ ?>
              <input type="text"  name="PlaylistssearchQuery" id="PlaylistssearchQuery" 
                value="<?php if ( isset( $searchmsg ) ) { 
                  echo $searchmsg ; 
                }?>"> 
              <?php /** Display search button */ ?>
              <input type="hidden" name="page" value="Playlists"> 
              <input type="submit" name="playlistsearchbtn" id="playlistsearchButton" class="button" value="<?php esc_attr_e( 'Search Categories', FLICHE_VGALLERY ); ?>">
          </p> 
      </form>
      <?php /** Playlist search form ends  */?>
       
      <?php /** Playlist grid form Starts */ ?>
      <form  name="Playlistsfrm" action="" method="post" onsubmit="return PlaylistdeleteIds()">
          <div class="alignleft actions bulk-actions"> 
            <?php /** Call function to display filter option in top  */
              echo adminFilterDisplay ( 'playlist', 'up' ); 
            ?>
          </div>
        <?php /** Get page number, total count and set default limit per page as 2000 -||- */ 
           $limit   = 2000; 
           $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1; 
           $total   = $playlist_count; 
           
           /** Get pagination from helper 
            * Display top pagintion drop down */
           echo paginateLinks ( $total, $limit, $pagenum, 'admin', '' ); 
           
           /** Set URL to sort playlist based on the titles  */
           /** Set playid URL to sort playlist based on the ID  */
           $playIDURL         = get_site_url() . '/wp-admin/admin.php?page=playlist&orderby=id&order=' . $reverse_direction;
           /** Set playlistname URL to sort playlist based on the playlistname  */
           $playlistNameURL   = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=title&order=' . $reverse_direction;
           /** Set playlistname URL to sort playlist based on the playlistname  */
           $playlistParentURL = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=parent&order=' . $reverse_direction;
           /** Set playlistname URL to sort playlist based on the playlistname  */
           $playlistDescURL   = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=desc&order=' . $reverse_direction;
           /** Set playlistname URL to sort playlist based on the playlistname  */
           $playlistImageURL  = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=image&order=' . $reverse_direction;
           /** Set playlistname URL to sort playlist based on the playlistname  */
           $playlistThumbURL  = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=thumb&order=' . $reverse_direction;
           /** Set playliststatus URL to sort playlist based on the status  */
           $playlistStatusURL = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=publish&order='. $reverse_direction; 
           /** Set playlist order URL to sort playlist based on the ordering */
           $playlistOrderURL  = get_site_url() .'/wp-admin/admin.php?page=playlist&orderby=sorder&order=' . $reverse_direction;
           ?>
           <div style="float:right ; font-weight: bold;" >
           <?php if ( isset( $pagelist ) ) { 
             echo $pagelist; 
           } ?>
           </div>
           <div style="clear: both;"></div>
           <table class="wp-list-table widefat fixed tags hai" cellspacing="0">
                <?php /** Top heading Starts  */ ?> 
                <thead> 
                    <tr>
                        <?php /** Display select all/none/this checkbox column */ ?>
                        <th class="manage-column column-cb check-column" scope="col" style="width: 5%;"> 
                            <input type="checkbox" name="" id="manage-column-category-1" > 
                        </th>
                        <?php /** Display playlist id column  */ ?>
                        <th lass="manage-column column-id sortable desc" scope="col" style="width: 5%;"> 
                            <a href="<?php echo $playIDURL; ?>"> 
                                <span><?php esc_attr_e( 'ID', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a>
                        </th>
                        <?php /** Display playlist parent id column  */ ?>
                        <th class="manage-column column-parent sortable desc" scope="col" style="width: 15%;"> 
                            <a href="<?php echo $playlistParentURL; ?>">
                                <span><?php esc_attr_e( 'Parent', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <?php /** Display playlist name column  */ ?>
                        <th class="manage-column column-name sortable desc" scope="col" style="width: 15%;"> 
                            <a href="<?php echo $playlistNameURL; ?>">
                                <span><?php esc_attr_e( 'Title', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <?php /** Display playlist desc column  */ ?>
                        <th class="manage-column column-desc sortable desc" scope="col" style="width: 15%;"> 
                            <a href="<?php echo $playlistDescURL; ?>">
                                <span><?php esc_attr_e( 'Description', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <?php /** Display playlist image column  */ ?>
                        <th class="manage-column column-image sortable desc" scope="col" style="width: 15%;"> 
                            <a href="<?php echo $playlistImageURL; ?>">
                                <span><?php esc_attr_e( 'Image', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <?php /** Display playlist thumb column  */ ?>
                        <th class="manage-column column-thumb sortable desc" scope="col" style="width: 15%;"> 
                            <a href="<?php echo $playlistThumbURL; ?>">
                                <span><?php esc_attr_e( 'Thumb', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <?php /** Display playlist published/unpublished status column  */ ?>
                        <th class="manage-column column-Expiry sortable desc" scope="col" style="width: 5%;"> 
                            <a href="<?php echo $playlistStatusURL; ?>">
                                <span class="fliche_category_publish"> <?php esc_attr_e( 'Publish', FLICHE_VGALLERY ); ?> </span> 
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th class="manage-column column-sortorder sortable desc" scope="col" style="width: 10%;"> 
                            <a href="<?php echo $playlistOrderURL; ?>">
                                <span> <?php esc_attr_e( 'Order', FLICHE_VGALLERY ); ?> </span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php /** Display playlist sorting column  */ ?>
                        <?php /*
                        <th class="manage-column column-si check-column" scope="col" style=""> 
                            <span> <?php esc_attr_e( '', FLICHE_VGALLERY ); ?></span> 
                            <span class="sorting-indicator"></span> 
                        </th> */ ?>
                    </tr> 
                </thead> 
               <?php /** Top heading Ends */ ?> 
                
                <?php /** Display playlist data section Starts */ ?> 
                <tbody id="test-list" class="list:post"> 
                    <input type="hidden" id="playlistid2" name="playlistid2" value="1" /> 
                    <div name="txtHint"></div> 
                <?php /** Looping to diplay playlist data */
                  foreach ( $gridPlaylist as $playlistView ) { 
                    $class = ( $class == 'class="alternate"' ) ? '' : 'class="alternate"';
                ?> 
                    <tr id="listItem_<?php echo $playlistView->pid ; ?>" 
                        <?php echo $class; ?> > 
                        <th scope="row" class="check-column"> <input type="checkbox" name="pid[]" value="<?php echo $playlistView->pid ; ?>"> </th>
                        
                        <?php /** Display playlist id column */ ?>
                        <td class="id-column column-id"> <a title="Edit <?php echo $playlistView->playlist_name ; ?>" 
                            href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo $playlistView->pid ; ?>" >
                            <?php echo $playlistView->pid ; ?> </a> 
                            <div class="row-actions"> 
                        </td>
                        
                        <?php /** Display playlist parent id column */ ?>
                        <td class="title-column"> 
                          <?php if( $playlistView->parent_id != 0 ){ ?>
                            <a title="Edit <?php echo $playlistView->playlist_name ; ?> Parent" class="row-title" 
                                href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo $playlistView->parent_id ; ?>" >
                                <?php echo $playlistView->parent_id ; ?>
                            </a>
                          <?php } else { ?>
                            --
                          <?php } ?>
                        </td>
                        
                        <?php /** Display playlist name column */ ?>
                        <td class="title-column"> <a title="Edit <?php echo $playlistView->playlist_name ; ?>" class="row-title" 
                            href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo $playlistView->pid ; ?>" >
                            <?php echo $playlistView->playlist_name ; ?></a> 
                        </td>
                        
                        <?php /** Display playlist desc column */ ?>
                        <td class="title-column"> <a title="Edit <?php echo $playlistView->playlist_name ; ?> Description" class="row-title" 
                            href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo $playlistView->pid ; ?>" >
                            <?php echo truncate( strip_tags( $playlistView->playlist_desc, '<br>' ), 30 ); ?></a>
                        </td>
                        
                        <?php /** Display playlist image column */ ?>
                        <td class="title-column"> <a title="Edit <?php echo $playlistView->playlist_name ; ?> Image" class="row-title" 
                            href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo $playlistView->pid ; ?>" >
                            <?php echo $playlistView->playlist_image ; ?></a> 
                        </td>
                        
                        <?php /** Display playlist thumb column */ ?>
                        <td class="title-column"> <a title="Edit <?php echo $playlistView->playlist_name ; ?> Thumb" class="row-title" 
                            href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo $playlistView->pid ; ?>" >
                            <?php echo $playlistView->playlist_thumb ; ?></a> 
                        </td>
                            
                        <td class="pub-column Expiry column-Expiry"  align="center"> 
                        <?php /** Set image based 
                               * on publish / unpublish status 
                               * to display in grid section */
                            $status  = 1; 
                            $image   = 'deactivate.jpg'; 
                            $publish = __( 'Publish', FLICHE_VGALLERY ); 
                            if ( $playlistView->is_publish == 1 ) { 
                                $status  = 0; 
                                $image   = 'activate.jpg'; 
                                $publish = __( 'Unpublish', FLICHE_VGALLERY ); 
                            }
                            /** Get status url when publish / unpublish action is done */
                            $statusURL = get_site_url() .'/wp-admin/admin.php?page=playlist&pagenum=' .$pagenum .'&playlistId='.$playlistView->pid .'&status='. $status; 
                            ?>
                            <a href="<?php echo $statusURL; ?>">   
                                <img src="<?php echo getImagesDirURL() . $image ?>" title="<?php echo $publish ; ?>"   /> 
                            </a>
                        </td>

                        <?php /** Display playlist ordering column */ ?>
                        <td class="order-column Expiry column-ordering column-Expiry">
                        <?php /*
                          <?php echo $playlistView->playlist_order ; ?>
                        </td> */ ?>
                        <?php /** Display drag and drop option */ ?>
                        <?php /*
                        <td class="check-column"> */ ?>
                        
                            <span class="hasTip content" title="<?php esc_attr_e( 'Click and Drag', FLICHE_VGALLERY ); ?>" style="padding: 5px;">
                                <img src="<?php echo getImagesDirURL() .'arrow.png'; ?>" alt="move" width="16" height="16" class="handle" /> 
                            </span>
                          
                          <?php echo $playlistView->playlist_order ; ?>
                        </td>

                    </tr>
                <?php 
                  } /** Foreach to display playlist data ends */

                     
                  /** If playlist not found, then display not found message */
                  if ( isset( $_REQUEST['searchplaylistsbtn'] ) ) { 
                ?> 
                    <tr class="no-items"> <td class="colspanchange" colspan="9"> No Category found. </td> </tr> 
                <?php }
                    /** Check count of playlist is empty, to display not found message */
                  if ( count( $gridPlaylist ) == 0 ) { 
                ?> 
                    <tr class="no-items"><td class="colspanchange" colspan="9"> No Category found. </td> </tr> 
                <?php } 
                    /** Display playlist data section Ends */
                ?> 
                </tbody>
                <?php /* Footer heading Starts */ ?>
                <tfoot> 
                    <tr> 
                        <th class="manage-column column-cb check-column" scope="col" style="">
                            <input type="checkbox" name="" id="manage-column-category-1" >
                        </th>
                        <th class="manage-column column-id sortable desc" scope="col" style=""> 
                            <a href="<?php echo $playIDURL; ?>"> 
                                <span><?php esc_attr_e( 'ID', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <th class="manage-column column-parent sortable desc" scope="col" style=""> 
                            <a href="<?php $playlistParentURL ; ?>"> 
                                <span><?php esc_attr_e( 'Parent', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <th class="manage-column column-name sortable desc" scope="col" style=""> 
                            <a href="<?php $playlistNameURL ; ?>"> 
                                <span><?php esc_attr_e( 'Title', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <th class="manage-column column-desc sortable desc" scope="col" style=""> 
                            <a href="<?php $playlistDescURL ; ?>"> 
                                <span><?php esc_attr_e( 'Desc', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <th class="manage-column column-image sortable desc" scope="col" style=""> 
                            <a href="<?php $playlistImageURL ; ?>"> 
                                <span><?php esc_attr_e( 'Image', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <th class="manage-column column-thumb sortable desc" scope="col" style=""> 
                            <a href="<?php $playlistThumbURL ; ?>"> 
                                <span><?php esc_attr_e( 'Thumb', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a> 
                        </th>
                        <th class="manage-column column-Expiry sortable desc" scope="col" style=""> 
                            <a href="<?php echo $playlistStatusURL; ?>">
                                <span><?php esc_attr_e( 'Publish', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a>
                        </th>
                        <th class="manage-column column-sortorder sortable desc" scope="col" style=""> 
                            <a href="<?php echo $playlistOrderURL ; ?>">
                                <span><?php esc_attr_e( 'Order', FLICHE_VGALLERY ); ?></span> 
                                <span class="sorting-indicator"></span> 
                            </a>
                        </th>
                        <?php /*
                        <th class="" scope="col" style=""> 
                            <span><?php esc_attr_e( '', FLICHE_VGALLERY ); ?> </span> 
                            <span class="sorting-indicator"></span> 
                        </th> */ ?>
                    </tr> 
                </tfoot> 
                <?php /** Footer heading Ends */ ?>
             </table>
                   
             <div style="clear: both;"></div>
             <!-- Footer filter options Starts -->
             <div class="alignleft actions" style="margin-top:10px;">
                 <?php /** Call function to display filter option in bottom   */
                       echo adminFilterDisplay ( 'playlist', 'down' ); ?>
             </div>
       <?php /** Footer pagination drop down  */
             echo paginateLinks ( $total, $limit, $pagenum, 'admin', '' );
             /** Footer filter options Ends */ ?>
          </form> 
          <?php /** Playlist grid form Ends */ ?>
        </div> 
    </div>
    <?php /**  Playlist add/ grid section Starts */ ?> 
</div>
<?php  /** Playlist admin page Ends */ ?>