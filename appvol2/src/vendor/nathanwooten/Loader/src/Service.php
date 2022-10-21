<?php

namespace nathanwooten\Website;

use nathanwooten\{

  Website\Application

};

class LoaderService
{

  public $callback = null;
  public $args = [];

  public $result = null;

  public function __construct( LoaderInterface $loader, $id, $raw = null, $args = null, array $properties = null )
  {

    $this->loader = $loader;
    if ( is_object( $id ) ) {
    $this->id = $id;

    $this->callback = $raw;
    $this->args = is_null( $args ) ? [] : $args;
    $this->properties = is_null( $properties ) ? [] : $properties;

    if ( ! is_null( $callback ) ) {
      $this->setCallback( $callback );

    }

    if ( ! is_null( $args ) ) {
      $this->setArgs( $args );

    }

    if ( ! is_null( $properties ) ) {
      $this->setProperties( $properties );

    }

  }

  public function __invoke( $args = null )
  {

    $callback = $this->getCallback();
    if ( ! is_callable( $callback ) ) {
      throw new Exception( 'Uncallable callback during invoke ' . __FILE__ . ' ' . __LINE__ );

    }

    $args = $this->getArgs( $args );

    return $this->result = $callback( ...$args );

  }

  public function setCallback( $callback )
  {

    $this->callback = $callback;

  }

  public function getCallback()
  {

    return $this->callback;

  }

  public function setArgs( $args = null )
  {

    $this->args = $args;

  }

  public function getArgs( $args = null )
  {

    if ( is_null( $args ) ) {
      $args = $this->args;
    }

    $args = (array) $args;
    $args = array_values( $args );

    $this->setArgs( $args );

    return $this->args;

  }

  public function setProperty( $property )
  {

    $this->properties[] = $property;

  }

  public function hasProperty( $property )
  {

    return in_array( $property, $this->properties );

  }

  public function removeProperty( $propertyName )
  {

    unset( $this->properties[ $propertyName ] );

  }

  public function setProperties( array $properties = [] )
  {

    foreach ( $properties as $property ) {
      $this->setProperty( $property );

    }

  }

  public function getProperties()
  {

    return $this->properties;

  }

  public function getId()
  {

    $callback = $this->getCallback();
    $id = $callback[0] . '::' . $callback . '::' . $this->getPropertiesString();

    return $id;

  }

  public function getPropertiesString()
  {

    $string = '';

    foreach ( $properies as $property ) {
      $string .= '::' . $property;
    }

    $string = ltrim( $string, '::' );

    return $string;

  }

  public function __toString()
  {

    return $this->getId();

  }  

}
