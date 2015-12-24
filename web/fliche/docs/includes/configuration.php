<?php
    
    //define('PROGRAM_PATH', null);
    //define('PROGRAM_PATH', '/opt/local/bin');
    define('PROGRAM_PATH', '/home/juice/bin');
    define('FFMPEG_PROGRAM', PROGRAM_PATH.DIRECTORY_SEPARATOR.'ffmpeg');
    define('FFPROBE_PROGRAM', PROGRAM_PATH.DIRECTORY_SEPARATOR.'ffprobe');
    define('TEMP_PATH', '../juice/tmp');
    
    define('BASE', dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);

    require_once '../autoloader.php';   
    if(is_file('../vendor/autoload.php')) require_once '../vendor/autoload.php';

//  define the error callback
    function __errorHandler()
    {      
        $args = func_get_args();      
        $count = func_num_args();  
        \FlicheToolkit\Trace::vars('ERROR---------', $count === 1 ? 'exception' : 'error', $args);
    }
    set_error_handler('__errorHandler');
    set_exception_handler('__errorHandler');
    
    require dirname(__FILE__).'/functions.php';
    
    $config = new \FlicheToolkit\Config(array(
        'temp_directory' => TEMP_PATH,
        'ffmpeg' => FFMPEG_PROGRAM,
        'ffprobe' => FFPROBE_PROGRAM,
    ));
    
    $example_video_path = BASE.'juice/media/BigBuckBunny_320x180.mp4';
    $example_audio_path = BASE.'juice/media/Ballad_of_the_Sneak.mp3';
