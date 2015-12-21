<?PHP
/*
#############################################
#         PHP FFMPEG progressbar 2 v2.0     #
#    Based on FFMPEG Progressbar with PHP   #
#       (C) 2010 by Vaclav Jirovsky         #     
#        Return progress json plugin        # 
#############################################

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

include_once dirname(__FILE__).'/config.inc.php';
include_once dirname(__FILE__).'/ffmpegprogressbar2.class.php';



// DONT CHANGE!!!
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 01 Jan 1970 00:00:00 GMT');
//header('Content-type: application/json');
//header("Content-Disposition: attachment; filename=\"progress.json\"");
flush();
// DONT CHANGE!!!
$FFMPEGProgressBar=&new FFMPEGProgressBar2();

// Output Minutes and Seconds...
function sec2min($sekunden)
{
    $stunden = floor($sekunden / 3600);
    $minuten = floor(($sekunden - ($stunden * 3600)) / 60);
    $sekunden = round($sekunden - ($stunden * 3600) - ($minuten * 60), 0);

    if ($stunden <= 9) {
        $strStunden = "0" . $stunden;
    } else {
        $strStunden = $stunden;
    }

    if ($minuten <= 9) {
        $strMinuten = "0" . $minuten;
    } else {
        $strMinuten = $minuten;
    }

    if ($sekunden <= 9) {
        $strSekunden = "0" . $sekunden;
    } else {
        $strSekunden = $sekunden;
    }

    return "$strStunden:$strMinuten:$strSekunden";
} 

if($FFMPEGProgressBar->checkFFMPEG($_GET["pkey"])){
$en_time=$FFMPEGProgressBar->GetEncodedTime($_GET["pkey"]);
$to_time=$FFMPEGProgressBar->GetTotalTime($_GET["pkey"]);
// Output
?>
{"time_encoded":"<?=round($en_time)?>","time_total":"<?=round($to_time)?>","time_encoded_min":"<?=sec2min($en_time)?>","time_total_min":"<?=sec2min($to_time)?>"}
<? 
//$FFMPEGProgressBar->logError('VJ error: {"time_encoded":'.$en_time.';"'.round($en_time).'","time_total":"'.$to_time.';'.round($to_time).'","time_encoded_min":'.$en_time.';"'.sec2min($en_time).'","time_total_min":"'.$to_time.';'.sec2min($to_time).'"}',date("d-m-y").'.error.log');
}else{ 
   $FFMPEGProgressBar->logError('ffmpeg-progressbar: can\'t open FFMPEG-Log \'./log/'.$pkey.'.ffmpeg\'',date("d-m-y").'.error.log');
} ?>
