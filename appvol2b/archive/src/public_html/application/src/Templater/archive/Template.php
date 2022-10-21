<?php

namespace nathanwooten\Website\Templater;

use nathanwooten\{

  Website\Templater\Templater

};

class Template extends Templater
{

  public function __construct( $source, array $methods = [], TemplateInterface $parent )
  {

    $this->template = $source;
    $this->methods( $this, $methods );

  }

  public function set( $template )
  {

    $this->template = $template;

  }

  public function get()
  {

    return $this->template;

  }

  public function setTemplate( TemplateInterface $template )
  {

    $this->container[] = $template;

  }

  public function getTemplate( $methodName, $args = [], $value = null )
  {

    foreach ( $this->container as $k => $template ) {
      if ( $value === $this->method( $template, $methodName, $args ) ) {
        return $template;
      }
    }

  }

}