<?php

use nathanwooten\{

  Website\Application

};

$request = null;
$uri = null;

if ( ! isset( $request_uri ) ) {
  $request_uri = $_SERVER[ 'REQUEST_URI' ];
}

//
try {
  $application = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';
  if ( ! is_object( $application ) || ! is_a( $application, Application::class ) ) {
    die( __FILE__ . ' ' . __LINE__ );
  }
} catch ( Exception $e ) {
  die( DEBUG ? $e->getMessage() : 'Please contact the administrator' );
}

$config_path = $application->getPath( 'config' );

