<?php

namespace websiteproject\Container\Services\websiteprojectcontainerdependencies;

use nathanwooten\{

  Container\Container,
  Container\ServiceContainer

};

use websiteproject\{

  Container\Dependencies as IdClass

};

class websiteprojectcontainerdependenciesservice extends ServiceContainer
{

  public function __construct( Container $container )
  {

    parent::__construct( $container );

  }

}
