<?php

#namespace FlicheToolkit;

include_once './includes/bootstrap.php';
    
require_once './includes/header.php';
                
define('__ROOT__', DIRNAME(DIRNAME(__FILE__)));
#echo __FILE__.'<br>';
#echo __ROOT__.'<br>';
$directory = __ROOT__."/juice/";
#echo $directory.'<br>';
$phpfiles = glob($directory . "*.php");
#var_dump($phpfiles);
$output = '';

$output .= '-||-<br>';

// loop over files in directory
foreach( $phpfiles as $phpfile ){
  if( basename($phpfile) != 'index.php' ){
    $output .= '-||- <a href="'.basename($phpfile).'" target="_blank" style="color: #000077;">'.basename($phpfile).'</a><br>';
  }
}

$output .= '-||-<br>';
?>

<div class="span9">
  <div class="hero-unit">
    <h3>TEST: Fliche</h3>
    <p>
      <?php echo $output; ?>
    </p>
  </div>
</div><!--/span-->
        
<?php

require_once './includes/footer.php';
    