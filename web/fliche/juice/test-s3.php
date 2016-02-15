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
#use \FlicheToolkit;

/* -|variables: script|- */
$output = '';
$result = [];
$bucket = 'fishflicks'; // fishflicks
$keykey = 'AKIAJNQLLLZRPPFRPKRQ';
$terces = 'bpEhI1Ly3QP115JPgHcpFPgLmOergE59FiJMskwF';

/* -|variables: file|- */
$okeyname = 'HEY HEY HEY.txt';
#$okeyname = 'Rapala Shadow Rap [HD, 720p].mp4';
// $origpath should be absolute path to a file on disk
$origpath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\HEYHEYHEY.txt';
#$origpath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\Rapala Shadow Rap [HD, 720p].mp4';
$savepath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\HEYHEYHEY.'.time().'.yay.txt';
#$savepath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\Rapala Shadow Rap [HD, 720p].'.time().'.yay.mp4';


/* -|begin process|- */
$s3 = new S3(	$keykey, $terces );
#$result1 = $s3->listBuckets(true);
#$result2 = $s3->putObjectFile( $origpath, $bucket, $okeyname, S3::ACL_PUBLIC_READ );
#$result3 = $s3->getObject( $bucket, $okeyname, $savepath );
$bucket_content = $result4 = $s3->getBucket( $bucket );

$output .= '<pre>';

#var_dump($s3);
#var_dump($s3Client);
#var_dump($result1);
/*
foreach ( $result1['buckets'] as $bucket ){
    // Each Bucket value will contain a Name and Time/CreationDate
    $output .= "{$bucket['name']} - {$bucket['time']}\n";
}
$output .= "\n";
*/
/*
foreach ( $result4 as $object ){
    // Each object value will contain a Name and Time/CreationDate
    $output .= "{$object['name']} - {$object['time']}\n";
}
$output .= "\n";
*/
foreach ($bucket_content as $key => $value) {
  // ignore s3 "folders"
  if (preg_match("/\/$/", $key)) continue;

  // explode the path into an array
  $file_path = explode('/', $key);
  $file_name = end($file_path);
	$file_folder = substr($key, 0, (strlen($file_name) * -1)+1);
  $file_folder = prev($file_path);

  $s3_url = "https://s3.amazonaws.com/{$bucket}/{$key}";

  if ( $file_folder == '' ){

  	$data[$key] = array(
			'file_name' => $file_name,
			's3_key' => $key,
			'file_folder' => $file_folder,
			'file_size' => $value['size'],
			'created_on' => date('Y-m-d H:i:s', $value['time']),
			's3_link' => $s3_url,
			'md5_hash' => $value['hash']
		);

    // Each object value will contain a Name and Time/CreationDate
    #$output .= "{$data[$key]['file_name']} | {$data[$key]['file_size']} | {$data[$key]['created_on']}\n";
    $output .= "{$data[$key]['file_name']}\n";

  }
}
#var_dump($data);

#$output .= list_s3_bucket($bucket);

$output .= '</pre>';


/*						
// Instantiate the client.
$s3 = S3Client::factory(array(
  'region'  => 'ap-southeast-2',
  'version' => 'latest'
));

// Upload a file.
$result = $s3->putObject(array(
    'Bucket'       => $bucket,
    'Key'          => $okeyname,
    'SourceFile'   => $origpath,
    'ContentType'  => 'text/plain',
    'ACL'          => 'public-read',
    'StorageClass' => 'REDUCED_REDUNDANCY',
    'Metadata'     => array(    
        'hey1' => 'hey 1',
        'hey2' => 'hey 2',
        'hey3' => 'hey 3'
    )
));

echo $result['ObjectURL'];
*/

/**
 * http://stackoverflow.com/questions/19424536/get-files-from-amazon-s3-buckets-sub-folder
 */
#public function list_s3_bucket($bucket_name)
function list_s3_bucket($bucket_name)
{
    // initialize the data array
    $data;
    $bucket_content = $this->s3->getBucket($bucket_name);

    foreach ($bucket_content as $key => $value) {
        // ignore s3 "folders"
        if (preg_match("/\/$/", $key)) continue;

        // explode the path into an array
        $file_path = explode('/', $key);
        $file_name = end($file_path);
            $file_folder = substr($key, 0, (strlen($file_name) * -1)+1);
        $file_folder = prev($file_path);

        $s3_url = "https://s3.amazonaws.com/{$bucket_name}/{$key}";

        $data[$key] = array(
            'file_name' => $file_name,
                    's3_key' => $key,
            'file_folder' => $file_folder,
            'file_size' => $value['size'],
            'created_on' => date('Y-m-d H:i:s', $value['time']),
            's3_link' => $s3_url,
            'md5_hash' => $value['hash']);
    }
    return $data;
}

?>
<div class="span9">
  <div class="hero-unit">
    <h3>TEST: S3</h3>
    <p>
			<?php 
				echo $output;
			?>
    </p>
  </div>
</div><!--/span-->
<?php

/* -|docs|- */
require_once './includes/footer.php';
