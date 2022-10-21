<?php

namespace nathanwooten\Factory;

class Factory
{

  public function create( $id, $args = [], $injection = [] )
  {

    $serviceContainer = $this->getLoader()->getContainer( $id );
    if ( $serviceContainer->isInstantiable() ) {
      $service = new $id( ...array_values( (array) $args ) );

    }

    foreach ( $injection as $callbackArray ) {

      $callback = $callbackArray[0];
      $args = $callbackArray[1];      


    }


  }

}
