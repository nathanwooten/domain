<?php

namespace nathanwooten\Loader;

use Exception;

use nathanwooten\{

  Loader\LoaderInterface,
  Loader\Loader

};

use nathanwooten\{

  Autoloader

};

class Loader implements LoaderInterface
{

  protected array $container = [];

  public function autoload( $namespace, $directory, $vendor_path = null )
  {

    if ( is_null( $vendor_path ) ) {
      $vendor_path = array_pop( explode( DIRECTORY_SEPARATOR, (string) $directory ) );
    }

    $vendor_path = new FilesystemDirectory( $vendor_path );
    $directory = new FilesystemDirectotry( $directory );

    Autoloader::configure( [
      $vendor_path => [
        [
          $namespace,
          $directory
        ]
      ]
    ] );
  }

  public function call( $id, $properties = [], $callback = '__construct', $args = null )
  {

    $has = $this->has( $id, $properties );
    if ( $has ) {
      return $has;

    }

    if ( ! isset( $service ) && ( is_null( $callback ) || '__construct' === $callback ) ) {
      $service = [ $this, $id, $properties, [ $this, 'get' ], [ $id, $properties ] ];

    }
    if ( ! isset( $service ) ) {
      $service = [ $this, $id, $properties, $callback, $args ];
      $callback = new Service( ...$callback );

    }

    $this->container[ $id ] = [ $id, $properties, $callback() ];

  }

  public function set( $id, array $properties, $service )
  {

    if ( ! $this->has( $id, $properties ) ) {
      if ( $this->isA( $service, $id ) ) {
        $this->container[ $id ][] = [ $id, $properties, $service ];

      }
    }

  }

  public function has( $id, $properties = [] )
  {

    $has = true;

    if ( array_key_exists( $id, $this->container ) ) {
      foreach ( $this->container[ $id ] as $int => $service_array ) {
        if ( $properties === $service_array[0] ) {
          return $this->container[ $id ][ $int ];
        }
      }
    }

    return ! $has;

  }

  public function create( $id, $args = null )
  {

    if ( ! isset( $service ) ) {
      $rClass = new \ReflectionClass( $id );
      $args = $this->args( $id, $args );

      if ( $rClass->isInstantiable() ) {
        $service = $rClass->newInstance( ...$args );

      } else {
        if ( method_exists( $id, 'getInstance' ) ) {
          $service = $id::getInstance();

        } else {
          $service = $id;

        }
      }
    }

    return $service;

  }

  public function args( $id, $args = null )
  {

    if ( is_null( $args ) ) $args = $this->get( $id )->getArgs();

    foreach ( $args as $index => $id ) {
      if ( is_string( $id ) && class_exists( $id ) ) {
        $args[ $index ] = $this->get( $id );

      }
    }

    return $args;

  }

  public function getName( $id )
  {

    $id = str_replace( '\\', '', strtolower( $id ) );
    return $id;

  }

  public function isA( $is, $a )
  {

    if ( interface_exists( $a ) ) {
      return is_a( $is, $a );

    } elseif ( class_exists( $a ) ) {
      $interfaces = class_implements( $a );
      while ( $interfaces ) {
        $interface = array_shift( $interfaces );
        if ( is_a( $is, $interface ) ) {
          return true;
        }
      }
      return false;

    } elseif ( in_array( $a, [ 'string', 'integer', 'double', 'float', 'boolean', 'null', 'array' ] ) ) {
      return $a === gettype( $is );

    } else {
      return true;

    }
  }

}
