<?php

define('__ROOT__', DIRNAME(DIRNAME(__FILE__)));
#echo __FILE__.'<br>';
#echo __ROOT__.'<br>';
$directory = __ROOT__."/juice/";
#echo $directory.'<br>';
$phpfiles = glob($directory . "*.php");
#var_dump($phpfiles);

echo "-||-<br>";

foreach($phpfiles as $phpfile)
{
	if ( basename($phpfile) != 'index.php' ) {
  	echo '-||- <a href="'.basename($phpfile).'" target="_blank" style="color: #000099;">'.basename($phpfile).'</a><br>';
  }
}

echo "-||-<br>";