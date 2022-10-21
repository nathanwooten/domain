<?php

function getTarget( string $target = null, UriInterface $uri = null, string $url = null ) {

  $includes_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
  if ( ! isset( $target ) ) {
    if ( ! isset( $uri ) ) {
      $uri = getUri( $uri, $url );
    }
    $target = $includes_dir . 'target.inc.php';
  }

  return $target;

}

function getUri( UriInterface $uri = null, string $url = null ) {

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
