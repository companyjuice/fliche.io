<?php

#echo 'TEST S3 <br>';
namespace FlicheToolkit;

include_once './includes/bootstrap.php';

/* -||- */
require_once './includes/header.php';


#use Aws\Common\Aws;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$output = '';
$result = [];
$bucket = 'fishflicks';
$keykey = 'AKIAJNQLLLZRPPFRPKRQ';
#$terces = 'bpEhI1Ly3QP115JPgHcpFPgLmOergE59FiJMskwF';
$terces = 'YAVyQsvAhtNPyMjx/ebw643v1qQU7UIruVJOh2pNYyNSiGsHv0sNoJifXBYbiskT';

//
try 
{
  // Create the AWS service builder, providing the path to the config file
  #$aws = Aws::factory('includes/config.aws.php');
  // Start using S3
  #$s3 = $aws->get('S3');
  // Upload a file to S3
  #$s3->putObject(array(
  #    'Bucket' => $bucket,
  #    'Key'    => 'HEY HEY HEY.txt',
  #    'Body'   => 'HEY HEY HEY'
  #));
  
  /*
  // Instantiate the S3 client with your AWS credentials
  $s3Client = S3Client::factory(array(
      'credentials' => array(
          'key'     => $keykey,
          'secret'  => $terces
      ),
      'version' => 'latest',
      'region'  => 'ap-southeast-2'
  ));
  */
  $s3Client = new S3Client([
      'credentials' => [
          'key'     => $keykey,
          'secret'  => $terces
      ],
      'region'  => 'ap-southeast-2',
      'version' => 'latest'
  ]);
  $result = $s3Client->listBuckets();

  foreach ( $result['buckets'] as $bucket ){
      // Each Bucket value will contain a Name and Time/CreationDate
      echo "{$bucket['name']} - {$bucket['time']}\n";
  }

  //
  #$s3Client->putObject(array(
  #    'Bucket' => $bucket,
  #    'Key'    => 'HEY HEY HEY.txt',
  #    'Body'   => 'HEY HEY HEY'
  #));
  /*
  // Makes an anonymous request. The Object would need to be publicly readable for this to succeed.
  $result = $s3Client->getObject(array(
      'Bucket' => $bucket,
      'Key'    => 'HEY HEY HEY.txt'
  ));
  */
} 
catch (Aws\Exception\S3Exception $e) 
{
    $output .= "There was an error doing something.\n";
    #echo '<pre>';
    #var_dump($e);
    #echo '</pre>';
}
catch(Exception $e)
{
    echo '<h1>Error</h1>';
    Trace::vars($e->getMessage());
    echo '<h2>Exception</h2>';
    Trace::vars($e);

    echo '<a href="?reset=1">Reset Process</a>';
}

/* Instantiate an Amazon S3 client.
// http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/using-regions-availability-zones.html
    #'region' => 'us-west-1' (N.California)
    #'region' => 'us-west-2' (Oregon)
    #'region'	=> 'ap-southeast-1' (Singapore)
    #'region' => 'ap-southeast-2' (Sydney)
$s3 = new S3Client([
    'version' => 'latest',
    'region'	=> 'ap-southeast-2'
]);
*/

/* Upload a publicly accessible file. The file size and type are determined by the SDK.
try 
{
  $s3->putObject([
      'Bucket' 			=> $bucket,
      'Key'    			=> $keykey,
      #'Body'   		=> fopen($example_video_path, 'r'),
      #'SourceFile' 	=> $example_video_path,
      'Body'   			=> 'HEY HEY HEY',
      'ACL'    			=> 'public-read',
  ]);

  $output .= "putObject successful.\n";

  // Access parts of the result object
	echo $result['Expiration'] . "\n";
	echo $result['ServerSideEncryption'] . "\n";
	echo $result['ETag'] . "\n";
	echo $result['VersionId'] . "\n";
	echo $result['RequestId'] . "\n";

	// Get the URL the object can be downloaded from
	echo $result['ObjectURL'] . "\n";

} 
catch (Aws\Exception\S3Exception $e) 
{
    $output .= "There was an error uploading the file.\n";
    #echo '<pre>';
    #var_dump($e);
		#echo '</pre>';
}
*/
?>
<div class="span9">
  <div class="hero-unit">
    <h2>TEST: S3</h2>
    <p>
			<?php 
        echo '<pre>';
        #var_dump($s3);
        #var_dump($s3Client);
        #var_dump($result);
        echo '</pre>';

				echo $output;
			?>
    </p>

  </div>
</div><!--/span-->

<?php
/* -||- */
require_once './includes/footer.php';
