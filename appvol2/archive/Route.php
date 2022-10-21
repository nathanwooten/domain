<?php

namespace nathanwooten\Website;

use nathanwooten\{

  Website\ApplicationInterface

};

class Route
{

  public array $parameters = [];

  public function __construct( ApplicationInterface $application, array $parameters )

    $this->application = $application;
    $this->parameters = $parameters;

  }

}
