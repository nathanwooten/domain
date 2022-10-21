<?php

namespace nathanwooten\Loader\Services;

use nathanwooten\{

  Loader\LoaderService,
  Loader\LoaderMethod

};

class ModelServiceModel extends LoaderMethod
{

  public LoaderService $container;
  public $method;
  public $tags = [];

  public function __construct( LoaderService $container )
  {

    $this->container = $container;
    $this->method();

  }

}
