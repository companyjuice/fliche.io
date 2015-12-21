<?php 
	include('./s3-signed-urls.juice.php'); 
?>

<a href="<?php echo getSignedUrl(
	$awsAccessKey,
	$secretKey,
	$bucket,
	'tv/Alaska Fly Fishing - Rainbow Trout (1080p HD) Top rated video (Low).mp4',
	'5',
	array( // Custom parameters to force a download and change the file name.
		'response-content-disposition' => 'attachment; filename=Alaska Fly Fishing - Rainbow Trout (Download Version).mp4',
		'response-content-type' => 'application/octet-stream',
	)
); ?>">tv/Alaska Fly Fishing - Rainbow Trout (1080p HD) Top rated video (Low).mp4</a>

-||-