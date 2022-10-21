<?php

namespace nathanwooten\Application;

class ApplicationService
{

  public function __construct( Application $application )
  {

    $this->application = $application;

  }

  public function getServicePath()
  {

    return dirname( __FILE__ );

  }

  public function getService()
  {

    if ( ! isset( $this->service ) ) {


    }

    return $this->service;

  }


  public function getPath( $append = '' )
  {

    $path = '';
    $path .= $this->getRoot() . DIRECTORY_SEPARATOR;
    $path .= $this->getApplication()->getRequest()->getUrl()->getComponenet( PHP_URL_PATH );
    $path .= $append;

    if ( is_readable( $path ) ) {
      return $path;
    }

  }

  public function getApplication()
  {

    return $this->application = $this->getLoader()->get( Application::class, [ 'path' => $this->getRoot() ] );

  }

  protected array $response = [];
  protected array $delimiters = [ '{{', '}}' ];

}