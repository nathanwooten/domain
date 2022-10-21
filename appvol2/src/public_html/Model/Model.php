<?php

namespace nathanwooten\Model;

use nathanwooten\{

  Application\Application

};

class Model
{

  protected $viewManager;

  public function __construct()
  {

    $this->application = Application::getApplication( dirname( __FILE__ ) );

  }

  public function setViewManager( ViewManagerInterface $viewManager )
  {

    $this->viewManger = $viewManager;

  }

  public function model()
  {

    $view = $this->getView();
    $viewManager = $this->getViewManager();
    $compiler = $viewManager->compiler();

    $keys = $compiler->match( $view );
    foreach ( $keys as $tag ) {
      $viewManager->set( $compiler->untag( $tag ), $this->fetch( $tag ) );

    }

  }

  public function fetch( $tags )
  {

    return $this->getLoader()->get( $tags );

  }

  public function setView( ViewInterface $view )
  {

    $this->view = $view;

  }

  public function getView()
  {

    return $this->view;

  }

  public function getAlternate( $tags )
  {

    return $this->viewManager->getView( $tags );

  }

  public function getLoader()
  {

    return $this->application->getLoader();

  }

}
