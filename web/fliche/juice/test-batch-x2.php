<?php
/*
echo '<pre>';
echo '# get list of videos in $v_src_dir'; echo "\n";
echo '# foreach( $v_src_files as $v_file ){ '; echo "\n";
echo '#   : 0) $v_file: "";'; echo "\n";
echo '#   : 1) get file metadata'; echo "\n";
echo '#   : 2) run ffmpeg functions'; echo "\n";
echo '#   : 3) upload new files to cloud'; echo "\n";
echo '#   : 4) save records to database'; echo "\n";
echo '# } ';
echo '</pre>';
*/

namespace FlicheToolkit;

include_once './includes/bootstrap.php';


// run this file
_construct();

function _construct() {
  if( 1 == 1 ) {
    run_batch();
  }
}


function run_batch() {
    
    echo '<a href="?method=blocking">Blocking</a> | <a href="?method=non-blocking">Non blocking</a><br />';

  $v_src_dir = 'G:\bkp\media\fishflicks';
  #$v_src_dir = '/var/www/html/assets/videogallery';
  $v_out_dir = 'G:\bkp\media\fishflicks\batch';
  #$v_out_dir = '/var/www/html/assets/videogallery/batch';
  $v_src_files = array();
  $v_out_files = array();
  $v_file = '';

  echo '<pre>';

  $v_src_files = scandir($v_src_dir);
  #var_dump($v_src_files);

  #get_videos(); // should return an array as $v_src_files


  foreach( $v_src_files as $v_file ) {
    #echo '$v_file: ' . $v_file . '';
    #echo "\n";
    
    #if ($v_file != "." && $v_file != "..") {
    if ( $v_file == "75_video104132678.mp4"
      || $v_file == "72_video1745878797.mp4"
      || $v_file == "72_video1641408712.mp4"
      || $v_file == "72_video876471870.mp4"
    ) {
      
      if ( stripos($v_file, '.mp4') !== FALSE
        || stripos($v_file, '.m4v') !== FALSE
        || stripos($v_file, '.mov') !== FALSE ){
        echo '-||- found: video file'; echo "\n";
        #insert_video( $v_file );
        process_video( $v_file, $v_src_dir, $v_out_dir );
        echo "\n";
      }/* 
      else if ( stripos($v_file, '.jpg') !== FALSE
             || stripos($v_file, '.gif') !== FALSE
             || stripos($v_file, '.png') !== FALSE ){
        echo '-||- found: image file'; echo "\n";
        echo "\n";
      }
      else {
        echo '-||- file type not found'; echo "\n";
        echo '$v_file: ' . $v_file . ''; echo "\n";
      }*/

    }
  }
  echo "\n";

  #get_videos();

  echo '</pre>';

  return true;

}


/**
 * Function to process videos using FFMPEG
 */
