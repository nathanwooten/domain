<?php

namespace nathanwooten\Application\Templater;

use nathanwooten\{

  Application\Templater\TemplaterPackage

};

interface TemplateInterface extends TemplaterPackage {

  public function set( $id = null, $template = null, array $chilren = [], $parent = null );
  public function get();

  public function setTemplate( TemplateInterface $template );
  public function getTemplate( $id );

}
