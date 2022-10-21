<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'UriInterface.php';
require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'nathanwooten' . DIRECTORY_SEPARATOR . 'http' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Uri.php';

function getUriProperty( $property, $value = null, $parent_value = null )
{

  if ( is_null( $value ) ) {
    $default = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'uri' . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . strtolower( $property ) . '.default.php';
var_dump( $default );
    if ( file_exists( $default ) && is_readable( $default ) ) {
		$value = include $default;

    } else {

        $chain = getRequestChain();
        $chain = array_slice( $chain, 1 + array_search( $property, $chain ) );

        if ( empty( $chain ) ) {
          return null;
        }

        $parent = array_shift( $chain );
        $value = getUriProperty( $parent, $parent_value );
    }
  }

  return $value;

}

function getRequestChain()
{

  return [ 'Directory', 'Target', 'Uri', 'Request' ];

}
