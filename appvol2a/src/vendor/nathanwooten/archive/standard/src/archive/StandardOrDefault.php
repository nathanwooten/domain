<?php

namespace nathanwooten\Standard;

if ( ! trait_exists( 'nathanwooten\Standard\StandardOrDefault' ) ) {
trait StandardOrDefault
{

  use StandardRun;

  protected function orDefault( $property, $value = null, string $getter = null )
  {

    return $this->standardRun( __FUNCTION__, [ $this, $property, $value, $getter ] );

  }

}
}
