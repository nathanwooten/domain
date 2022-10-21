<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Model\Model,
  View\ViewManager,

  Http\RequestInterface,
  Http\Request,
  Http\Uri,

  Loader\Loader

};

class Application
{

  protected static $instance = [];

  protected $path;
  protected RequestInterface $request;

  protected Loader $loader;
  protected array $container = [];

  public array $services = [
    Model::class,
    ViewManager::class
  ];

  public function __construct( RequestInterface $request, Application $application = null )
  {

    $this->path = $application ?? dirname( __FILE__ );
    static::$instance[ $this->path ] = $this;

    $this->request = $request;

    $this->prepare();

    $this->service = $this->request( $request );

  }

  public function prepare()
  {

    $this->getLoader()->prepare( $this );

  }

  public function request( RequestInterface $request )
  {

    $params = $request->getTags();
    $service = $this->retrieve( $params );
    $request->set( get_class( $service ), $service );

    return $request;

  }

  public function get( $tags = [] )
  {

    return Application::tag( $this->container, $tags );

  }

  public function getPath( ApplicationInterface $application = null )
  {

    $path = $application ?? $this->path;
    if ( $path instanceof ApplicationInterface ) {
      $path = $path->getPath();

    }

    return $path;

  }

  public function getLoader()
  {

    if ( ! isset( $this->loader ) ) {
      $loader = Loader::class;
      $this->loader = new $loader( $this );

    }

    return $this->loader;

  }

  public static function getApplication( $path )
  {

    while( ! array_key_exists( $path, static::$instance ) ) {
      $parent = dirname( $path );
      if ( $parent === $path ) {
        throw new Exception( 'Reached root' );

      }
      $path = $parent;
    }

    return static::$instance[ $path ];

  }

  public static function tag( array $services, array $tags )
  {

    $highestPropertyIntersectCountTemplate = false;
    $has = 0;

    foreach ( $services as $key => $service ) {
      $intersect = array_intersect( $properties, $service->getTags() );
      if ( $intersect && count( $intersect ) > $has ) {
        $has = count( $intersect );
        $highestPropertyIntersectCountTemplate = $templateInstance;

      }
    }

    return $highestPropertyIntersectCountTemplate;

  }

}
