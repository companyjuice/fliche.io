<?php

/* -|toolkit|- */
require_once './includes/bootstrap.php';
#BASE.'juice/includes/config.php';
/* -|docs|- */
require_once './includes/header.php';
/* -|plugin|- */
#require_once './includes/s3/S3.php';

/* -|class: vendor\tpyo\amazon-s3-php-class\S3 no namespace|- */
use \S3;

/* -|sdk3: vendor\aws\aws-sdk-php\src\functions.php namespace Aws|- 
use Aws\S3\S3Client;
*/

/* -|src: FlicheToolkit\ namespace FlicheToolkit|- */
#namespace FlicheToolkit;
use FlicheToolkit\Video;
use FlicheToolkit\MultiOutput;
use FlicheToolkit\Format;
use FlicheToolkit\ProgressHandlerNative;
use FlicheToolkit\Trace;


// run this file
_construct();

function _construct() {
  if( 1 == 1 ) {
    run_batch();
  }
}

function run_batch() {

	/* -|variables: script|- */
	$output = '';
	/* -|variables: mysql|- */
	$servername = "localhost";
	$username = "marty";
	$password = "pursueB@8";
	$dbname = "fishflicks_wpdo_003";
	$qVideos = [];
	/* -|variables: s3|- */
	$bucket = 'fishflicks'; // fliche
	$keykey = 'AKIAJNQLLLZRPPFRPKRQ';
	$terces = 'bpEhI1Ly3QP115JPgHcpFPgLmOergE59FiJMskwF';
	/* -|variables: s3 object/file|- */
	$okeyname = '';
	$savename = '';
	$origpath = '';
	$savepath = '';

	/**
	 * AUTOMATION
	 * Fliche Batch Video File Processing
	 * 
	 * 1) Query DB for files (that need processing)
	 * 2) Loop thru files (as array of DB records)
	 * 3) - Download file (to local server for processing)
	 * 4) - Process file (using PHP -> FFMpeg)
	 * 5) - Upload new/processed file (to remote server S3)
	 * 6) - Check that new/processed file exists (on remote server S3)
	 * 7) - Save new data to file records in DB
	 * 8) - Delete downloaded and new/processed files (from local server)
	 * 9) Continue Loop thru files (until complete)
	 * 10) Output process results
	 * 11) Provide notifications
	 */

	/* 
	 * 1)  Query DB for files (that need processing) 
	 */
	// create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// query string
	$sql = 'SELECT 		vid, name, file, hdfile, image, opimage, publish 
					FROM 			wp_hdflvvideoshare 
					WHERE 		publish = 1
					ORDER BY 	vid DESC
					LIMIT 		1
	';
	// run query
	$qResult = $conn->query($sql);

	// loop over query results
	if ($qResult->num_rows > 0) {
		// output data of each row to separate array
		while($row = $qResult->fetch_assoc()) {
			#echo "vid: ". $row["vid"]. " | File: ". $row["file"]. " | Name: " . $row["name"] . "<br>";
			$qVideos[$row['vid']] = $row;
		}
	} 
	else {
		echo "0 results";
	}

	// close connection
	$conn->close();

	echo '<pre>';
	#var_dump($qVideos);
	echo count($qVideos) . ' results';
	echo '</pre>';
	#echo '-|end|-';
	#exit;


	/* 
	 * 2) Loop thru files (as array of DB records) 
	 */
	foreach ($qVideos as $v) 
	{
		# code...
		#echo '<pre>';
		#echo 'vid: '.$v['vid'].' | file: '.$v['file'].' | '.'<br>';
		#var_dump($v);
		#var_dump($v['vid']);
		#echo '<br>---<br>';
		#echo '</pre>';

		/* -|variables: file|- */
		#$okeyname = 'Rapala Shadow Rap [HD, 720p].mp4';
		$okeyname = str_ireplace('http://fishflicks.vidflix.co/', '', $v['file']);
		$okeyname = 'video.mp4';
		$savename = str_ireplace('.mp4', '.mp4.nat.480p.mp4', $okeyname);
		#$origpath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\Rapala Shadow Rap [HD, 720p].mp4';
		#$origpath = 'G:\bkp\media\\'.$bucket.'\orig\\'.$okeyname.'';
		$origpath = 'D:\media\\'.$bucket.'\orig\\'.$okeyname.'';
		#$savepath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\Rapala Shadow Rap [HD, 720p].'.time().'.yay.mp4';
		#$savepath = 'G:\bkp\media\\'.$bucket.'\save\\'.$savename.'';
		$savepath = 'D:\media\\'.$bucket.'\save\\'.$savename.'';

		/* 
		 * 3) - Download file (to local server for processing) 
		 */
		if ( !file_exists( $origpath ) ){
			$s3 = new S3(	$keykey, $terces );
			#$list_buckets = $s3->listBuckets(true);
			$getobject = $s3->getObject( $bucket, $okeyname, $origpath );
			echo "<pre>";
			echo "getobject: \n";
			var_dump($getobject);
			echo "---\n";
			echo "</pre>";
		}
		else {
			echo "<pre>";
			echo "origpath file already exists: \n";
			var_dump($origpath);
			echo "---\n";
			echo "</pre>";
		}

		/*
		 * 4) - Process file (using PHP -> FFMpeg)
		 */
		if ( !file_exists( $savepath ) ){
			$is_processed = process_video( $origpath, $savepath );
		}
		else {
			$is_processed = true;
			echo "<pre>";
			echo "savepath file already exists: \n";
			var_dump($savepath);
			echo "---\n";
			echo "</pre>";
		}

		/*
		 * 5) - Upload new/processed file (to remote server S3)
		 */
		if ( $is_processed ) {
			$s3 = new S3(	$keykey, $terces );
			$putobject = $s3->putObjectFile( $savepath, $bucket, $savename, S3::ACL_PUBLIC_READ );
			echo "<pre>";
			echo "putobject: \n";
			var_dump($putobject);
			echo "---\n";
			echo "</pre>";
		}
		else {
			echo "<pre>";
			echo "process_video failed. is_processed: \n";
			var_dump($is_processed);
			echo "---\n";
			echo "</pre>";
		}

		#echo '-|end|-';
		#exit;

	}; // end foreach $qVideos[]

	?>
	<div class="span9">
	  <div class="hero-unit">
	    <h3>TEST: BATCH X3</h3>
	    <p>
				<?php 
					echo "<pre>";
					echo "---\n";
					echo $output;
					echo "---\n";
					echo "</pre>";
				?>
	    </p>
	  </div>
	</div><!--/span-->
	<?php

	/* -|docs|- */
	require_once './includes/footer.php';

}

