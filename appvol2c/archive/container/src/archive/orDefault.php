<?php

namespace nathanwooten\Container;

trait OrDefault
{

  public function orDefault( $object, $property, $value = null )
  {

    if ( isset( $value ) ) {
      return $value;
    }

    $rProperty = new ReflectionProperty( $object, $property );
    if ( $rProperty->isPublic() ) {
		if ( isset( $object->$property ) ) {
          return $object->$property;
        }
    }

  }

}
