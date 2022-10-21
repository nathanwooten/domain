<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Application\ApplicationInterface

};

interface ApplicationServiceProviderInterface
{

  public function get( $id );
  public function getPath();
  public function configureService();
  public function setApplication( ApplicationInterface $application );
  public function getApplication() : ApplicationInterface;

}