<?php

namespace nathanwooten;

use Exception;
use ArrayIterator;

class ApplicationAbstract extends ArrayIterator
{

  protected static $instance = [];
  protected static $namespace = __NAMESPACE__;

  protected $application;
  protected array $container = [];

  public array $response = [];

  public $property = 'response';
  public $pointer = -1;

  public function __construct()
  {

    $this->application = dirname( __FILE__ );

    spl_autoload_register( [ static::class, 'autoload' ], true, true );

  }

  public function __toString()
  {

    return $this->getPath();

  }

  public function getPath()
  {

    return $this->application;

  }

  public function autoload( $interface = null )
  {

    if ( ! is_null( $interface ) ) {
      $basespace = static::$namespace;
      $file = str_replace( $basespace, dirname( __FILE__ ), $interface ) . '.php';

      if ( $this->exists( $file ) ) {
        return require $file;
      }
      throw new Exception( 'Interface does not exists in this application\'s library ' . $interface );
    }

  }

  public function get( $id, array $tags = null )
  {

    $service = null;

    if ( ! $this->has( $id ) ) {
      if ( ! class_exists( $id ) ) {
        static::autoload( $id );
      }
      $container = $this->contain( $id );
    }

    return $service;

  }

  public function has( $id )
  {

    return $this->offsetExists( $id );

  }

  public function exists( $id )
  {

    $path = $this->getPath();

    if ( 0 !== strpos( $id, $path ) ) {
      $file = $path . $id;
    } else {
      $file = $id;
    }

    return file_exists( $file ) && is_readable( $file );

  }

  public function contain( $id )
  {

    if ( ! $this->has( $id ) ) {
      $container = $id . static::$containerType;
      $this->container[ $id ] = new $container( $this );
    }

    return $this->container[ $id ];

  }

  public static function getApplication( $path )
  {

    while( ! array_key_exists( $path, static::$instance ) ) {
      $parent = dirname( $path );

      if ( $parent === $path ) {
        throw new Exception( 'No application set in the application instance property' );

      }
      $path = $parent;
    }

    return static::$instance[ $path ];

  }

}
