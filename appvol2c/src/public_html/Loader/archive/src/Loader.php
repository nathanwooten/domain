<?php

namespace nathanwooten\Loader;

use Exception;

use nathanwooten\{

  Loader\Loader

};

use nathanwooten\{

  Autoloader

};

class Loader
{

  protected array $container = [];

  public function __construct()
  {

    $this->set( static::class, [], $this );

    $dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Services';

    foreach ( scandir( $dir ) as $item ) {
      $file = $dir . DIRECTORY_SEPARATOR . $item;
    }


  }

  public function load( $id, $args = null, array $tags = [] )
  {

    $has = $this->has( $id, $tags );
    if ( $has ) {
      return $has;

    }

    $container = $id . 'Service';
    $container = new $container( $id, $args, $tags );

    $this->container[ $container->getId() ] = $container;
    return $container->getService();

  }

  public function set( $id, array $properties, $service )
  {

    if ( ! $this->has( $id, $properties ) ) {
      if ( $this->isA( $service, $id ) ) {
        $this->container[ $id ][] = new LoaderService( $this, $service );

      }
    }

  }

  public function has( $id, $properties = [] )
  {

    $has = true;

    if ( array_key_exists( $id, $this->container ) ) {
      foreach ( $this->container[ $id ] as $int => $service_array ) {
        if ( $properties === $service_array[0] ) {
          $callback = $this->container[ $id ][ $int ];
          $service = $callback();

          return $service;

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
