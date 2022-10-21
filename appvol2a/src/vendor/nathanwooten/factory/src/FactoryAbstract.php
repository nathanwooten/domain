<?php

namespace nathanwoten\Factory;

class FactoryAbstract
{

  protected $id;
  protected $properties = []

  public static function getInstance()
  {

    if ( ! isset( static::$instance ) || is_a( static::$instance, FactoryAbstract::class ) ) {
      static::$instance = new static;
    }

    return static::$instance;

  }

  public static function create( $id, $properties = [] )
  {

    $instance = static::getInstance();

    $properties = $this->properties( $properties );

    $created = new $id( ...array_values( $properties ) );

  }

  public function properties( array $properties )
  {

    if ( is_null( $properties ) ) {
      $properties = $this->properties;
    }

    foreach ( array_keys( $properties ) as $property ) {
      $properties[ $property ] = static::$property( ...$this->getArgs( $properties[ $property ] ) );
    }

    return $properties;;

  }

  public function property( $name, $value = null, $args = [] )
  {

     $none = false;

     if ( is_null( $value ) ) {
       $value = static::$properties[ $name ];
     }

    if ( is_string( $value ) && class_exists( $value ) ) {
      $value = static::create( $value, $args );
    }

	return $this->properties[ $name ] = $value;

  }

  public function getArgs()
  {

    return $this->properties();

  }

}
