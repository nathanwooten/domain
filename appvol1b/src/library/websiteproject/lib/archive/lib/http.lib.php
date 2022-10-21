<?php

use nathanwooten\{

  Http\UriInterface,
  Http\Uri

};

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'UriInterface.php';
require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Uri.php';

function getUriProperty( $property, $value = null, $parent_value = null, $orginal_property = null )
{

  if ( ! isset( $original_property ) ) {
    $original_property = $property;
  }

  if ( is_null( $value ) ) {
    $default = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . strtolower( $property ) . '.default.php';

    if ( file_exists( $default ) && is_readable( $default ) ) {
		$value = include $default;

    } else {

      $request_chain = getRequestChain();
      $chain = array_slice( $request_chain, 1 + array_search( $property, $request_chain ) );

      while ( $chain && is_null( $value ) ) {
        $parent = array_shift( $chain );

        $value = getUriProperty( $parent, $parent_value, null, $original_property );
      }


      $value = advanceUriProperty( $parent, parseUriProperty( $parent, $value ) );

    }
  }

  return $value;

}

function advanceUriProperty( $property, $value )
{

  $fn = 'advanceUri' . $property;
  $value = $fn( $value );
  return $value;

}

function advanceUriTarget( $target )
{

  $directory = PROJECT_PATH . 'public_html' . $target;
  return $directory;

}

function advanceUriUri( UriInterface $uri )
{

  $target = $uri->getTarget();
  return $target;

}

function advanceUriRequest( string $request )
{

  $uri = new Uri( $request );
  return $uri;

}

function parseUriProperty( $property, $value )
{

  $fn = 'parseUri' . $property;
  if ( function_exists( $fn ) ) {
	$value = $fn( $value );
  }

  return $value;

}

function parseUriDirectory( string $directory )
{

  if ( ! is_readable( $directory ) ) {
    return false;
  }

  return $directory;

}

function parseUriTarget( string $target )
{

  $target = str_replace( '/', DIRECTORY_SEPARATOR, $target );

  if ( is_file( PROJECT_PATH . 'public_html' . $target ) ) {
    $target = str_replace( ltrim( basename( $target ), DIRECTORY_SEPARATOR ), '', $target );
  }

  return $target;

}

function getRequestChain()
{

  return [ 'Directory', 'Target', 'Uri', 'Request' ];

}
