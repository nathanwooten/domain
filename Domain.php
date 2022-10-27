<?php

namespace Domain;

use Exception;

use Domain\{

  DomainCollection,
  DomainHelper

};

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'DomainCollection.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'DomainHelper.php';

class Domain
{

  protected static $instance;

  protected string $path;
  protected DomainCollection $domain;
  protected array $basespace = [];
  protected array $services = [];
  protected array $tags = [];

  protected function __construct()
  {

    $this->path = $path = dirname( __FILE__ );
    $this->domain = new DomainCollection( $this );

    spl_autoload_register( [ $this, 'autoload' ] );

  }

  public static function getInstance()
  {

    if ( ! isset( static::$instance ) || static::$instance instanceof static ) {
      static::$instance = new static;
    }

    return static::$instance;

  }

  public function autoload( $interface )
  {

    $collection = $this->domain;

    $interface = $spaces = explode( '\\', $interface );
    array_shift( $spaces );
    $name = array_pop( $spaces );

    $collection = $collection->seek( $spaces );

    $file = $collection . DIRECTORY_SEPARATOR . $name . '.php';

    if ( DomainHelper::isReadable( $file ) ) {
      require $file;
    }

  }

  public function setService( $interface, array $args = null )
  {

    if ( class_exists( $interface ) ) {
      $parameters = ( new ReflectionMethod( $interface, '__construct' ) )->getParameters();
      $names = array_map(
        function ( $item ) {
          return $item->getName();
        },
        $parameters
      );
      if ( ! array_diff( $names, array_keys( $args ) ) ) {
        throw new Exception;
      }

      $args = array_values( $this->parseArgs( $args ) );

      $this->services[ $interface ] = [ $interface, $args ];
    }

  }

  public function getService( $interface )
  {

    if ( $this->hasService( $interface ) ) {
      $provider = $this->services[ $interface ];
      if ( $provider instanceof StdClass ) {
      } else {
         $this->loadService( ...$provider );
      }
    }

    $provider = $this->services[ $interface ];
    $service = $provider->service;

    return $service;

  }

  public function hasService( $interface )
  {

    return array_key_exists( $interface, $this->services );

  }

  public function loadService( $interface )
  {

    $item = $this->services[ $interface ];

    if ( $item instanceof StdClass ) {
    } else {
      if ( ! is_array( $item ) ) {
        throw new Exception( 'What the heck is wrong with my neck!' );
      }
      $interface = $item[0];
      $args = $item[1];

      $service = $this->create( $interface, $args );

      $item = new StdClass;
      $item->service = $service;
      $item->args = $args;
    }

    $this->services[ $interface ] = $item;

  }

  public function createService( $interface, $args = null )
  {

    $rClass = new ReflectionClass( $interface );
    if ( $rClass->isInstantiable() ) {

      $getInstance = $args ? 'newInstanceArgs' : 'newInstance';
      if ( ! $args ) { $args = null; }
      $service = $rClass->$getInstance( $args );
    } else {
      $service = $interface;
    }

    return $service;

  }

  public function parseArgs( array $args = null )
  {

    if ( $args ) {
      foreach ( $args as $key => $value ) {
        $args[ $key ] = $this->get( $value );
      }
    }

    return [];

  }

  public function injection( $tags, $interface, $method, array $args = null )
  {

    $this->items[ implode( '/', $tags ) ] = [ [ $interface, $method ], $args ];

  }

  public function inject( $interface, $tags = [] )
  {

    $item = $this->get( $interface );

    $injections = $item->injections;
    $tagstring = implode( '/', $tags );

    foreach ( $injections as $string => $injection ) {
      if ( $tagstring === $string ) {
        if ( $injection instanceof StdClass ) {
        } else {
          $callback = $injection[0];
          $args = $injection[1];
          if ( is_string( $callback[0] ) ) {
            $callback[0] = $service;
          }
          $result = $this->call( $callback, $args );

          $injected = new StdClass;
          $injected->callback = $callback;
          $injected->args = $args;
          $injected->result = $result;
        }
        $injections[ $string ] = $injected;
      }
    }

    $item->injections = $injections;
    $this->setItem( $tags, $item );

    return $item;

  }

  public function get( $tags )
  {

    $id = implode( '/', $tags );
    if ( array_key_exists( $id, $this->items ) ) {
      $item = $this->items[ $id ];
    }

  }

  public function getPath()
  {

    return $this->path;

  }

  public function getDomain()
  {

    return $this;

  }

  public function getCollection()
  {

    return $this->domain;

  }

  public function __toString()
  {

    return $this->getPath();

  }

}