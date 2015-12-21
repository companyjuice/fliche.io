<?php
/* Vzaar API Implementation
 * Works for public API and OAuth both
 * All methods listed till today (October 29, 2009) - fully supported
 *
 * In order to use OAuth based API please make sure you've generated your own
 * token. All samples in /test folder provided just for the reference.
 *
 * This PHP implementation been tested under Linux/Apache and Windows/IIS both.
 *
 * @author skitsanos
 * @version 1.4
 */

require_once 'Vzaar.php';
require_once 'User.php';
require_once 'Video.php';
require_once 'VideoList.php';
require_once 'AccountType.php';

//require_once 'HttpRequest.php';

# api helper ??
#require_once '../examples/api_helper.php';
function generateRandomStr($len = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $len);
};

/**
 * This API call returns the user's public details along with it's relevant metadata
 */
//Vzaar::$token = 'rWKWWj9IWuTxGqELAAYEHs03rAVKTUCj4gwCExoAvv8';
//Vzaar::$secret = 'damartman'; // 5266871
Vzaar::$token = 'hFtppj7cqYvux6aEKbA17NlYYGmVlOnBedPgr1e1g1w';
Vzaar::$secret = 'companyjuice'; // 121596

# ??
Vzaar::$enableHttpVerbose = true;

//date_default_timezone_set("Europe/London");
date_default_timezone_set("America/Los_Angeles");

/* CUSTOM CODE -- MM */

echo('<h2>Vzaar::whoAmI() -- OAuth API</h2>');
echo(Vzaar::whoAmI()); print_r('<br>');
//print_r('Welcome ' . Vzaar::whoAmI()); print_r('<br>');

//echo('<h2>Vzaar::getUserDetails(\'skitsanos\')</h2>');
//var_dump(Vzaar::getUserDetails('skitsanos')); print_r('<br>');
#echo('<h2>Vzaar::getUserDetails(\'damartman\')</h2>');
#var_dump(Vzaar::getUserDetails('damartman')); print_r('<br>');
echo('<h2>Vzaar::getUserDetails(\'companyjuice\')</h2>');
var_dump(Vzaar::getUserDetails('companyjuice')); print_r('<br>');

//echo('<h2>Vzaar::getAccountDetails(1) [skitsanos]</h2>');
//var_dump(Vzaar::getAccountDetails(1)); print_r('<br>');
//echo('<h2>Vzaar::getAccountDetails(122586) -- damartman</h2>');
//var_dump(Vzaar::getAccountDetails(122586)); print_r('<br>');
//echo('<h2>Vzaar::getAccountDetails(121596) -- companyjuice</h2>');
//var_dump(Vzaar::getAccountDetails(121596)); print_r('<br>');

#echo('<h2>Vzaar::getVideoList(\'damartman\', true, 10)</h2>');
#print_r(Vzaar::getVideoList('damartman', true, 10));
echo('<h2>Vzaar::getVideoList(\'companyjuice\', true, 10)</h2>');
print_r(Vzaar::getVideoList('companyjuice', true, 10));

//echo('<h2>Vzaar::searchVideoList(\'skitsanos\')</h2>');
//var_dump(Vzaar::searchVideoList('skitsanos', true)); print_r('<br>');
#echo('<h2>Vzaar::searchVideoList(\'damartman\', true)</h2>');
#var_dump(Vzaar::searchVideoList('damartman', true)); print_r('<br>');
echo('<h2>Vzaar::searchVideoList(\'companyjuice\', true)</h2>');
var_dump(Vzaar::searchVideoList('companyjuice', true)); print_r('<br>');


/* UPLOAD VIDEO */
echo('<h2>Vzaar::uploadVideo(\'../../media/video.mp4\')</h2>');
#print_r(Vzaar::uploadVideo('../examples/video.mp4')); print_r('<br>');
$title 		= "fishflicks-api-php-" . generateRandomStr(5);
var_dump($title); print_r('<br>');
$guid 		= Vzaar::uploadVideo('../examples/video.mp4'); // self::$filePath
var_dump($guid); print_r('<br>');
$videoId 	= Vzaar::processVideo($guid, $title, "test test test", "");
var_dump($videoId); print_r('<br>');
$vid 			= Vzaar::getVideoDetails($videoId, true);
var_dump($vid); print_r('<br>');



/* TEST TEST TEST */
echo('<h2>TEST TEST TEST</h2>');



/**
 * Public API
 */
//var_dump(Vzaar::getVideoDetails(17069)); print_r('<br><br>');
//var_dump(Vzaar::getVideoDetails(36356, true)); print_r('<br><br>');
//var_dump(Vzaar::getAccountDetails(1)); print_r('<br><br>');
//var_dump(Vzaar::getVideoList('skitsanos', true, 10)); print_r('<br><br>');
//var_dump(Vzaar::searchVideoList('skitsanos', 'true', 's3')); print_r('<br><br>');
//var_dump(Vzaar::getUserDetails('skitsanos')); print_r('<br><br>');
//var_dump(Vzaar::getUserDetails('damartman')); print_r('<br><br>');
//var_dump(Vzaar::getVideoDetails(21791,true)); print_r('<br><br>');
//var_dump(Vzaar::getUploadSignature()); print_r('<br><br>');

//print_r(Vzaar::getUploadSignature('http://skitsanos.com')); print_r('<br><br>');

//print_r(Vzaar::uploadSubtitle('en', 4699267, 'some subtitle')); print_r('<br><br>');

//print_r(Vzaar::getVideoDetails(632017, true)); print_r('<br><br>');

/**
 * OAuth API
 */
//print_r(Vzaar::whoAmI()); print_r('<br><br>');
//var_dump(Vzaar::searchVideoList('skitsanos', true)); print_r('<br><br>');
//print_r(Vzaar::getVideoList('skitsanos', true, 2, 'skitsanos%20tv'));
//print_r(Vzaar::getVideoDetails(4699267, true)->html);

//print_r(Vzaar::uploadVideo("/Users/florin/Documents/Vzaar/movie/sample.mkv"));
//print_r(Vzaar::processVideo("vz6a7c7ce3f65140fa8d0f1a4eae0d42a9", "Test video", "Some awesome video", "label"));
//print_r(Vzaar::getVideoDetails(4699267));
//print_r(Vzaar::editVideo(4699267,"Testing video", "For testing purpose", true ));
//print_r(Vzaar::getAccountDetails(1));
//print_r(Vzaar::generateThumbnail(4699267, 243));
//print_r(Vzaar::deleteVideo(4699267));
//print_r(ini_get_all());
//print_r(Vzaar::editVideo(434506, 'My Video tv Title', 'Some amazing description', 'true', 'http://skitsanos.tv/content/746959'));
//print_r(Vzaar::deleteVideo(517885));
?>