<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'UriInterface.php';
require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Uri.php';

function getUriProperty( string $property, $value = null, ...$parents )
{

  if ( ! isset( $



function getDirectory( string $directory = null, string $target = null, UriInterface $uri = null, string $url = null )
{

  if ( ! isset( $directory ) ) {
    if ( ! isset( $target ) ) {
      $target = getTarget( $target, $uri, $url );
    }
    $directory = PROJECT_PATH . 'public_html' . str_replace( '/', DIRECTORY_SEPARATOR, $target );
  }

  return $directory;
}

function getTarget( string $target = null, UriInterface $uri = null, string $url = null )
{

  $includes_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
  if ( ! isset( $target ) ) {
    if ( ! isset( $uri ) ) {
      $uri = getUri( $uri, $url );
    }
    $target = include $includes_dir . 'target.inc.php';
  }

  return $target;

}

function getUri( UriInterface $uri = null, string $url = null )
{

  $includes_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
  if ( ! isset( $uri ) ) {
    if ( ! isset( $url ) ) {
      $url = getUrl();
    }
    $uri = $includes_dir . 'uri.inc.php';
  }

  return $uri;

}

function getUrl( string $url = null )
{

  if ( isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
    return $_SERVER[ 'REQUEST_URI' ];
  }

  return '/';

}
