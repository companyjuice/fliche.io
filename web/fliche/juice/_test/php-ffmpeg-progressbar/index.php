<?php
/*
#############################################
#         PHP FFMPEG progressbar 2 v2.0     #
#    Based on FFMPEG Progressbar with PHP   #
#       (C) 2010 by Vaclav Jirovsky         #     
#           Example file of use             # 
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

include_once 'inc/config.inc.php';
include_once 'inc/ffmpegprogressbar2.class.php';
ob_flush();
?>
<html>
<head>
<title>PHP FFMPEG progressbar 2 - demo</title>
<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<style>
body{font-family:calibri;background:#ECEBFA;}
.body{background:#F8F8F8;margin:20px;padding:20px;border:1px solid silver;}
.progress-a{font-size:12px;}
.progress-a a{color:#C34E00;text-decoration: none;border-bottom:1px #C34E00 dotted;}
.progress-a a:hover{color:#005B88;border-bottom:1px #005B88 dotted;}
.progress-error{color:#9A1E08;font-weight:bold;line-height: 30px;font-size:15px;}
</style>
<div class="body">
<h1>Demo PHP FFMPEG Progressbar 2</h1>
<h2>Progressbar</h2>

<?php
// Specifie Inputfile for FFMPEG
$FFMPEGInput='C:\htdocs\test.avi';

// Specifie Outputfile for FFMPEG
$FFMPEGOutput='C:\htdocs\test.flv';

// Specifie (optional) Parameters for FFMPEG
$FFMPEGParams=' -acodec aac -ab 96k -vcodec libx264 -fpre "'.FFMPEG_PRESET_DIR.'libx264-slower.ffpreset" -qmax 25 -strict experimental -threads 0';
$_GET['pkey']=strip_tags(AddSlashes($_GET['pkey']));

  if(!$_GET["pkey"]){
    $pkey=rand();
  }
  elseif(file_exists('log/'.$_GET["pkey"].'.ffmpeg')){
    $pkey=$_GET["pkey"];
  }
  else{
    $pkey=rand();
  }

// initializing and create ProgressBar
flush();

$FFMPEGProgressBar2 = &new FFMPEGProgressBar2();

flush();

// Show Progressbar

$FFMPEGProgressBar2->Show($pkey);
if(!$_GET["pkey"] || !file_exists('log/'.$_GET["pkey"].'.ffmpeg')){
flush();
$FFMPEGProgressBar2 = &new FFMPEGProgressBar2();
flush();
@$FFMPEGProgressBar2->execFFMPEG($FFMPEGInput, $FFMPEGOutput, $FFMPEGParams, $pkey);
}
echo "<br /><br />
<span class=\"progress-a\">Now you can close this window and watch this progressbar later by this link: <a href=\"".FFMPEG_WEB_PATH."index.php?pkey=".$FFMPEGProgressBar2->pkey."\">".FFMPEG_WEB_PATH."index.php?pkey=".$FFMPEGProgressBar2->pkey."</a></span>.";

?>
<br /><br />
Sample code:<br /><br /> 
<div style="height:300px;border:1px solid silver;width:1000px;background:white;padding:10px;overflow:auto;">
<?echo highlight_string('<?php

$FFMPEGInput=\'C:\full_path\to\input.avi\';
$FFMPEGOutput=\'C:\full_path\to\output.flv\';

$FFMPEGParams=\' -acodec aac -ab 96k -vcodec libx264 -fpre "\'.FFMPEG_PRESET_DIR.\'libx264-slower.ffpreset" -qmax 25 -strict experimental -threads 0\'; // example of parameters for FFMPEG, (optional))
$_GET[\'pkey\']=strip_tags(AddSlashes($_GET[\'pkey\'])); // securing _GET

  if(!$_GET[\'pkey\']){
    $pkey=rand();
  }
  elseif(file_exists(\'log/\'.$_GET[\'pkey\'].\'.ffmpeg\')){
    $pkey=$_GET[\'pkey\'];
  }
  else{
    $pkey=rand();
  }

// initializing and create ProgressBar
flush();

$FFMPEGProgressBar2 = &new FFMPEGProgressBar2();

flush();

// Show Progressbar

$FFMPEGProgressBar2->Show($pkey);
if(!$_GET[\'pkey\'] || !file_exists(\'log/\'.$_GET[\'pkey\'].\'.ffmpeg\')){
flush();
$FFMPEGProgressBar2 = &new FFMPEGProgressBar2();
flush();
@$FFMPEGProgressBar2->execFFMPEG($FFMPEGInput, $FFMPEGOutput, $FFMPEGParams, $pkey);
}
echo "<br /><br />
<span class=\"progress-a\">Now you can close this window and watch this progressbar later by this link: <a href=\"".FFMPEG_WEB_PATH."index.php?pkey=".$FFMPEGProgressBar2->pkey."\">".FFMPEG_WEB_PATH."index.php?pkey=".$FFMPEGProgressBar2->pkey."</a></span>.";

?>',1);
?>
</div>
</div>
</body>
</html>