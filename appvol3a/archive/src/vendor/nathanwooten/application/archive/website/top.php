<?php

////////// /local/websiteproject/top.inc

use nathanwooten\{

  Autoloader,
  Container\ContainerAbstract as Container,
  Standard\Standard

};

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . DIRECTORY_SEPARATOR );

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
  'nww' => LIB_PATH . 'nathanwooten' . DS . 'website' . DS
];
$files = [
  'autoloader' => $paths[ 'al' ] . 'Autoloader.php',
  'autoloads' => $paths[ 'nww' ] . 'autoloads.php'
];
require_once $files[ 'autoloader' ];
Autoloader::normalize( false );

$autolaods = Autoloader::autoload( require $files[ 'autoloads' ] );

if ( ! isset( $standard ) ) {
  $standard = Standard::class;
}

$standard = new Standard;

return $standard->getContainer();
