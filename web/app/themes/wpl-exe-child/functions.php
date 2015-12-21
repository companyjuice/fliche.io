<?php

function enqueue_child_styles() {

  wp_enqueue_style( 'child-style',
    get_stylesheet_directory_uri() . '/style.css',
    array( 'wproto-front-skin' )
  );

}
add_action( 'wp_enqueue_scripts', 'enqueue_child_styles' );