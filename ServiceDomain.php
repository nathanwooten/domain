<?php

namespace nathanwooten\Domain;

use ReflectionClass;
use Exception;

use nathanwooten\{

  Domain\Domain

};

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Domain.php';

class ServiceDomain implements Domain
{

  protected static $instance = [];

  protected $path;
  protected $services = [];
  protected $spaces = [];
  protected $interfaces = [];
  protected $response = [];

  protected function __construct( $path )
  {

    $this->path = $path;

    spl_autoload_register( [ $this, 'fetch' ], true, true );

  }

  public static function getInstance( $path )
  {

    $path = (string) $path;

    $path = str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $path );
    $path = rtrim( $path, DIRECTORY_SEPARATOR );

    if ( ! isset( static::$instance[ $path ] ) ) {
      static::$instance[ $path ] = new static( $path );
    }

    return static::$instance[ $path ];

  }

  public function add( $namespace )
  {

    $this->spaces[] = $namespace;

  }

  public function fetch( $service )
  {

    foreach ( $this->spaces as $basespace ) {
       $file = str_replace( $basespace, $this->getPath(), $service );
       $with = $file . '.php';
       if ( file_exists( $with ) && is_readable( $with ) ) {

         $result = require $with;
         return $result;
       }
    }

  }

  public function load( $id, $service = null, $args = [], array $properties = [] )
  {

    if ( isset( $this->services[ $id ] ) ) {
      return $this->services[ $id ]->getService();
    }

    $top = trim( str_replace( basename( PUBLIC_HTML ), '', PUBLIC_HTML ), DIRECTORY_SEPARATOR );
    $up = $this;

    while ( $top != (string) $up ) {
     if ( isset( $up->services[ $id ] ) ) {
        return $up->services[ $id ]->getService();
      }
      $up = $up->up( 1 );
    }

    if ( ! isset( $service ) ) {
      throw new Exception( 'Service must be provided if not already set' );
    }

    $defArgs = [ $this, $id, $service, $args, $properties ];
    try {
      $definition = $this->factory( $service . 'Definition', $defArgs );
    } catch ( Exception $e ) {
      $definition = new ServiceDefinition( ...$defArgs );
    }

    if ( $definition->isShared() ) {
      $this->services[ $id ] = $definition;
    }

    $this->response[] = [ $id, null, $definition ];

    return $definition->getService();

  }

  public function has( $interface )
  {

    if ( ! in_array( $interface, $this->interfaces ) ) {

      $services = array_map(
        function ( $item ) use ( $services ) {
          if ( $interface === $item->getInterface() ) {
            return $item;
          }
          return null;
        },
        $services
      );

      $services = array_filter( $services );
      if ( $services ) {

        $key = key( $services );
        $this->interfaces[ $key ] = $interface;

      } else {
        return false;
      }
    }

    return true;

  }

  public function inject( $id, $method = null, $args = null )
  {

    $definition = $this->load( $interface );

    $callback = [ $definition->getService(), $method ];
    $result = $callback( ...array_values( (array) $args ) );

    return $this->response[] = [ $id, [ $callback, $args ], $result ];

  }

  public function get( $id )
  {

    $responses = $this->response;
    $responses = array_filter( array_map(
      function( $item ) {
        if ( $id === $item[0] ) {
          return $item;
        }
        return null;
      },
      $responses
    ) );

    return array_shift( $responses );

  }

  public function up( $count )
  {

    $path = $this->getPath();
    while( $count ) {
      --$count;
      $path = dirname( $path );
      $domain = ServiceDomain::getInstance( $path );
    }

	return $domain;

  }

  public function down( $path )
  {

    $domain = $this;

    $path = trim( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $path ), DIRECTORY_SEPARATOR );

    foreach ( explode( DIRECTORY_SEPARATOR, $path ) as $dir ) {
      if ( is_dir( $domain . DIRECTORY_SEPARATOR . $dir ) ) {
        if ( ! isset( $domain->domain[ $dir ] ) ) {
          $domain->domain[ $dir ] = static::getInstance( $domain . DIRECTORY_SEPARATOR . $dir );
          foreach ( $domain->getSpaces() as $space ) {
             $domain->domain[ $dir ]->add( $space . '\\' . $dir );
          }
        }
        $domain = $domain->domain[ $dir ];
      }
    }

    return $domain;

  }

  public function getResponses()
  {

    return $this->response;

  }

  public function getServices()
  {

    return $this->services;

  }

  public function getSpaces()
  {

    return $this->spaces;

  }

  public function getPath()
  {

    return $this->path;

  }

  public function __toString()
  {

    return (string) $this->path;

  }

  public function factory( $interface, $args = [] )
  {

    if ( ! class_exists( $interface ) ) {
      throw new Exception( 'Class does not exists in factory method ' . $interface );
    }

    $args = array_values( $args );

    $rClass = new ReflectionClass( $interface );
    if ( $rClass->isInstantiable() ) {
      if ( $args ) {
        $instance = $rClass->newInstanceArgs( $args );
      } else {
        $instance = $rClass->newInstance();
      }
    } else {
      $instance = $interface;
    }

    return $instance;

  }

}