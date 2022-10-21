<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Application\ApplicationPackage

};

interface ApplictionServicesProviderInterface extends ApplicationPackage {

  public function getContainer() : ContainerInterface

}