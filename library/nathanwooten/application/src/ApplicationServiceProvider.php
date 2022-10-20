<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Application\ApplicationServiceProviderInterface

};

class ApplicationServiceProvider implements ApplicationServiceProviderInterface
{

  public function __construct( ApplicationInterface $application )
  {

    $this->setApplication( $application );

    $this->configureService();

  }

  public function setApplication( ApplicationInterface $application )
  {

    $this->application = $application;

  }

  public function getApplication() : ApplicationInterface
  {

    return $this->application;

  }

  public function getPath()
  {

    return $this->getApplication()->getPath();

  }

  public function get( $id )
  {

    return $this->getApplication()->get( $id );

  }

  // Services get their services from the Application
  public function configureService()
  {

    $this->getApplication()->getContainer()->set( static::class, $this );

    $services = $this->getServices();
    while ( $services ) {
      $service = array_shift( $services );

      // Finds or creates/sets
      $this->get( $service );
    }

  }

}