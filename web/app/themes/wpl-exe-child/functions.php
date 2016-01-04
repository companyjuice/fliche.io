<?php

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles' );
function enqueue_child_styles() 
{
  wp_enqueue_style( 'child-style',
    get_stylesheet_directory_uri() . '/style.css',
    array( 'wproto-front-skin' )
  );
}


/* -||- CUSTOM CODE -- FLICHE TEST -||- 

$fliche_plugin_dir_url = plugin_dir_url() . 'fliche-video-gallery/';
echo '<pre>';
echo 'plugin_dir_url: ';
echo $fliche_plugin_dir_url;
echo '</pre>';
*/

/* CUSTOM CODE -- Custom MediaElement CSS */
#add_action( 'wp_enqueue_scripts', 'fliche_deregister_styles' );
// deregister wp core mediaelement styles
function fliche_deregister_styles()
{
  wp_deregister_style( 'mediaelement' );
  wp_deregister_style( 'wp-mediaelement' );
}
/* register custom mediaelement styles */
#add_action( 'wp_enqueue_scripts', 'fliche_enqueue_styles' );
function fliche_enqueue_styles() 
{
  wp_enqueue_style( 
    'mediaelement', 
    //trailingslashit( WP_PLUGIN_URL ) . 'css/mediaelement/mediaelement.min.css',
    $fliche_plugin_dir_url . 'mejs/build/mediaelementplayer.css',
    null,
    '20160101'
  );
}
/* END CUSTOM CODE -- Custom MediaElement CSS */


/* CUSTOM CODE -- TEST -- YouTube iframe->to->video tag swapper */
/*
add_filter( 'embed_oembed_html', 'oembed_so_fliche', 10, 4 );
function oembed_so_fliche( $html, $url, $attr, $post_ID )
{
  $provider = parse_url( $url ); 

  if( !in_array( $provider['host'], array('youtu.be','youtube.com') ) ) {
    return $html;
  }

  if(
    strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') 
    || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') 
    || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')
  ) {
    return $html;
  }

  $html = str_replace( '<iframe width="584" height="438"', '<video controls="controls" id="youtube1"><source type="video/youtube" width="640" height="360" ', $html );
  $html = str_replace( '?feature=oembed" frameborder="0" allowfullscreen></iframe>', '"/></video>', $html );
  $html .= '<script>jQuery(document).ready(function($) { var player = new MediaElementPlayer("#youtube1"); });</script>';
  return $html;
}

add_action( 'wp_enqueue_scripts', function(){
  wp_enqueue_style( 'wp-mediaelement' );
  wp_enqueue_script( 'wp-mediaelement', false, array('jquery'), false, true );
});
*/
/* END CUSTOM CODE -- TEST -- YouTube iframe->to->video tag swapper */