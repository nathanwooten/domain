<?php

////////// /local/websiteproject/top.inc

use nathanwooten\{

  Autoloader

};

use nathanwooten\{

  Application\Application

};

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ . ' ' . __LINE__ );

if ( ! isset( $container ) ) {
  if ( ! defined( 'PROJECT_NAME' ) ) {
    die( __FILE__ . ' ' . __LINE__ );
  }
  $container = implode( '\\', [ PROJECT_NAME, 'Container', 'Container' ] );
}

if ( ! defined( 'DEBUG' ) ) {
  define( 'DEBUG', 1 );
  ini_set( 'display_errors', DEBUG );
}

if ( ! function_exists( 'handleError' ) ) {
function handleError( Exception $e ) {

	$fileAndLine = ( isset( $e->errfile ) ? ', ' . $e->errfile . '::' . $e->errline : '' );
	$message = $e->getMessage() . $fileAndLine;

	error_log( $message );

	if ( ! defined( 'DEBUG' ) || DEBUG ) {
		die( $message );
	}
	die;
}
}

$paths = [
  'al' => LIB_PATH . 'nathanwooten' . DS . 'autoloader' . DS . 'src' . DS,
  'als' => dirname( __FILE__ ) . DS
];
$files = [
  'autoloader' => $paths[ 'al' ] . 'Autoloader.php',
  'autoloads' => $paths[ 'als' ] . 'autoloads.php'
];

require_once $files[ 'autoloader' ];

Autoloader::normalize( false );

$autoloads = require $files[ 'autoloads' ];
$configured = Autoloader::configure( $autoloads );

//
return $application = new Application( PROJECT_PATH . 'public_html' );
