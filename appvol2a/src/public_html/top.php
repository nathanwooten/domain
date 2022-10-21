<?php

use nathanwooten\{

  Autoloader,

  Website\Application,

  Website\Http\Request,
  Website\Http\Uri

};

if ( ! defined( 'PROJECT_PATH' ) ) require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

if ( ! defined( 'PROJECT_NAME' ) ) define( 'PROJECT_NAME', 'websiteproject' );

if ( ! defined( 'DEBUG' ) ) {
  define( 'DEBUG', 1 );
  ini_set( 'display_errors', DEBUG );
}

$autoloader = PROJECT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Autoloader.php';
require_once $autoloader;

Autoloader::configure( [
  PROJECT_PATH . 'public_html' => [
    [
      'nathanwooten\Website',
      'application' . DIRECTORY_SEPARATOR . 'src'
    ]
  ]
] );

if ( ! isset( $request ) ) {
  if ( ! isset( $uri ) ) {
    if ( ! isset( $request_uri ) ) {
      $request_uri = $_SERVER[ 'REQUEST_URI' ];
    }
    $uri = new Uri( $request_uri );
  }
  $request = new Request( $uri );
}

return $application = new Application( PROJECT_PATH . 'public_html' . DIRECTORY_SEPARATOR . 'application', $request );