function process_video( $v_file, $v_src_dir, $v_out_dir ) {

  $v_src_file_path = $v_src_dir . '\\' . $v_file;
  $v_out_file_path = $v_out_dir . '\\' . $v_file;

  #echo '$v_file: ' . $v_file . '';
  #echo "\n";
  #echo '$v_src_dir: ' . $v_src_dir . '';
  #echo "\n";
  #echo '$v_out_dir: ' . $v_out_dir . '';
  #echo "\n";
  echo '$v_src_file_path: ' . $v_src_file_path . '';
  echo "\n";
  echo '$v_out_file_path: ' . $v_out_file_path . '';
  echo "\n";
  #return true;

    try
    {
        $video = new Video($v_src_file_path);
        #$video->extractSegment(new Timecode(10), new Timecode(30));
        $process = $video->getProcess();
        #$process->setProcessTimelimit(5); // in seconds (did not work)


        // 240p, 360p, 480p, 720p, 1080p
        $multi_output = new MultiOutput();


        // 240p
        $mp4_240p_output = $v_out_file_path . '.nat.240p.mp4';
        $format = Format::getFormatFor($mp4_240p_output, null, 'VideoFormat');
            #$output_format = new VideoFormat();
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(426, 240);
            #$output_format->setVideoDimensions(160, 120);
        $multi_output->addOutput($mp4_240p_output, $format);
            #$video->save('BigBuckBunny_160x120.3gp', $output_format);
        //
        // 360p
        $mp4_360p_output = $v_out_file_path . '.nat.360p.mp4';
        $format = Format::getFormatFor($mp4_360p_output, null, 'VideoFormat');
            #$output_format = new VideoFormat();
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(640, 360);
            #$output_format->setVideoDimensions(160, 120);
        $multi_output->addOutput($mp4_360p_output, $format);
            #$video->save('BigBuckBunny_160x120.3gp', $output_format);
        //
        // 480p
        $mp4_480p_output = $v_out_file_path . '.nat.480p.mp4';
        $format = Format::getFormatFor($mp4_480p_output, null, 'VideoFormat');
            #$output_format = new VideoFormat();
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(854, 480);
            #$output_format->setVideoDimensions(160, 120);
        $multi_output->addOutput($mp4_480p_output, $format);
            #$video->save('BigBuckBunny_160x120.3gp', $output_format);
        //
        // 720p
        $mp4_720p_output = $v_out_file_path . '.nat.720p.mp4';
        $format = Format::getFormatFor($mp4_720p_output, null, 'VideoFormat');
            #$output_format = new VideoFormat();
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(1280, 720);
            #$output_format->setVideoDimensions(160, 120);
        $multi_output->addOutput($mp4_720p_output, $format);
            #$video->save('BigBuckBunny_160x120.3gp', $output_format);
        //
        /* 1080p
        $mp4_1080p_output = $v_out_file_path . '.nat.1080p.mp4';
        $format = Format::getFormatFor($mp4_1080p_output, null, 'VideoFormat');
            #$output_format = new VideoFormat();
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(1920, 1080);
            #$output_format->setVideoDimensions(160, 120);
        $multi_output->addOutput($mp4_1080p_output, $format);
            #$video->save('BigBuckBunny_160x120.3gp', $output_format);
        */

        /*
        $ogg_output = $v_out_file_path . '.nat.1.ogg';
        $format = Format::getFormatFor($ogg_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_SQCIF);
        $multi_output->addOutput($ogg_output, $format);

        $threegp_output = $v_out_file_path . '.nat.2.3gp';
        $format = Format::getFormatFor($threegp_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $multi_output->addOutput($threegp_output, $format);

        $threegp_output = $v_out_file_path . '.nat.3.3gp';
        $format = Format::getFormatFor($threegp_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $multi_output->addOutput($threegp_output, $format);
        */

        if(isset($_GET['method']) === true && $_GET['method'] === 'blocking')
        {
            echo '<h2>Blocking Method</h2>';

            // If you use a blocking save but want to handle the progress during the block, then assign a callback within
            // the constructor of the progress handler.
            // IMPORTANT NOTE: most modern browser don't support output buffering any more.
            $progress_data = array();

            $progress_handler = new ProgressHandlerNative(function($data) use (&$progress_data)
            {
                // do something here like log to file or db.
                array_push($progress_data, round($data['percentage'], 2).': '.round($data['run_time'], 2));
            });

            $process = $video->purgeMetaData()
                             ->setMetaData('title', 'Fliche Video Toolkit')
                             ->setMetaData('author', 'fliche.io')
                             ->save($multi_output, null, Video::OVERWRITE_EXISTING, $progress_handler);
            
            array_unshift($progress_data, 'Percentage Completed: Time taken');
            Trace::vars(implode(PHP_EOL, $progress_data));
        }
        else
        {
            echo '<h2>Non Blocking Method</h2>';

            // use a non block save to probe the progress handler after the save has been made.
            // IMPORTANT: this method only works with ->saveNonBlocking as otherwise the progress handler
            // probe will quit after one cycle.
            $progress_handler = new ProgressHandlerNative();

            $process = $video->purgeMetaData()
                             ->setMetaData('title', 'Fliche Video Toolkit')
                             ->setMetaData('author', 'fliche.io')
                             ->saveNonBlocking($multi_output, null, Video::OVERWRITE_EXISTING, $progress_handler);

            while($progress_handler->completed !== true)
            {
                Trace::vars($progress_handler->probe(true, 1));
            }
        }
         
        echo '<h1>Executed Command</h1>';
        Trace::vars($process->getExecutedCommand());
        echo '<h1>RAW Executed Command</h1>';
        Trace::vars($process->getExecutedCommand(true));
        echo '<hr /><h1>FFmpeg Process Messages</h1>';
        Trace::vars($process->getMessages());
        echo '<hr /><h1>Buffer Output</h1>';
        Trace::vars($process->getBuffer(true));
        echo '<hr /><h1>Resulting Output</h1>';
        $output = $process->getOutput();
        $paths = array();
        if(empty($output) === false)
        {
            foreach ($output as $obj)
            {
                array_push($paths, $obj->getMediaPath());
            }
        }
        Trace::vars($paths);
return;
        exit;
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
        
        echo '<a href="?reset=1">Reset Process</a>';
    }
    catch(Exception $e)
    {
        echo '<h1>Error</h1>';
        Trace::vars($e->getMessage());
        echo '<h2>Exception</h2>';
        Trace::vars($e);

        echo '<a href="?reset=1">Reset Process</a>';
    }
}


