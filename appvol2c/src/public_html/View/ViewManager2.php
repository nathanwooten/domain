<?php

namespace nathanwooten\View;

use nathanwooten\{

  Application\ApplicationService

};

class ViewManager extends ApplicationService
{

  public $actions = [
    [
      'set',
      BaseTemplate::class
    ]
  ];

  public TemplateInterface $template;

  public array $container = [];

  public function set( ViewInterface $view )
  {

    $this->template = $view;

  }

  public function get()
  {

    return $this->template;

  }

  public function addTemplate( $source, $name = null, $properties = [] )
  {

    $this->container[] = $template = new View( $source, $name, $properties );
    return $template;

  }

  public function setView( ViewInterface $view )
  {

    $this->container[] = $view;

  }

  public function getView( $tags )
  {




  }

  public function hasTemplate( $tags )
  {

    return Application::getTags( $this->getTemplates(), $tags );

  }


/*
  public function hasTemplate( array $properties = [] )
  {

    $highestPropertyIntersectCountTemplate = false;
    $has = 0;

    $templates = $this->getTemplates();

    foreach ( $this->getTemplates() as $key => $templateInstance ) {
      $intersect = array_intersect( $properties, $templateInstance->getProperties() );
      if ( $intersect && count( $intersect ) > $has ) {
        $has = count( $intersect );
        $highestPropertyIntersectCountTemplate = $templateInstance;

      }
    }

    return $highestPropertyIntersectCountTemplate;

  }
*/
  public function getBaseTemplate()
  {

    return $this->has( [ 'base' ] );

  }

  public function prepare()
  {

    $template = $this->getTemplate();

    return $this->template = $this->compiler()->compile( $template, $this->getTemplates() );

  }

}
