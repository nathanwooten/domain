<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Application\Container\ContainerInterface

};

trait ApplicationServicesProviderTrait
{

  public function getContainer() : ContainerInterface
  {

    if ( ! is_a( $this->container, ContainerInterface::class ) ) {
      if ( ! class_exists( $this->container ) ) {
        if ( ! class_exists( Container::class ) ) {
          $class = false;
        }

        $class = Container::class;

      } else {

        $class = $this->container;

      }

      if ( $class ) {
        $this->container = new $class;
      } else {
        throw new Exception( __FILE__ . ' ' . __LINE__ );
      }
    }

    return $this->container;

  }

}