/**
 * Function to insert videos and their posts in posts table
 */
function insert_video($v_file) {
  #global $wpdb;

  #$v_site_url = 'http://fliche.io/';
  $v_site_url = 'http://fishflicks.tv/';
  $v_cloud_url = 'http://fishflicks.s3.amazonaws.com/';
  $v_thumb = 'vidcat-Fishing-Addiction-140x90.jpg';
  $v_prev = 'vidcat-Fishing-Addiction-560x315.jpg';
  $v_post_id = 0;
  $v_member_id = 1;


  echo '$v_file: ' . $v_file . ''; echo "\n";


  $db = new mysqli('localhost', 'marty', 'pursueB@8', 'fliche_bedrock_000'); // fliche.io local
  #$db = new mysqli('localhost', 'juice', 'pursueBeauty8', 'wordpress'); // fliche.io remote
  #$db = new mysqli('localhost', 'wordpress', 'OCMKO2y1pv', 'wordpress'); // fishflicks.tv remote
  #shell_exec('ssh -f -L 3306:163.47.10.131:22 juice@163.47.10.131 sleep 60 >> logfile');
  #$db = mysqli_connect('127.0.0.1', 'juice', 'pursueBeauty8', 'wordpress', 3306);

  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }

  $sql = '
    SELECT  *
    FROM    `wp_hdflvvideoshare`
    WHERE   `file` LIKE "%' . $v_file . '%"
  ';

  if( !$result = $db->query($sql) ) {
    die('There was an error running the FILE EXISTS query [' . $db->error . ']');
  }

  // if does exist in db,
  // output the record found
  if( $result->num_rows > 0 ) {
    echo 'records exist: ' . $result->num_rows; echo "\n";
    while( $row = $result->fetch_assoc() ) {
      echo $row['vid']  . ' | ';
      echo $row['name'] . ' | ';
      echo $row['file'] . ' | <br>';
    }
    // update record first ??
    $db->close();
    return 'record already exists';
  }

  $result->free();



  /** Get last Id from post table */
  $sql = '
    SELECT  ID
    FROM    `wp_posts`
    ORDER BY ID DESC
    LIMIT   1
  ';

  if( !$result = $db->query($sql) ) {
    die('There was an error running the GET LAST ID query [' . $db->error . ']');
  }

  // if does exist in db,
  // output the record found
  if ( $result->num_rows > 0 ) {
    #echo 'records exist: ' . $result->num_rows; echo "\n";
    while( $row = $result->fetch_assoc() ) {
      #$v_post_id = $wpdb->get_var ( 'SELECT ID FROM ' . WVG_POSTS . ' ORDER BY ID DESC' ); # LIMIT 1
      $v_post_id = $row['ID'];
      #echo '$v_post_id: ' . $v_post_id; echo "\n";
      // new post id
      $v_post_id ++;
      echo '$v_post_id: ' . $v_post_id; echo "\n";
    }
  }

  $result->free();

  

  // video array as $v
  $v = array();
  // set defaults
  $v["post_id"]     = $v_post_id;
  $v["playlist_id"] = 3; // category id ( FA = 3, HLS = ? )
  // db
  $v["vid"]             = 0; // media_id
  $v["name"]            = 'Fishing Addiction ' . $v_file;
  $v["description"]     = 'Mark Berg\'s Fishing Addiction';
  $v["embedcode"]       = '';
  $v["file"]            = $v_cloud_url . $v_file;
  $v["streamer_path"]   = '';
  $v["hdfile"]          = $v_cloud_url . $v_file;
  $v["slug"]            = $v_post_id;
  $v["file_type"]       = 2;
  $v["duration"]        = '';
  $v["srtfile1"]        = '';
  $v["srtfile2"]        = '';
  $v["subtitle_lang1"]  = '';
  $v["subtitle_lang2"]  = '';
  $v["image"]           = $v_cloud_url . $v_thumb;
  $v["opimage"]         = $v_cloud_url . $v_prev;
  $v["download"]        = 0;
  $v["link"]            = '';
  $v["featured"]        = 0;
  $v["hitcount"]        = 0;
  $v["ratecount"]       = 0;
  $v["rate"]            = 0;
  $v["post_date"]       = '2016-01-16 00:00:00';
  $v["postrollads"]     = 0;
  $v["prerollads"]      = 0;
  $v["midrollads"]      = 0;
  $v["imaad"]           = 0;
  $v["publish"]         = 1;
  $v["islive"]          = 0;
  $v["member_id"]       = $v_member_id;
  $v["google_adsense"]  = 0;
  $v["google_adsense_value"] = 0;
  $v["ordering"]        = 0;
  $v["amazon_buckets"]  = 1;

  // -||- //
  #echo '$v["post_id"]: ' . $v["post_id"];
  #var_dump($v);

  #$db->close();
  #return 'true';


  $sql = '
    INSERT INTO `wp_hdflvvideoshare`
    ( 
      `vid`, 
      `name`, 
      `description`, 
      `embedcode`, 
      `file`, 
      `streamer_path`, 
      `hdfile`, 
      `slug`, 
      `file_type`, 
      `duration`, 
      `srtfile1`, 
      `srtfile2`, 
      `subtitle_lang1`, 
      `subtitle_lang2`, 
      `image`, 
      `opimage`, 
      `download`, 
      `link`, 
      `featured`, 
      `hitcount`, 
      `ratecount`, 
      `rate`, 
      `post_date`, 
      `postrollads`, 
      `prerollads`, 
      `midrollads`, 
      `imaad`, 
      `publish`, 
      `islive`, 
      `member_id`, 
      `google_adsense`, 
      `google_adsense_value`, 
      `ordering`, 
      `amazon_buckets`
    ) 
    VALUES
    ( 
       ' . $v["vid"] . ',
      "' . $v["name"] . '",
      "' . $v["description"] . '",
      "' . $v["embedcode"] . '",
      "' . $v["file"] . '",
      "' . $v["streamer_path"] . '",
      "' . $v["hdfile"] . '",
      "' . $v["slug"] . '",
       ' . $v["file_type"] . ',
      "' . $v["duration"] . '",
      "' . $v["srtfile1"] . '",
      "' . $v["srtfile2"] . '",
      "' . $v["subtitle_lang1"] . '",
      "' . $v["subtitle_lang2"] . '",
      "' . $v["image"] . '",
      "' . $v["opimage"] . '",
       ' . $v["download"] . ',
      "' . $v["link"] . '",
       ' . $v["featured"] . ',
       ' . $v["hitcount"] . ',
       ' . $v["ratecount"] . ',
       ' . $v["rate"] . ',
      "' . $v["post_date"] . '",
       ' . $v["postrollads"] . ',
       ' . $v["prerollads"] . ',
       ' . $v["midrollads"] . ',
       ' . $v["imaad"] . ',
       ' . $v["publish"] . ',
       ' . $v["islive"] . ',
       ' . $v["member_id"] . ',
       ' . $v["google_adsense"] . ',
      "' . $v["google_adsense_value"] . '",
       ' . $v["ordering"] . ',
       ' . $v["amazon_buckets"] . '
    )
  ';

  if( !$result = $db->query($sql) ) {
    die('There was an error running the INSERT VIDEO query [' . $db->error . ']');
  }

  var_dump($result);

  #$v["vid"] = $result->insert_id; 
  $v["vid"] = mysqli_insert_id($db);
  
  #$result->free(); // cannot free an insert


  #$db->close();
  #return 'true';


    /** Insert posts for all sample video into post table */

      /** Set seo title for smaple videos */
      $slug = fliche_sanitize_title( $v["name"] );
      /** Set post content for smaple videos */
      $post_content = '[hdvideo id=' . $v["vid"] . ']';
      /** Set post URL for sample videos */
      $guid = $v_site_url . '?post_type=videogallery&p=' . $v["post_id"];

      /** Insert new posts into post table for smaple vidoes */
      $sql = '
        INSERT INTO `wp_posts`
        ( 
          `post_author`,
          `post_date`, 
          `post_date_gmt`, 
          `post_content`, 
          `post_title`, 
          `post_excerpt`, 
          `post_status`, 
          `comment_status`, 
          `ping_status`, 
          `post_password`, 
          `post_name`, 
          `to_ping`, 
          `pinged`, 
          `post_modified`, 
          `post_modified_gmt`, 
          `post_content_filtered`, 
          `post_parent`, 
          `guid`, 
          `menu_order`, 
          `post_type`, 
          `post_mime_type`, 
          `comment_count` 
        )
        VALUES 
        ( 
           ' . $v["member_id"] . ',
          "' . $v["post_date"] . '", 
          "' . $v["post_date"] . '", 
          "' . $post_content . '", 
          "' . $v["name"] . '", 
          "", 
          "publish", 
          "open",
          "closed", 
          "", 
          "' . $slug . '", 
          "", 
          "", 
          "' . $v["post_date"] . '", 
          "' . $v["post_date"] . '", 
          "", 
          0, 
          "' . $guid . '", 
          "0",
          "videogallery", 
          "", 
          "0" 
        )
      ';

      if( !$result = $db->query($sql) ) {
        die('There was an error running the INSERT POST query [' . $db->error . ']');
      }

      var_dump($result);

      #$v["post_id"] = $result->insert_id;
      $v["post_id"] = mysqli_insert_id($db);

      #var_dump($v);

      #$result->free(); // cannot free an insert


      #$db->close();
      #return 'true';


    /** Insert categories/playlists for new videos */

      
      

      /** Insert new posts into post table for smaple vidoes */
      $sql = '
        INSERT INTO `wp_hdflvvideoshare_med2play`
        (
          `rel_id`, 
          `media_id`, 
          `playlist_id`, 
          `porder`, 
          `sorder`
        ) VALUES (
          "0", 
          ' . $v["vid"] . ',
          ' . $v["playlist_id"] . ',
          "0", 
          "0"
        )
      ';

      if( !$result = $db->query($sql) ) {
        die('There was an error running the INSERT POST query [' . $db->error . ']');
      }

      var_dump($result);

      #$v["rel_id"] = $result->insert_id;
      $v["rel_id"] = mysqli_insert_id($db);

      var_dump($v);

      #$result->free(); // cannot free an insert

  $db->close();
  return 'batch successful';


  #}

}


