<?php

namespace websiteproject\Container\Services\websiteprojectcontainerdependencies;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use websiteproject\{

  Container\Dependencies as IdClass

};

class websiteprojectcontainerdependenciesservice extends ContainerService
{

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

}
