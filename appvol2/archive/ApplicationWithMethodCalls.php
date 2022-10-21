<?php

namespace nathanwooten\Website;

use Exception;

use nathanwooten\{

  Website\ApplicationInterface,
  Website\ContainerInterface,
  Website\Filesystem\FilesystemDirectory

};

use nathanwooten\{

  Website\Http\RequestInterface,
  Website\Http\Request

};

class Application extends FilesystemDirectory implements ContainerInterface, ApplicationInterface
{

  protected $directory;
  protected array $container = [];

  public function __construct( $directory, RequestInterface $request )
  {

    $this->request = $request;

    parent::__construct( $directory );

  }

  public function getPath( $append = '' )
  {

    $request_path = $this->pathNormalize( $this->getRequest()->getUri()->getComponent( PHP_URL_PATH ) );

    $directory = $this->toPath( $request_path, $append );

    while ( ! is_readable( $directory ) ) {
      $dirname = $this->pathDirname( $request_path );
      $path = $this->toPath( $dirname, $append );      

      $directory = $dirname;

      if ( $dirname === $directory ) {
        throw new Exception( 'Reached root' . ' ' . $dirname . ' ' . __FILE__ . ' ' . __LINE__ );
      }
    }

    return $directory;

  }

  public function toPath( $request_path, $append = '' )
  {

    $directory = $this . DIRECTORY_SEPARATOR . $this->pathNormalize( $request_path, '', '' ) . $append;

    if ( false !== strpos( '.', basename( $directory ) ) ) {
      $fromFile = str_replace( basename( $directory ), '', $directory );
      $directory = $fromFile;
    }

    return $directory;

  }

  public function pathDirname( $path, $count = 1 )
  {

    while( $count ) {
      --$count;

      $dirname = dirname( $path );
      if ( $count && $dirname === $path ) {
        throw new Exception( 'Reached root' . ' ' . __FILE__ . ' '  . __LINE__ );
      }

      $path = $dirname;
    }

  }

  public function pathRelative()
  {

    $dir = '';

    if ( false !== strpos( $url, '/' ) ) {

      $explode = explode( '/', trim( $url, '/' ) );
      while ( $explode ) {

        array_pop( $explode );
        $dir = '../' . $dir;

      }
    }

    return $dir;

  }

  public function pathNormalize( $path, $before = '', $after = '', $separator = DIRECTORY_SEPARATOR )
  {

    $path = str_replace( [ '\\', '/' ], $separator, $path );

    if ( isset( $before ) ) {
      $path = ltrim( $path, $separator );
      if ( ! empty( $before ) ) {
        $before = $separator;
        $path = $before . $path;
      }
    }

    if ( isset( $after ) ) {
      $path = rtrim( $path, $separator );
      if ( ! empty( $after ) ) {
        $after = $separator;
        $path .= $after;
      }
    }

    return $path;

  }

  public function set( $id, $service, array $properties = [] )
  {

    if ( ! $this->isA( $service, ApplicationService::class ) ) {
      if ( $this->isA( $service, $id ) ) {
        if ( $this->hasContainer( $id ) ) {
          $service = $this->createContainer( $id );

        }
      } else {
        throw new Exception( 'Not valid service by given type, ' . $id . ' ' . __FILE__ . ' ' . __LINE__ );

      }
    }

    $this->container[ $id ] = $service;

  }

  public function get( $id, $args = null )
  {

    $args = (array) $args;

    if ( array_key_exists( $id, $this->container ) ) {
      $container = $this->container[ $id ];

    } else {
      $container = $this->createContainer( $id, $args );

    }

    if ( $container->isFactory() ) {
      $this->set( $id, $container );
    }

    if ( $container instanceof ApplicationService ) {
      $service = $container->service( $args );

    } else {
      $service = $container;

    }

    return $service;

  }

  public function has( $id )
  {

    return array_key_exists( $id, $this->container()->container );

  }

  public function container()
  {

    return $this;

  }

