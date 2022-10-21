<?php

namespace nathanwooten\View;

class ViewService extends ApplicationService
{

  public function __construct( Application $application )
  {

    parent::__construct( $application, [ 'view', 'instance' ] );

  }

}
