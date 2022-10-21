<?php

namespace nathanwooten\Website;

use nathanwooten\{

  Website\WebsitePackage

};

interface ApplictionServicesProviderInterface extends WebsitePackage {

  public function getContainer() : ContainerInterface

}