<?php

namespace nathanwooten\Website\Http;

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
