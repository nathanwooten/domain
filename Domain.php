<?php

namespace Domain;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use StdClass;

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

  protected array $spaces = [];
  protected array $services = [];

  protected function __construct( $collection = DomainCollection::class )
  {

    $this->path = $path = dirname( __FILE__ );
    $this->domain = new $collection( $this );

    spl_autoload_register( [ $this, 'autoload' ] );

  }

  public static function getInstance()
  {

    if ( ! isset( static::$instance ) || static::$instance instanceof static ) {
      static::$instance = new static;
    }

    return static::$instance;

  }

  public function add( $basespace, $directory = null )
  {

    if ( is_null( $directory ) ) $directory = $this->getPath();

    $this->spaces[] = [ $basespace, $directory ];

  }

  public function autoload( $interface )
  {

    $collection = $this->domain;
    $space = $this->match( $interface );

    if ( ! $space ) {
      throw new Exception;
    }

    $spaces = str_replace( $space[0] . '\\', '', $interface );
    $spaces = explode( '\\', $spaces );
    $name = array_pop( $spaces );

    $collection = $collection->seek( $spaces );

    $file = $collection . DIRECTORY_SEPARATOR . $name . '.php';

    if ( DomainHelper::isReadable( $file ) ) {
      require_once $file;
    }

  }

  public function match( $interface )
  {

    $spaces = $this->spaces;

    while ( $spaces ) {
      $space = array_shift( $spaces );
      $basespace = $space[0];
      if ( 0 === strpos( $interface, $basespace ) ) {
        return $space;
      }
    }

  }

  public function set( $interface, array $args = null )
  {

    if ( class_exists( $interface ) ) {
      $this->services[ $interface ] = [ $interface, array_values( $this->parseArgs( $args ) ) ];
    }

  }

  public function get( $interface, $id = null )
  {

    if ( $this->has( $interface ) ) {
      $provider = $this->services[ $interface ];
      if ( $provider instanceof stdClass ) {
      } else {
         $this->load( ...$provider );
      }
    }

    if ( ! is_null( $id ) ) {
      return $this->inject( $id, $interface );
    }

    return $this->services[ $interface ]->service;

  }

  public function has( $interface )
  {

    return array_key_exists( $interface, $this->services );

  }

  public function load( $interface )
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

  public function create( $interface, $args = null )
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
        if ( is_numeric( $key ) ) {
          $args[ $key ] = $this->get( $value );
        }
      }
    }

    return $args;

  }

  public function injection( $id, $interface, $method, array $args = null )
  {

    $this->services[ $interface ]->injection[ $id ] = [ [ $interface, $method ], $args ];

  }

  public function inject( $id, $interface )
  {

    $item = $this->services[ $interface ];

    $injections = $item->injection;

    foreach ( $injections as $string => $injection ) {
      if ( $id === $string ) {
        if ( $injection instanceof StdClass ) {
        } else {
          $callback = $injection[0];
          $args = $injection[1];
          if ( is_string( $callback[0] ) ) {
            $callback[0] = $item->service;
          }
          $result = $this->call( $callback, $args );

          $injected = new StdClass;
          $injected->callback = $callback;
          $injected->args = $args;
          $injected->result = $result;
        }
        $injections[ $string ] = $injected;
        break;
      }
    }

    $item->injection = $injections;
    $this->services[ $interface ] = $item;

    return $result;

  }

  public function call( callable $callback, $args )
  {

    return $callback( ...array_values( (array) $args ) );

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