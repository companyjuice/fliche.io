<?php

try
{
  $config = new \FlicheToolkit\Config(array(
    'temp_directory'              => './tmp',
    'ffmpeg'                      => 'C:\\webserver\\ffmpeg\\bin\\ffmpeg.exe', 
                                      // '/home/juice/bin/ffmpeg', 
                                      // '/opt/local/bin/ffmpeg',
    'ffprobe'                     => 'C:\\webserver\\ffmpeg\\bin\\ffprobe.exe', 
                                      // '/home/juice/bin/ffprobe', 
                                      // '/opt/local/bin/ffprobe',
    //'yamdi'                       => '/home/juice/bin/yamdi', 
                                      //'/opt/local/bin/yamdi',
    //'qtfaststart'                 => '/home/juice/bin/qt-faststart', 
                                      //'/opt/local/bin/qt-faststart',
    'gif_transcoder'              => 'php',
    'gif_transcoder_convert_use_dither'    => false,
    'gif_transcoder_convert_use_coalesce'  => false,
    'gif_transcoder_convert_use_map'       => false,
    //'convert'                     => '/home/juice/bin/convert', //'/opt/local/bin/convert',
    //'gifsicle'                    => '/home/juice/bin/gifsicle', //'/opt/local/bin/gifsicle',
    'php_exec_infinite_timelimit' => true,
    'cache_driver'                => 'InTempDirectory',
    'set_default_output_format'   => true,
  ), true);
}
catch(\FlicheToolkit\Exception $e)
{
  echo '<h1>Config set errors</h1>';
  \FlicheToolkit\Trace::vars($e);
  exit;
}


#$example_video_path = BASE.'juice/media/47_video2092056811.mp4';
#$example_video_path = 'G:\bkp\media\fishflicks\FA_S01_E11.mp4';
#$example_video_path = 'G:\bkp\media\fishflicks\ktp_test.mp4';
$example_video_path = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\video.mp4';
#$example_audio_path = BASE.'juice/media/Ballad_of_the_Sneak.mp3';
$example_audio_path = 'G:\bkp\media\fishflicks\FA_S01_E11.mp3';

$example_images_dir = BASE.'juice/media/images/';
$example_image_paths = array(
  $example_images_dir.'P1110741.jpg',
  $example_images_dir.'P1110742.jpg',
  $example_images_dir.'P1110743.jpg',
  $example_images_dir.'P1110744.jpg',
  $example_images_dir.'P1110745.jpg',
  $example_images_dir.'P1110746.jpg',
  $example_images_dir.'P1110753.jpg',
  $example_images_dir.'P1110754.jpg',
  $example_images_dir.'P1110755.jpg',
  $example_images_dir.'P1110756.jpg',
);


/* -||- */

/**
 * Function to get video duration using ffmpeg option
 */
function getVideoDuration ( $ffmpeg_path, $videoFile ) 
{
  /** Get duration from video using ffmpeg option */
  ob_start ();

  /** Execute code to get duration using ffmpeg */
  passthru ( $ffmpeg_path . ' -i "' . $videoFile . '" 2>&1' );

  /** Get contents */
  $get_duration   = ob_get_contents ();

  /** End ffmpeg contact */
  ob_end_clean ();

  /** Preg match video duration and get results */
  $search         = '/Duration: (.*?),/';
  $duration       = preg_match ( $search, $get_duration, $matches, PREG_OFFSET_CAPTURE, 3 );

  /** Return video duration and matches */
  return array( $duration, $matches );
}