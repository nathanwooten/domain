<?php

use nathanwooten\{

  Uri\UriInterface,
  Uri\Uri,

  Container\Container,

  Registry\Registry

};

if ( ! defined( 'PROJECT_PATH' ) ) define( __FILE__ );

function getTarget( $uri = null )
{

  $container = Registry::get( Container::class );

  if ( ! isset( $uri ) ) {
    $uri = $container->get( Uri::class );
  }

  if ( is_string( $uri ) ) {
    $uri = $container->get( Uri::class, [ $uri ] );
  }

  $target = $uri->getTarget();

  if ( ! is_dir( PROJECT_PATH . $target ) ) {
    $basename = basename( $target );

    $target = str_replace( $basename, '', $target );
  }
  $target = trim( $target, '/' );

  return $target;

}

function urlRelative( $url )
{

  $dir = '';

  $subfolder = trim( $url, '/' );

  if ( false !== strpos( $subfolder, '/' ) ) {
    $explode = explode( '/', $url );
    if ( ! empty( $explode ) ) {
      foreach ( $explode as $exp ) {
        $dir = '../' . $dir;
      }
    }
  }

  return $dir;

}
