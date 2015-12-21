<?php

  try
  {
    $config = new \Fliche\Config(array(
      'temp_directory'              => './tmp',
      'ffmpeg'                      => '/home/juice/bin/ffmpeg', //'/opt/local/bin/ffmpeg',
      'ffprobe'                     => '/home/juice/bin/ffprobe', //'/opt/local/bin/ffprobe',
      //'yamdi'                       => '/home/juice/bin/yamdi', //'/opt/local/bin/yamdi',
      //'qtfaststart'                 => '/home/juice/bin/qt-faststart', //'/opt/local/bin/qt-faststart',
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
  catch(\Fliche\Exception $e)
  {
    echo '<h1>Config set errors</h1>';
    \Fliche\Trace::vars($e);
    exit;
  }

  $example_video_path = BASE.'juice/media/47_video2092056811.mp4';
  $example_audio_path = BASE.'juice/media/Ballad_of_the_Sneak.mp3';

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
  
