<?php
/**
 * http.fliche.php
 *
 * use wp_remote_post to access an outside PHP file within this plugin
 * ________________
 */

#$url = 'http://fliche.io/fliche/juice/extract-metadata-media.juice.php';
$url = 'http://fliche.io/fliche/juice/index.php';

try
{

	$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array(), //array( 'username' => 'bob', 'password' => '1234xyz' ),
			'cookies' => array()
    )
	);

	if ( is_wp_error( $response ) ) {
	   $error_message = $response->get_error_message();
	   echo "Something went wrong: $error_message";
	} else {
	   echo 'Response:<pre>';
	   print_r( $response );
	   echo '</pre>';
	}

}
catch(Exception $e)
{
  echo '<h1>Error</h1>';
  Trace::vars($e->getMessage());
  echo '<h2>Exception</h2>';
  Trace::vars($e);
}

/**________________
 * END FILE
 */