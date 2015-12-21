<?php
//get so far encoded time
public function getEncodedTime(){

    $FFMPEGLog = file_get_contents('ffmpeg.log');
    $times     = explode('time=', $FFMPEGLog);
    $ctime     = count($times)-1;
    $timed     = explode(' bitrate=', $times[$ctime]);
    //print_r($timed);
    $nEncTime  = $timed[0];
    list($h, $m, $s) = explode(":", $nEncTime);
    $s = ceil($s); // 21.40 seconds => 22 seconds
    $nEncTime = hms2sec($h, $m, $s);

    return $nEncTime;

}

//covert H:i:s time to seconds
public function hms2sec ($h, $m, $s) {

    //list($h, $m, $s) = explode (":", $hms);
    $seconds = 0;
    $seconds += (intval((string)$h) * 3600);
    $seconds += (intval((string)$m) * 60);
    $seconds += (intval((string)$s));
    return $seconds;

}


//get total length of file
public function getTotalTime()
{
    $play_time_sec = 0;

    $lines = file('ffmpeg.log');
    foreach ($lines as $line_num => $line) {
        if(strpos($line, 'Duration') !== false) {
            $line = explode("Duration: ", $line);
            $line = explode(",", $line[1]);
            $line = explode(":", $line[0]);

            $play_time_sec = 0;
            $play_time_sec += intval((string)$line[0]) * 60 * 60; // hour
            $play_time_sec += intval((string)$line[1]) * 60; // minute
            $play_time_sec += intval((string)round($line[2])); // second
            break;
        }
    }

    return $play_time_sec;
}


//get percents completed:
public function getPercentsComplete()
{

    $sFileContents = file_get_contents('ffmpeg.log');
    if(stripos($sFileContents, 'No more inputs to read from, finishing') !== false) {
        return 100;
    }

    $nTotalTime = getTotalTime();
    $nEncodedTime = getEncodedTime();

    if($nEncodedTime <= 0)
        return 0;

    if($nEncodedTime >= $nTotalTime)
        return 100;

    $nPercentsComplete = round(($nEncodedTime/$nTotalTime)*100);

    return $nPercentsComplete;
}