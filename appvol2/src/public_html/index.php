<?php

use nathanwooten\{

  Application\Application,
  Http\Request

};

$one = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';
if ( 1 !== $one ) {
  die( "Who's fault is it Johnson? Who's Johnson?" );
}

$application = new Application( new Request );
var_dump( $application );
