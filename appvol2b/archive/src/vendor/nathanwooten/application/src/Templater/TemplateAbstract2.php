<?php

namespace nathanwooten\Application\Templater;

use nathanwooten\{

  Application\Templater\Templater

};

class TemplateAbstract
{

  protected array $children = [];
  protected ?TemplateInterface $parent = null;
  protected array $templates = [];

  public array $template = [];

  public function __construct( TemplaterInterface $templater, $id = null, $template = null, array $children = [] )
  {

    $this->templater = $templater;

    $this->set( $id, $template, $children );

  }

  public function set( $id = null, $template = null, array $children = [] )
  {

    if ( ! is_null( $id ) ) {
      $this->id = $id;
    }

    if ( ! is_null( $template ) ) {
      $this->template[] = $template;
    }

    if ( ! empty( $children ) ) {
      foreach ( $children as $template ) {
        $this->setTemplate( $template );
      }
    }

  }

  public function setTemplate( TemplateInterface $template )
  {

    $this->templates[ $template->getId() ] = $template;

  }

  public function getTemplate( $id )
  {

    return array_key_exists( $id, $this->templates ) ? $this->templates[ $id ] : null;

  }

}
