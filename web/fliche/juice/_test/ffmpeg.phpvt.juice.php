<?php
/**
 * Fliche PHP Video Toolkit v2 -- Library Testing
 */
define('__ROOT__', DIRNAME(DIRNAME(DIRNAME(__FILE__))));
#define('__FFMPEG__', __ROOT__.'/fliche/php-ffmpeg/src');

#require_once(__FFMPEG__.'/FFMpeg/FFMpeg.php');
#require_once(__FFMPEG__.'/FFMpeg/FFProbe.php');
#require_once(__FFMPEG__.'/Doctrine/Common/Cache/ArrayCache.php');

// hello composer !!
require __ROOT__ . '/fliche/vendor/autoload.php';

$ffmpeg_exe     = '/home/juice/bin/ffmpeg';
$ffprobe_exe    = '/home/juice/bin/ffprobe';
$ffmpeg_log     = '/home/juice/ffmpeg.log';
$src_file 	    = __ROOT__.'/assets/videogallery/video.mp4';
$dest_file 	    = __ROOT__.'/assets/videogallery/video-frame.ff.jpg';

$ffmpeg_out	    = '';
$last_line	    = '';
$time_start     = '';
$time_end	    = '';

/***/

echo '<pre>';
#Reflection::export(new ReflectionObject($ffprobe));
#var_dump($duration);
var_dump($dest_file);
echo '</pre>';

/***/