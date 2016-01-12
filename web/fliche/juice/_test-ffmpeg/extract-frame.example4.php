<?php

namespace FlicheToolkit;

include_once './includes/bootstrap.php';

try
{
  $video = new Video($example_video_path);
  $process = $video->extractSegment(new Timecode('00:00:05.00'), new Timecode('00:00:06.00'))
                   ->extractFrames(null, null, '1/2')
                   ->save('./output/extract-frame.example4.%timecode.jpg', null, Media::OVERWRITE_EXISTING);

  echo '<h1>Executed Command</h1>';
  Trace::vars($process->getExecutedCommand());
  echo '<hr /><h1>FFmpeg Process Messages</h1>';
  Trace::vars($process->getMessages());
  echo '<hr /><h1>Buffer Output</h1>';
  Trace::vars($process->getBuffer(true));
  echo '<hr /><h1>Resulting Output</h1>';
  $output = $process->getOutput();
  $output = array_values($output);
  $paths = array();
  foreach ($output as $obj)
  {
    array_push($paths, $obj->getMediaPath());
  }
  Trace::vars($paths);

}
catch(FfmpegProcessOutputException $e)
{
  echo '<h1>Error</h1>';
  Trace::vars($e);

  $process = $video->getProcess();
  if($process->isCompleted())
  {
    echo '<hr /><h2>Executed Command</h2>';
    Trace::vars($process->getExecutedCommand());
    echo '<hr /><h2>FFmpeg Process Messages</h2>';
    Trace::vars($process->getMessages());
    echo '<hr /><h2>Buffer Output</h2>';
    Trace::vars($process->getBuffer(true));
  }
}
catch(Exception $e)
{
  echo '<h1>Error</h1>';
  Trace::vars($e->getMessage());
  echo '<h2>Exception</h2>';
  Trace::vars($e);
}