  public function create( $id, $args = null, $service = null )
  {

    if ( ! class_exists( $id ) ) return $service;

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

  public function createContainer( $id, $args = null )
  {

    $service = false;

    if ( ! $this->has( $id ) ) {
	  $container = $this->hasContainer( $id );
      $container = $this->create( $container, $args );
    }

    return $container;

  }

  public function hasContainer( $id ) {

	$readable = $this->getPath( 'config' . DIRECTORY_SEPARATOR . 'container' ) . DIRECTORY_SEPARATOR . $this->getName( $id ) . DIRECTORY_SEPARATOR . $this->getName( $id ) . 'service.php';
    if ( ! is_readable( $readable ) ) {
      return false;

    }

    $class = $this->getServiceClass( $id );

    return $class;

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

  public function getServiceClass( $id )
  {

    $name = $this->getName( $id );
    $class = $this->getNamespace() . '\\' . 'Services' . '\\' . $name . '\\' . $name . 'service';

    if ( ! class_exists( $class ) ) {
      throw new Exception( 'Service container does not exists, ' . $class . ' ' . __FILE__ . ' ' . __LINE__ );
    }


    return $class;

  }

  public function getNamespace()
  {

    return static::namespace;

  }

  protected function isA( $is, $a )
  {

    if ( is_object( $a ) ) {
      if ( is_a( $is, $a ) ) {
        return true;
      }

      return false;
    }

    return true;

  }

  public function getRequest( RequestInterface $request = null )
  {

    if ( ! isset( $request ) ) {
      if ( ! isset( $this->request ) ) {
        $this->request = $this->get( Request::class );
      }
      $request = $this->request;
    }
    return $this->request = $request;

  }

  public function getResponse( ResponseInterface $response = null )
  {

    if ( ! isset( $response ) ) {
      if ( ! isset( $this->response ) ) {
        $this->response = $this->get( Response::class );
      }
      $response = $this->response;
    }
    return $this->response = $response;

  }

  public function run( RouteInterface $route )
  {

    $response = $this->cache();
    if ( $response ) {
      return $response;
    }

    $path = $this->getPath();
    $route = $this->route( $path );

    foreach ( $route as $call ) {
      if ( $call instanceof ResponseInterface ) {
        $response = $call;
      }
    }

    return $response;

  }

  public function cache()
  {

    $cache = $this->hasCache();
    if ( $cache && isset( $cache[ 'body' ] ) ) {
      return $this->handleResonse( null, $cache );
    }

  }

  public function hasCache()
  {

    $cache = [];

    $file = $this->getPath( 'templates' ) . DS . 'index.html';
    if ( is_readable( $file ) ) {
      $cache = [ 'body' => file_get_contents( $file ) ];
    }

    $headers = $this->getPath( 'http' ) . DS . 'headers.php';
    if ( is_readable( $headers ) ) {
      $cache = [ 'headers' => $headers ];
    }

    return $cache;

  }

  public function route( $path )
  {

    $route = $this->getFile( $this->getPath( $path ), 'route.php' );
    $route = include $route;

    $route = new Route( $this, $route );

    foreach ( $route->params as $alias => $call ) {
      $route[ $alias ] = $this->service( ...$call );
    }

  }

  public function service( $id, $methodName, $args = null, $properties = [] )
  {

    $serviceProperties = [];
    $callbackProperties = [];

    if ( 1 ) {
      $serviceProperties = $callbackProperties = $properies;
    }

    $service = $this->get( $id, $serviceProperties );
    if ( method_exists( $service, $methodName ) ) {

      $callback = new Callback;

      $callback->setCallback( [ $service, $methodName ] );
      $callback->setArgs( $args );
      $callback->setProperties( $properties );

      $this->invoke( $callback );
    }

  }

  public function invoke( CallbackInterface $callback, $args = null )
  {

    $result = $callback( $args );
    $this->result[] = $callback;

    return $result;

  }

  public function handleResponse( ResponseInterface $response = null, $properties = [] )
  {

    return $this->response = $this->getResponse( $response )->handleProperties( $properties );

  }












  public function methods( TemplateInterface $template, $methods )
  {

    $method_returns = [];

    foreach ( $methods as $methodName => $args ) {
      $template = $this->method( TemplateInterface $template, $methodName, $args );
    }

    return $template;

  }

  public function method( TemplateInterface $template, $methodName, $args )
  {

    $template->$methodName( ...array_values( (array) $args ) );
    return $template;

  }




  protected function load( $vendor_path, $namespace, $directory )
  {

    return Autoloader::configure( [ $vendor_path => [ [ $namespace, $directory ] ] ] );

  }

  protected function setRequest( RequestInterface $request )
  {

    return $this->getRequest( $request );

  }

  protected function setResponse( ResponseInterface $response )
  {

    return $this->getResponse( $response );

  }

}