/**
 * Function to process videos using FFMPEG
 */
function process_video( $v_src_file_path, $v_out_file_path ) {

    try
    {
        $video = new Video($v_src_file_path);
        #$video->extractSegment(new Timecode(10), new Timecode(30));
        $process = $video->getProcess();
        #$process->setProcessTimelimit(5); // in seconds (did not work)


        // 240p, 360p, 480p, 720p, 1080p
        $multi_output = new MultiOutput();


        /* 240p
        $mp4_240p_output = $v_out_file_path;
        $format = Format::getFormatFor($mp4_240p_output, null, 'VideoFormat');
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(426, 240);
        $multi_output->addOutput($mp4_240p_output, $format);
        */
        /* 360p
        $mp4_360p_output = $v_out_file_path;
        $format = Format::getFormatFor($mp4_360p_output, null, 'VideoFormat');
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(640, 360);
        $multi_output->addOutput($mp4_360p_output, $format);
        */
        // 480p
        $mp4_480p_output = $v_out_file_path;
        $format = Format::getFormatFor($mp4_480p_output, null, 'VideoFormat');
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(854, 480);
        $multi_output->addOutput($mp4_480p_output, $format);
        //
        /* 720p
        $mp4_720p_output = $v_out_file_path;
        $format = Format::getFormatFor($mp4_720p_output, null, 'VideoFormat');
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(1280, 720);
        $multi_output->addOutput($mp4_720p_output, $format);
        */
        /* 1080p
        $mp4_1080p_output = $v_out_file_path;
        $format = Format::getFormatFor($mp4_1080p_output, null, 'VideoFormat');
        #$format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoDimensions(1920, 1080);
        $multi_output->addOutput($mp4_1080p_output, $format);
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


        echo '<h3>Non Blocking Method</h3>';

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

         
        echo '<h1>Executed Command</h1>';
        Trace::vars($process->getExecutedCommand());
        
        echo '<h1>RAW Executed Command</h1>';
        Trace::vars($process->getExecutedCommand(true));
        
        /*
        echo '<hr /><h1>FFmpeg Process Messages</h1>';
        Trace::vars($process->getMessages());
        */
        /*
        echo '<hr /><h1>Buffer Output</h1>';
        Trace::vars($process->getBuffer(true));
        */
        
        echo '<hr /><h1>Resulting Output</h1>';
        $output = $process->getOutput();
        #var_dump($output);
        
        $paths = array();
        if(empty($output) === false)
        {
            foreach ($output as $obj)
            {
              #if ( is_object( $obj ) 
              #  && function_exists( $obj->getMediaPath() ) ){
              #  array_push($paths, $obj->getMediaPath());
              #}
            }
        }
        Trace::vars($paths);

        return true;
        #exit;
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
        return false;
    }
    catch(Exception $e)
    {
        echo '<h1>Error</h1>';
        Trace::vars($e->getMessage());
        echo '<h2>Exception</h2>';
        Trace::vars($e);

        echo '<a href="?reset=1">Reset Process</a>';
        return false;
    }

    // assume something bad happened
    return false;
} // end function process_video()


#echo '-|end script|-';
#exit;


/* ---------- */
/* scratchpad */
/* ---------- */
/*
$output .= '<pre>';
#var_dump($s3);
#var_dump($list_buckets);
foreach ( $list_buckets['buckets'] as $bucket ){
	// Each Bucket value will contain a Name and Time/CreationDate
	$output .= "{$bucket['name']} - {$bucket['time']}\n";
}
$output .= '</pre>';
*/
