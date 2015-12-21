<?php

/**
 * test ffmpeg installation 
 */

define('__ROOT__', DIRNAME(DIRNAME(DIRNAME(__FILE__))));

$ffmpeg_exe = '/home/juice/bin/ffmpeg';
$ffmpeg_log = '/home/juice/ffmpeg.log';
$src_file 	= __ROOT__.'/assets/videogallery/47_video1043870064.mp4';
$dest_file 	= __ROOT__.'/assets/videogallery/47_thumb1043870064.ff.jpg';

$ffmpeg_out	= '';
$last_line	= '';
$time_start = '';
$time_end		= '';


/***/
$time_start = date('Y-m-d H:i:s');
echo "Start ffmpeg: $time_start";

ob_start();
// Ignore connection-closing by the client/user
ignore_user_abort(true);

// Set your time limit to a length long enough for your script to run, 
// but not so long it will bog down your server in case multiple versions run 
// or this script gets in an endless loop.
if ( 
     !ini_get('safe_mode') 
     && strpos(ini_get('disable_functions'), 'set_time_limit') === FALSE 
){
    set_time_limit(600);
}


/** 
 * transcode a file
 */
echo '<pre>';

//works, but must output to log file location with www-data user permission
#echo shell_exec("$ffmpeg_exe -y -i $src_file $dest_file </dev/null >/dev/null 2>$ffmpeg_log &"); 
//works
//echo shell_exec("$ffmpeg_exe -y -i $src_file $dest_file 2>&1 &"); 
//works, but doesn't output whole process to variable/file, only outputs last line -- may be good to have?
#exec("$ffmpeg_exe -y -i $src_file $dest_file 2>&1 &", $ffmpeg_out);
#var_dump($ffmpeg_out);
//?? -loglevel panic OR -nostats
#$last_line = system("$ffmpeg_exe -nostats -y -i $src_file $dest_file 2>&1 &", $ffmpeg_out);
#$last_line = system("$ffmpeg_exe -ss 00:00:02 -y -i $src_file -vf scale=800:-1 -vframes 1 $dest_file 2>&1 &", $ffmpeg_out);
echo shell_exec("$ffmpeg_exe -ss 00:00:02 -y -i $src_file -vf scale=800:-1 -vframes 1 $dest_file 2>&1 &");
#var_dump($last_line);
#var_dump($ffmpeg_out);

// Get your output and send it to the client
$content = ob_get_contents();         // Get the content of the output buffer
ob_end_clean();                       // Close current output buffer
#$len = strlen($content);             // Get the length
#header('Connection: close');         // Tell the client to close connection
#header("Content-Length: $len");      // Close connection after $len characters
#var_dump($content);                   // Output content
#flush();                             // Force php-output-cache to flush to browser.

// Optional: kill all other output buffering
#while (ob_get_level() > 0) {
#    ob_end_clean();
#}

echo '</pre>';
echo '<hr>';
echo 'Last line of the output: ' . $last_line;
echo '<br>';
echo 'Return value: ' . $ffmpeg_out;
echo '<hr>';


/** 
 * get duration of file
 */
$ffmpeg_duration = getVideoDuration( $ffmpeg_exe, $src_file );
#var_dump($ffmpeg_duration); echo '<br>';
$duration = $ffmpeg_duration[0];
#var_dump($duration); echo '<br>';
$matches  = $ffmpeg_duration[1];
#var_dump($matches); echo '<br>';

if (! empty ( $duration )) {
  /* Convert duration into hours:minutes format */
  $duration_array = explode( ':', $matches [1] [0] );
  $sec            = ceil( $duration_array [0] * 3600 + $duration_array [1] * 60 + $duration_array [2] );
  $duration       = convertTime( $sec );
}
echo '<hr>';
echo 'Video Duration: ' . $duration; echo '<br>';
#echo 'Duration Matches: ' . $matches; echo '<br>';


/***/
$time_end = date('Y-m-d H:i:s');
echo "<hr>End ffmpeg: $time_end";


/**
 * EXAMPLES
 */
/**
 * Function to get video duration using ffmpeg option
 * 
 * @param unknown $ffmpeg_path
 * @param unknown $videoFile
 * @return multitype:unknown number
 */
function getVideoDuration( $ffmpeg_path, $videoFile ) {
  /** Get duration from video using ffmpeg option */
  ob_start();
  /** Execute code to get duration using ffmpeg */
  passthru( $ffmpeg_path . ' -i "' . $videoFile . '" 2>&1' );
  /** Get contents */
  $get_duration = ob_get_contents();
  ob_end_clean();
  /** Preg match video duration and get results */
  $search       = '/Duration: (.*?),/';
  $duration     = preg_match ( $search, $get_duration, $matches, PREG_OFFSET_CAPTURE, 3 );
  /** Return video duration and matches */
  return array( $duration, $matches );
}
/*
 * Function to convert video duration into h:m:s format
 * 
 * @param unknown $sec
 * @return string
 */
function convertTime( $sec ) {
  $hms    = $padHours = '';
  /* Calculate hours / minutes / seconds and return the values in h:m:s format */
  $hours  = intval ( intval ( $sec ) / 3600 );
  $hms    .= ($padHours) ? str_pad ( $hours, 2, '0', STR_PAD_LEFT ) . ':' : $hours . ':';
  if ($hms == '0:') {
    $hms  = '';
  }
  /* Calculate minutes */
  $minutes = intval ( ($sec / 60) % 60 );
  /* Condition if less than 9 mins */
  if( $minutes < 9 && $hms ) {
    $minutes = '0:' . $minutes;
  }
  $hms    .= str_pad ( $minutes, 1, '0', STR_PAD_LEFT ) . ':';
  /* Calculate seconds */
  $seconds = intval ( $sec % 60 );
  /* Return seconds */
  return $hms . str_pad ( $seconds, 2, '0', STR_PAD_LEFT );
}