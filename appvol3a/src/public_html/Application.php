<?php

namespace nathanwooten;

use Exception;

use nathanwooten\{

  Model\Model,
  View\ViewManager,

  Http\RequestInterface,
  Http\Request,
  Http\ResponseInterface,
  Http\Response,
  Http\Uri,

  Loader\Loader

};

class Application
{

  protected static $instance = [];
  protected static $namespace = 'nathanwooten';
  protected static $autoloader_registered = false;

  protected $application;
  protected RequestInterface $request;
  protected ResponseInterface $response;
  protected array $container = [];

  public function __construct( RequestInterface $request = null, $application = null )
  {

    $this->application = $application ?? dirname( __FILE__ );
    static::$instance[ $this->application ] = $this;

    if ( ! is_null( $request ) ) {
      $this->response = $this->makeRequest( $request );
    }

  }

  public static function autoload( $interface = null )
  {

    if ( ! static::$autoloader_registered ) {
      spl_autoload_register( [ static::class, 'autoload' ], true, true );
      static::$autoloader_registered = true;
    }

    if ( ! is_null( $interface ) ) {
      $basespace = static::$namespace;
      $file = str_replace( $basespace, dirname( __FILE__ ), $interface ) . '.php';

      if ( file_exists( $file ) && is_readable( $file ) ) {
        return require_once $file;
      }
      throw new Exception( 'Interface does not exists in this application\'s library ' . $interface );
    }

  }

  public function getPath( $application = null )
  {

    $path = $application ?? $this->path;
    if ( $path instanceof ApplicationInterface ) {
      $path = $path->getPath();

    }

    return $path;

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

  public function makeRequest( RequestInterface $request = null, UriInterface $uri = null, $path = null, $querystring = null, array $params = null )
  {

    $request = $request ?? new Request( $uri ?? new Uri( $path, $querystring, $params ) );

    $path = $this->getRequestPath( $request );
    if ( array_key_exists( $path, $this->container ) ) {
      $container = $this->container[ $path ];
      $id = $container->getId();

    } else {
      $id = static::$namespace . '\\' . $path;

      if ( ! class_exists( $id ) ) {
        $file = $this->getPath() . $path . '.php';

        if ( ! file_exists( $file ) ) {
          throw new Exception( 'File does not exist ' . $file . ' ' . __CLASS__ . ' ' . __FUNCTION__ );
        }
        $file = require_once $file;
      }
     
      if ( ! class_exists( $id ) ) {
        throw new Exception( 'Class does not exists after autoloading ' . $id );
      }

      $this->container[ $path ] = $container = static::create( $id );
   }

    $has = $this->byTag( $container, $this->getRequestParams( $request ) );
    if ( $has ) {


    }

    $index = count( $this->request );
    $this->request[ $index ] = $request;
    $this->response[ $index ] = $response;

    return $response;

  }

  public function container( $id, $tags = [] )
  {

    if ( array_key_exists( $id, $this->container ) ) {
      $container = $this->container[ $id ];
      return $this->byTag( $container, $tags );
    }

  }

  public function byTag( $container, $tags )
  {

    $highestPropertyIntersectCount = false;
    $has = 0;

    foreach ( $container as $key => $method ) {
      $intersect = array_intersect( $tags, $method->getTags() );
      if ( $intersect && count( $intersect ) > $has ) {
        $has = count( $intersect );
        $highestPropertyIntersectCount = $service;

      }
    }

    return $highestPropertyIntersectCount;

  }

  public function getRequestPath( RequestInterface $request )
  {

    return $request->getUri()->getPath();

  }

  public function getRequestQuery( RequestInterface $request )
  {

    return $request->getUri()->getQuery();

  }

  public static function create( $id, $args = [], $injection = [] )
  {

    $serviceContainer = $this->container( $id );
    if ( $serviceContainer->isInstantiable() ) {
      $service = new $id( ...array_values( (array) $args ) );

    }

    foreach ( $injection as $callbackArray ) {

      $callback = $callbackArray[0];
      $args = $callbackArray[1];

      if ( is_string( $callback ) ) {
         $callback = [ $service, $callback ];

      } elseif ( is_array( $callback ) && ! empty( $callback ) && 2 === count( $callback ) ) {
        $callback = array_values( $callback );
        if ( is_string( $callback[0] ) ) {
          $callback[0] = $service;
 
        }

      } else {
        throw new Exception;

      }

      $callback( ...array_values( $args ) );

    }

    return $service;

  }

}
