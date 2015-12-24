<?php
/**
 * Function to delete plugin , 
 * delate created table for install the plugin.
 */
function videogallerypluginuninstalling() {
    global $wpdb;
    /** Delete content from post table */
    /** Delete video home page content */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[videohome]"' );
    /** Delete videomore page content  */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[videomore]"' );
    /** Delete videogallery page content */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[videogallery]"' );
    /** Delete main player page  */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[HDFLV_mainplayer]"' );
    /** Delete old main page content */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[flicheHome]"' );
    /** Delete old more page content */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[flicheMore]"' );
    /** Delete old video page content */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[flicheVideo]"' );
    /** Delete videogallery video posts */
    $wpdb->query ( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_type = "videogallery"' );
    /** Delete plugin tables */
    /** Delete video table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare' );
    /** Delete video ads table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_vgads' );
    /** Delete video tags table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_tags' );
    /** Delete plugin settings table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_settings' );
    /** Delete playlist table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_playlist' );
    /** Delete media table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_med2play' );
    /** Delete google adsense table */
    $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_vgoogleadsense' );
    /**
     * Check if #__hdflvvideoshare_language is exists,
     * If exist then delete table
     */
    $table_language   = $wpdb->prefix . 'hdflvvideoshare_language';  
    foreach ( $wpdb->get_results ( 'SHOW TABLES;', ARRAY_N ) as $row ) {
      if ($row [0] == $table_language) {
        $lfound   = true;
      }
    } 
    if ($lfound) {
        /** Delete language table for old version plugin */
        $wpdb->query ( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_language' );
    }    
}
?>