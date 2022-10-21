<?php

namespace nathanwooten\Container;

use nathanwooten\{

  Autoloader

};

use function orDefault;

use Exception;

abstract class ContainerService
{

  protected ContainerInterface $container;

  protected $id;
  protected array $args = [];
  protected array $methods = [];

  protected array $property = [];

  protected array $add = [];

  public function __construct( ContainerInterface $container )
  {

    $this->container = $container;

    if ( ! empty( $this->add ) ) {
      $this->add( $this->add );
    }

  }

  public function service( ...$args )
  {

    if ( ! isset( $this->service ) ) {

      $service = $this->id;
      $args = $this->args( $args );

      if ( ! method_exists( $this, 'method' ) ) {
		$service = new $service( ...$args );

      } else {
        $this->{$this->getName()}();

      }

      if ( $this->isFactory() ) {
        return $service;
      }

      $this->service = $service;
    }

    return $this->service;

  }

  public function args( array $args = null )
  {

    if ( ! isset( $args ) ) {
      if ( ! isset( $this->args ) ) {
        $args = [];
      } else {
        $args = $this->args;
      }
    } elseif ( empty( $args ) ) {
      $args = $this->args;
    }

    foreach ( $args as $key => $arg ) {
      if ( is_string( $arg ) && class_exists( $arg ) ) {
        $args[ $key ] = $this->getContainer()->get( $arg );
      }
    }


    return $this->args = $args;

  }

  public function isFactory()
  {

    if ( isset( $this->property[ 'factory' ] ) && $this->property[ 'factory' ] ) {
      return true;
    }

    return false;

  }

  public function getName()
  {

    return str_replace( '\\', '', strtolower( $this->id ) );

  }

  public function add( array $add )
  {

    foreach ( $add as $pair ) {
      Autoloader::add( ...$pair );
    }

  }

  public function getContainer()
  {

    return $this->container;

  }

}
