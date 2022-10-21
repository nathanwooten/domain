<?php

namespace nathanwooten\Standard;

if ( ! trait_exists( 'nathanwooten\Standard\StandardRun' ) ) {
trait StandardRun
{

  use StandardContainerService;

  public function standardRun( $fn_name, array $args = [] )
  {

    $dependencies = $this->container( Dependencies::class );
    $result = $dependencies->runUser( $fn_name, $args );

    return $result;

  }

}
}