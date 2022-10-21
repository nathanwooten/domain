<?php

namespace nathanwooten\Container;

use nathanwooten\{

  Autoloader

};

use ReflectionMethod;

use function orDefault;

use Exception;

abstract class ContainerService
{

  protected ContainerInterface $container;

  // All four of these properties must be set
  // in the service container class and
  // they must be in this order and
  // they must be the only public properties

  public array $autoloads = [];
  public array $args = [];
  public string $service;
  public array $methods = [];

  protected array $property = [];

  public function __construct( ContainerInterface $container )
  {

    $this->container = $container;

    $this->load();

  }

  public function getName()
  {

    return str_replace( '\\', '', strtolower( $this->service ) );

  }

  protected function load()
  {

    $load = (array) $this;
    extract( $load );

    foreach ( $load as $propertyName => $propertyValue ) {

      $methodName = $propertyName;
      if ( is_array( $propertyValue ) ) $propertyValue = [ $propertyValue ];

      $args = [];
      $params = ( new ReflectionMethod( $this, $methodName ) )->getParameters();
      if ( ! empty( $params ) ) {
        $params = array_map( function( $param ) { return $param->getName(); }, $params );

        foreach ( $params as $paramName ) {
          if ( ! isset( $args[ $paramName ] ) ) {
            $args[ $paramName ] = ${$propertyName};
          }
        }
        $args = $this->sortArgs( $params, $args );
      }
var_dump( $args );
      $this->{$propertyName} = $this->$methodName( ...array_values( $args ) );
    }

  }

  public function service( array $args = [] )
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

  protected function autoloads( array $autoloads )
  {

    return Autoloader::autoload( $autoloads );

  }

  protected function args( array $args = null )
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

  protected function methods()
  {

    foreach ( $this->methods as $methodName => $args ) {
      $this->methods[ $methodName ] = $this->service()->$methodName( ...array_values( $args ) );
    }

    return $this->methods;

  }

  protected function sortArgs( $params, $args )
  {

    $sorted = [];

    if ( ! is_array( $params ) ) {
      throw new Excpetion( 'Argument: "params" must be an array' );
    }
    if ( ! is_array( $args ) ) {
      throw new Exception( 'Argument: "args" must be an array' );
    }

    $sorted = [];
    foreach ( $params as $paramName ) {

      $sorted[ $paramName ] = $args[ $paramName ];
    }

    return $sorted;

  }

  protected function isFactory()
  {

    if ( isset( $this->property[ 'factory' ] ) && $this->property[ 'factory' ] ) {
      return true;
    }

    return false;

  }

  protected function getContainer()
  {

    return $this->container;

  }

}
