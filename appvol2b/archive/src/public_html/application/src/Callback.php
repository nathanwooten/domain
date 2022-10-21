<?php

namespace nathanwooten\Website;

use nathanwooten\{

  Website\Application

};

class Callback
{

  public $callback = null;
  public $args = [];

  public $result = null;

  public function __invoke( $args )
  {

    $callback = $this->getCallback();
    if ( ! is_callable( $callback ) ) {
      throw new Exception( 'Uncallable callback during invoke' . ' ' __FILE__ . ' ' . __LINE__ );
    }

    $args = $this->getArgs();
    $args = array_values( (array) $args );

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

  public function getArgs()
  {

    return $this->args;

  }

  public function setProperties( array $properties = [] )
  {

    foreach ( $properties as $propertyName => $flag ) {
      $this->properties[ $propertyName ] = $flag;
    }

  }

  public function getProperties()
  {

    return $this->properties;

  }

  public function removeProperty( $propertyName )
  {

    unset( $this->properties[ $propertyName ] );

  }

  public function getId()
  {

    $callback = $this->getCallback();
    $id = $callback[0] . '::' . $callback . $this->toString( $this->getProperties() );

    return $id;

  }

  protected function toString( array $properies )
  {

    $string = '';

    foreach ( $properies as $name => $value ) {
      $string .= '::' . $name . '=' . (string) $value;
    }

    return $string;

  }

}
