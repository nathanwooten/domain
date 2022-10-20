<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Application\ApplicationInterface,
  Application\Filesystem\FilesystemDirectory,

  Container\ContainerInterface

};

class Application extends FilesystemDirectory implements ApplicationInterface
{

  use ApplicationServicesProviderTrait;

  public $directory;

  protected ContainerInterface $container;

  protected RequestInterface $request;
  protected ResponseInterface $response;

  public function __construct( $directory )
  {

    parent::__construct( $directory );

  }

  public function getPath( $append = '' )
  {

    $target = $this->getRequest()->getUrl()->getTarget();
    $directory = $this->getRoot() . $target;

    $directory .= $append;

    if ( is_file( $directory ) ) {
      $directory = str_replace( basename( $directory ), '', $directory );
    }

    return $directory;

  }

  protected function create( $id )
  {

    $service = false;

    $path = $this->getPath( 'container' );
    $name = $this->getName( $id );

	$readable = $path . DS . $name . DS . $name . 'service.php';

    if ( ! is_readable( $readable ) ) {
      throw new Exception( 'Unreadable: ' . (string) $readable );
    }
    $class = $this->getServiceClass( $id );

    $container = new $class( $this );
    return $container;

  }

  public function getName( $id )
  {

    $id = str_replace( '\\', '', strtolower( $id ) );
    return $id;

  }

  public function getServiceClass( $id )
  {

    $name = static::getName( $id );
    $class = static::getNamespace() . '\\' . 'Services' . '\\' . $name . '\\' . $name . 'service';

    return $class;

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

    if ( ! isset( $request ) ) $request = $this->request;

    return $this->request = $request;

  }

  public function getResponse( ResponseInterface $response = null )
  {

    if ( ! isset( $response ) ) $response = $this->response;

    return $this->response = $response;

  }

  public function run()
  {

    $response = $this->cache();
    if ( $response ) {
      return $response;
    }

    $path = $this->getPath();
    $request = $this->route( $path );

    $properties = $request->toArray();

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

    $route = $path . 'route.php';
    $route = include $route;

    return $route;

  }

  public function handleResponse( ResponseInterface $response = null, $properties = [] )
  {

    return $this->response = $this->getResponse( $response )->handleProperties( $properties );

  }

  public function getContainer()
  {

    return $this->container;

  }

  public function get( $id )
  {

    return $this->getContainer()->get( $id );

  }

  public function has( $id )
  {

    return $this->getContainer()->has( $id );

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
