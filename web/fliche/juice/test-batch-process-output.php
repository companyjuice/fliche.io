<?php

    namespace FlicheToolkit;

    include_once './includes/bootstrap.php';
    
    echo '<a href="?method=blocking">Blocking</a> | <a href="?method=non-blocking">Non blocking</a><br />';
    
    try
    {
        $video = new Video($example_video_path);
        #$video->extractSegment(new Timecode(10), new Timecode(30));
        $process = $video->getProcess();

        
        $multi_output = new MultiOutput();
        
        /*
        $ogg_output = $example_video_path . '.fr5.ogg'; // './output/big_buck_bunny.multi1.ogg';
        $format = Format::getFormatFor($ogg_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_SQCIF);
        $format->setVideoFrameRate(5);
        $multi_output->addOutput($ogg_output, $format);

        $threegp25_output = $example_video_path . '.fr25.3gp'; // './output/big_buck_bunny.multi2.3gp';
        $format = Format::getFormatFor($threegp25_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoFrameRate(25);
        $multi_output->addOutput($threegp25_output, $format);

        $threegp12_output = $example_video_path . '.fr12.3gp'; // './output/big_buck_bunny.multi3.3gp';
        $format = Format::getFormatFor($threegp12_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $format->setVideoFrameRate(12);
        $multi_output->addOutput($threegp12_output, $format);
        */

        $ogg_output = $example_video_path . '.out.1.ogg'; // './output/big_buck_bunny.multi1.ogg';
        $format = Format::getFormatFor($ogg_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_SQCIF);
        $multi_output->addOutput($ogg_output, $format);

        $threegp_output = $example_video_path . '.out.2.3gp'; // './output/big_buck_bunny.multi2.3gp';
        $format = Format::getFormatFor($threegp_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $multi_output->addOutput($threegp_output, $format);

        $threegp_output = $example_video_path . '.out.3.3gp'; // './output/big_buck_bunny.multi3.3gp';
        $format = Format::getFormatFor($threegp_output, null, 'VideoFormat');
        $format->setVideoDimensions(VideoFormat::DIMENSION_XGA);
        $multi_output->addOutput($threegp_output, $format);

        if(isset($_GET['method']) === true && $_GET['method'] === 'blocking')
        {
            echo '<h2>Blocking Method</h2>';

            // If you use a blocking save but want to handle the progress during the block, then assign a callback within
            // the constructor of the progress handler.
            // IMPORTANT NOTE: most modern browser don't support output buffering any more.
            $progress_data = array();
            $progress_handler = new ProgressHandlerOutput(function($data) use (&$progress_data)
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
            $progress_handler = new ProgressHandlerOutput();
            $process = $video->purgeMetaData()
                             ->setMetaData('title', 'Fliche Video Toolkit')
                             ->setMetaData('author', 'fliche.io')
                             ->saveNonBlocking($multi_output, null, Video::OVERWRITE_EXISTING, $progress_handler);

            while($progress_handler->completed !== true)
            {
                $data = $progress_handler->probe(true, 1);
                Trace::vars($data);
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
