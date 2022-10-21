<?php

namespace nathanwooten\Loader;

use Exception;

use nathanwooten\{

  Loader\Loader,
  Application\ApplicationInterface

};

use nathanwooten\{

  Autoloader

};

class Loader
{

  protected array $container = [];

  public function __construct( ApplicationInterface $application )
  {

    $this->path = dirname( __FILE__ );

    $services_path = $this->path . DIRECTORY_SEPARATOR . 'Services';
    $scan = scandir( $services_path );
    foreach ( $scan as $item ) {
      $item_path = $services_path . DIRECTORY_SEPARATOR . $item;

      if ( is_file( $item_path ) ) {
        $container = __NAMESPACE__ . '\\' . 'Services' . '\\' . rtrim( $item, '.php' );
        $container = new $container( $this );
        $id = $container->getId();

        $this->container[ $id ] = $container;

      }
    }

  }

  public function prepare( $object )
  {

    foreach ( $object->services as $id ) {
      $object->set( $id, $this->has( $id ) );

    }

    return $object;

  }

  public function has( $id )
  {

    $has = true;

    if ( array_key_exists( $id, $this->container ) ) {
      $container = $this->container[ $id ];

    }

    return ! $has;

  }

  public function create( $id, $args = null )
  {

    return new $id( ...array_values( (array) $args ) );

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

  public static function name( $id )
  {

    $id = str_replace( '\\', '', strtolower( $id ) );
    return $id;

  }

  public static function isA( $is, $a )
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

  public static function noBasename( $path )
  {

    $path = str_replace( basename( $path ), '', $path );
    return $path;

  }

}
