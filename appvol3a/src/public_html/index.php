<?php

use nathanwooten\{

  Application,

  Http\Request,
  Http\Uri

};

$one = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';
if ( 1 !== $one ) {
  die( "Who's fault is it Johnson? Who's Johnson?" );
}

Application::autoload();

$application = new Application( new Request( new Uri( 'Application' ) ) );
var_dump( $application );
