<?php

use nathanwooten\{

  Loader\Loader

};

$one = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';
if ( 1 !== $one ) {
  die( "Who's fault is it Johnson? Who's Johnson?" );
}

$loader = new Loader;

$template = $loader->has( BaseTemplate::class );

var_dump( $template );
