<?php

namespace nathanwooten\Model;

use nathanwooten\{

  Application\ApplicationService

};

class ModelService extends ApplicationService
{

  public function __construct()
  {

    parent::__construct( dirname( __FILE__ ) ):

  }

}
