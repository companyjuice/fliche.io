<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>test</title>
    <!-- mediaelement -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://mediaelementjs.com/js/mejs-2.9.2/mediaelement-and-player.min.js"></script>
    <link rel="stylesheet" href="http://mediaelementjs.com/js/mejs-2.9.2/mediaelementplayer.min.css" />
    </head>
    <body>
        <video width="640" height="360" id="player1" preload="none">
            <source type="video/youtube" src="http://www.youtube.com/watch?v=Lt6CUH9bVzI" />
        </video>
    </body>
</html>

<script type="text/javascript">
jQuery(document).ready(function($) {

    // declare object for video
    var player = new MediaElementPlayer('#player1');

});
</script>


<?php

/**
 * untrailingslashit
 * + trailingslashit
 */
if ( !function_exists( 'trailingslashit' ) ) :
/**
 * Appends a trailing slash.
 *
 * Will remove trailing slash if it exists already before adding a trailing
 * slash. This prevents double slashing a string or path.
 *
 * The primary use of this is for paths and thus should be used for paths. It is
 * not restricted to paths and offers no specific path support.
 *
 * @since 1.2.0
 * @uses untrailingslashit() Unslashes string if it was slashed already.
 *
 * @param string $string What to add the trailing slash to.
 * @return string String with trailing slash added.
 */
function trailingslashit($string) {
   return untrailingslashit($string) . '/';
}
endif;

if ( !function_exists( 'untrailingslashit' ) ) :
/**
 * Removes trailing slash if it exists.
 *
 * The primary use of this is for paths and thus should be used for paths. It is
 * not restricted to paths and offers no specific path support.
 *
 * @since 2.2.0
 *
 * @param string $string What to remove the trailing slash from.
 * @return string String without the trailing slash.
 */
function untrailingslashit($string) {
   return rtrim($string, '/');
}
endif;


// get YouTube Video ID from YT URL string
// using regular expressions.
function getYoutubeVideoID ( $youtube_url ) {
  /**
   * Match non-linked youtube URL in the wild.
   * : Regex Overview/Pseudo-code :
   * 
   * Required scheme. Either http or https.
   * Optional subdomain.
   * Group host alternatives.
   * Either youtu.be
   * or youtube.com 
   * or youtube-nocookie.com
   * followed by
   * Allow anything up to VIDEO_ID,
   * but char before ID is non-ID char.
   * End host alternatives.
   * $1: VIDEO_ID is exactly 11 chars.
   * Assert next char is non-ID or EOS.
   * Assert URL is not pre-linked.
   * Allow URL (query) remainder.
   * Group pre-linked alternatives.
   * Either inside a start tag,
   * or inside <a> element text contents.
   * End recognized pre-linked alts.
   * End negative lookahead assertion.
   * Consume any URL (query) remainder.
   */
  return preg_replace('~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/| youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>| </a>))[?=&+%\w.-]*~ix', '$1', $youtube_url);
}

// example urls
$this_video_url = 'https://youtu.be/RVWtSsMUBD0';
$this_video_url = 'https://www.youtube.com/watch?v=RVWtSsMUBD0';

$this_youtube_id = getYoutubeVideoID( $this_video_url );
echo 'YouTube ID: ' . $this_youtube_id;