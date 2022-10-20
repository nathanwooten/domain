<?php

namespace nathanwooten\Application\Http;

class Message
{

  public function properties( $properties = [] )
  {

    foreach ( $properties as $name => $callbackArray ) {
      $properties[ $name ] = $callbackArray[0]( ...$callbackArray[1] );
    }

    return $properties;

  }

}

class RequestAbstract extends Message
{

  public $services = [];

}