/**
 * Function to fetch videos
 */
function get_videos() {

  $db = new mysqli('localhost', 'marty', 'pursueB@8', 'fliche_bedrock_000'); // fliche.io local
  #$db = new mysqli('localhost', 'juice', 'pursueBeauty8', 'wordpress'); // fliche.io remote
  #$db = new mysqli('localhost', 'wordpress', 'OCMKO2y1pv', 'wordpress'); // fishflicks.tv remote
  #shell_exec('ssh -f -L 3306:163.47.10.131:22 juice@163.47.10.131 sleep 60 >> logfile');
  #$db = mysqli_connect('127.0.0.1', 'juice', 'pursueBeauty8', 'wordpress', 3306);

  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }

  $sql = '
    SELECT  *
    FROM    `wp_hdflvvideoshare`
    WHERE   `publish` = 0 
    AND     `vid` = 65
  ';

  if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }

  while($row = $result->fetch_assoc()){
    echo $row['vid']  . ' | ';
    echo $row['name'] . ' | ';
    echo $row['file'] . ' | <br>';
  }

  echo '<br>Total results: ' . $result->num_rows;

  $result->free();

  $db->close();

  return true;
}


/**
 * sanitize_title
 */

function fliche_sanitize_title($string)
{
    $url = $string;
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url); // substitutes anything but letters, numbers and '_' with separator
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url); // TRANSLIT does the whole job
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url); // keep only letters, numbers, '_' and separator
    return $url;
}