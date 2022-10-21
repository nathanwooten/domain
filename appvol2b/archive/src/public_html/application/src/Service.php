<?php

namespace nathanwooten\Website;

use nathanwooten\{

  Autoloader

};

use Exception;

abstract class Service
{

  public function __construct( ApplicationInterface $application )
  {

    $this->setApplication( $application );

    if ( ! $application->has( static::class ) ) {
      $application->set( static::class, $this );
    }

  }

  public function setApplication( ApplicationInterface $application )
  {

    $this->application = $application;

  }

  public function getApplication()
  {

    return $this->application;

  }

}
