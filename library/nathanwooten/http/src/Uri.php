<?php

namespace nathanwooten\Http;

use nathanwooten\{

  Http\UriInterface

};

use Exception;

class Uri implements UriInterface
{

  protected string $uri;
  protected array $params = [];

  public function __construct( string $uri = null, array $params = [] )
  {

    $this->uri = ! is_null( $uri ) ? $uri : $_SERVER[ 'REQUEST_URI' ];
    $this->params = $params;

  }

  public function getUri()
  {

    if ( is_null( $this->uri ) ) {
      return '/';
    }

    return $this->uri;

  }

  public function getTarget()
  {

    if ( ! isset( $this->target ) ) {

      $this->target = '';

      $path = $this->getComponent( PHP_URL_PATH );
      $query = $this->getComponent( PHP_URL_QUERY );
      $fragment = $this->getComponent( PHP_URL_FRAGMENT );

      if ( $path ) {
        $this->target .= $path;				
      }
      if ( $query ) {
        $this->target .= '?' . $query;
      }
      if ( $fragment ) {
        $this->target .= $fragment;
      }

    }

    return $this->target;

  }

  public function getComponent( int $phpUrlConstant )
  {

    return parse_url( $this->getUri(), $phpUrlConstant );

  }

  public function withUri( string $uri ) : UriInterface
  {

    $clone = clone $this;
    $clone->uri = $uri;

    return $clone;

  }

  public function withComponent( int $phpUrlConstant, string $component ) : UriInterface
  {

    $components = [];
    $componentConstants = [ PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER, PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY, PHP_URL_FRAGMENT ];

    foreach ( $componentConstants as $int ) {
      if ( $phpUrlConstant === $int ) {
        $components[ $phpUrlConstant ] = $value;

      } elseif ( in_array( $int, $componentConstants ) ) {
        $components[ $int ] = $this->getComponent( $int );
      }
    }

    return $this->withUri( implode( '', $components ) );

  }

  public function withParams( array $params = [] ) : UriInterface
  {

    $clone = clone $this;

    foreach ( $params as $name => $value ) {
      $clone->params[ $name ] = $value;
    }

    return $clone;

  }

  public function getParams()
  {

    return $this->params;

  }

  public function getParam( $name )
  {

    return isset( $this->param[ $name ] ) ? $this->param[ $name ] : null;

  }

}
