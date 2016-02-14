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
$bucket = 'fliche'; // fishflicks
$keykey = 'AKIAJNQLLLZRPPFRPKRQ';
$terces = 'bpEhI1Ly3QP115JPgHcpFPgLmOergE59FiJMskwF';

/* -|variables: file|- */
#$okeyname = 'HEY HEY HEY.txt';
$okeyname = 'Rapala Shadow Rap [HD, 720p].mp4';
// $origpath should be absolute path to a file on disk
$origpath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\HEYHEYHEY.txt';
#$origpath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\Rapala Shadow Rap [HD, 720p].mp4';
$savepath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\HEYHEYHEY.'.time().'.yay.txt';
#$savepath = 'C:\webroot\fliche\bedrock\web\fliche\juice\media\Rapala Shadow Rap [HD, 720p].'.time().'.yay.mp4';


/* -|begin process|- */
$s3 = new S3(	$keykey, $terces );
$result  = $s3->listBuckets(true);
$result2 = $s3->putObjectFile( $origpath, $bucket, $okeyname, S3::ACL_PUBLIC_READ );
$result3 = $s3->getObject( $bucket, $okeyname, $savepath );

$output .= '<pre>';
#var_dump($s3);
#var_dump($s3Client);
#var_dump($result);
foreach ( $result['buckets'] as $bucket ){
    // Each Bucket value will contain a Name and Time/CreationDate
    $output .= "{$bucket['name']} - {$bucket['time']}\n";
}
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


?>
<div class="span9">
  <div class="hero-unit">
    <h3>TEST: S3</h3>
    <p>
			<?php 
				echo "<pre>";
				var_dump($result2);
				echo "---\n";
				var_dump($result3);
				echo "</pre>";
				echo $output;
			?>
    </p>
  </div>
</div><!--/span-->
<?php

/* -|docs|- */
require_once './includes/footer.php';
