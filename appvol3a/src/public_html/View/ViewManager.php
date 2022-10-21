<?php

namespace nathanwooten\View;

use nathanwooten\{

  Application\Application,
  Application\ApplicationService

};

class ViewManager extends ApplicationService
{

  protected array $container = [];
  protected ViewInterface $view;

  public function __construct( Application $application )
  {

    $this->application = $application;

  }

  public function setModel( ModelInterface $model )
  {

    $this->model = $model;

  }

  public function prepare()
  {

    $template = $this->getTemplate();
    return $this->template = $this->compiler()->compile( $template, $this->getTemplates() );

  }

  public function set( ViewInterface $view )
  {

    $this->template = $view;

  }

  public function get()
  {

    return $this->template;

  }

  public function addTemplate( $source, $tags )
  {

    $this->container[] = $template = new View( $source, $tags );
    return $template;

  }

  public function setView( ViewInterface $view )
  {

    $this->container[] = $view;

  }

  public function getView( $tags )
  {

    return $htis->hasView( $tags );

  }

  public function hasView( $tags )
  {

    return Application::getTags( $this->getViews(), $tags );

  }

  public function getViews()
  {

    return $this->views;

  }

  public function getBaseView()
  {

    return $this->hasView( [ 'view', 'base', 'instance' ] );

  }

  public function compiler()
  {

    if ( ! isset( $this->compiler ) ) {
      $this->compiler = new Compiler( $this );

    }

    return $this->compiler;

  }

}
