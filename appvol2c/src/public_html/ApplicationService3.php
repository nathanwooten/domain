<?php

namespace nathanwooten\Application;

class ApplicationService
{

  public function __construct( Application $application )
  {

    $this->application = $application;

  }

  public function getApplication()
  {

    return $this->application;

  }

  public function getPath()
  {

    return dirname( __FILE__ );

  }




}
