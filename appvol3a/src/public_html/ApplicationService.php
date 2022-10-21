<?php

namespace nathanwooten\Application;

/**

var namespace = nathanwooten;

application > get

model

to

var directory = application->getPath() . ucfirst( 'model' );





*/

class ApplicationService
{

  protected $path;

  public function __construct( $path )
  {

    $this->path = $path;
    $this->application = Application::getApplication( $this->path );

  }

  public function getPath()
  {

    return $this->path;

  }

  public function getApplication()
  {

    return $this->application;

  }

}
