<?php

namespace nathanwooten\Website;

use nathanwooten\{

  Autoloader

};

use Exception;

abstract class ServiceContainer
{

  protected ContainerInterface $container;

  public array $args = [];
  public array $autoloads = [];
  public ?string $id = null;
  public array $methods = [];
  public $service;

  protected array $property = [];

  public function __construct( ContainerInterface $container )
  {

    $this->container = $container;
    $this->autoloads( $this->autoloads );

  }

  public function getName()
  {

    return $this->container()->getName( $this->id );

  }

  public function service( array $args = null )
  {

    if ( ! isset( $this->service ) || $this->getProperty( 'factory' ) ) {

      $method = null;

      if ( method_exists( $this, $this->getName() ) ) {
        $method = $this->getName();

      } else {
        $method = 'instantiate';
      }

      $service = $this->$method( $this->args( $args ) );

      if ( $this->isFactory() ) {
        return $service;
      }

      $this->service = $service;
    }

    return $this->service;

  }

  protected function autoloads( array $autoloads )
  {

    return Autoloader::autoload( $autoloads );

  }

  protected function args( array $args = null )
  {

    if ( ! isset( $args ) ) $args = $this->args;

    foreach ( $args as $key => $arg ) {
      if ( is_string( $arg ) && class_exists( $arg ) ) {
        $args[ $key ] = $this->fetch( $arg );

      }
    }

    return $this->args = $args;

  }

  protected function methods()
  {

    $service = $this->service();

    foreach ( $this->methods as $methodName => $args ) {
      $this->methods[ $methodName ] = $this->service()->$methodName( ...array_values( $args ) );

    }

    return $this->methods;

  }

  protected function instantiate( array $args = null )
  {

    if ( ! isset( $this->id ) || ! class_exists( $this->id ) ) {
       throw new Exception( 'Unknown id' . ' ' . __FILE__ . ' ' . __LINE__ );

    }

    $service = $this->container()->create( $this->id, $this->args( $args ) );

    return $service;

  }

  protected function isFactory()
  {

    if ( isset( $this->property[ 'factory' ] ) && $this->property[ 'factory' ] ) {
      return true;

    }

    return false;

  }

  protected function fetch( $id )
  {

    return $this->container()->get( $id );

  }

  protected function container()
  {

    return $this->container;

  }

}
