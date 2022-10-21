<?php

////////// /local/websiteproject/top.inc

use nathanwooten\{

  Autoloader,
  Container\ContainerAbstract as Container,
  Standard\Standard

};

if ( ! defined( 'PROJECT_NAME' ) ) die( 'Please define "PROJECT_NAME" in your top.inc.php file: /path/to/project/package/top.inc.php' );

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );

ini_set( 'display_errors', DEBUG );

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

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

$autoloader = LIB_PATH . 'nathanwooten' . DS . 'autoloader' . DS . 'src' . DS . 'Autoloader.php';
require_once $autoloader;

$autoloads = require LIB_PATH . 'nathanwooten' . DS . 'website' . DS . 'autoloads.php';

$autoloads = Autoloader::autoload( $autoloads );

new Standard;

$container = Container::getContainerClass();
$container = new $container;

return $container;
