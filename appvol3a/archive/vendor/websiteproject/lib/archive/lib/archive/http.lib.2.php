<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'UriInterface.php';
require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Uri.php';

function getUriDirectory( string $directory = null, string $target = null, UriInterface $uri = null, string $url = null )
{

  if ( ! isset( $directory ) ) {
    if ( ! isset( $target ) ) {
      $target = getTarget( $target, $uri, $url );
    }
 
    $directory = include getUriInclude( 'directory' );





  }

  return $directory;
}

function getUriTarget( string $target = null, UriInterface $uri = null, string $url = null )
{

  $includes_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
  if ( ! isset( $target ) ) {
    if ( ! isset( $uri ) ) {
      $uri = getUri( $uri, $url );
    }
    $target = getUriInclude( 'target' );
  }

  return $target;

}

function getUriUri( UriInterface $uri = null, string $url = null )
{

  $includes_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
  if ( ! isset( $uri ) ) {
    if ( ! isset( $url ) ) {
      $url = getUrl();
    }
    $uri = include getUriInclude( 'uri' );
  }

  return $uri;

}

function getUriRequest( string $string = null )
{

  if ( ! isset( $string ) ) {
    if ( isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
      $string = $_SERVER[ 'REQUEST_URI' ];
    } else {
      $string = '/';
  }

  return $string;

}

function getUriInclude( $property )
{

  $includes_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
  $property = strtolower( $property );

  $include = $includes_dir . $property . 'inc.php';

  return $include;

}
