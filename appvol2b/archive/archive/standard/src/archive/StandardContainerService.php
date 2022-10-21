<?php

namespace nathanwooten\Standard;

if ( ! trait_exists( 'StandardContainerService' ) ) {
trait StandardContainerService
{

  public function container( $id = null )
  {

    $container_class = PROJECT_NAME . '\\' . 'Container' . '\\' . 'Container';
    $registry_class = PROJECT_NAME . '\\' . 'Registry' . '\\' . 'Registry';

    $container = $registry_class::get( $container_class );

    if ( ! isset( $id ) ) {
      return $container;
    }

    return $container->get( $id );

  }

}
}