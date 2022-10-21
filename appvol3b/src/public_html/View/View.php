<?php

namespace nathanwooten\Templater;

use nathanwooten\{

  Templater\TemplateInterface,

};

class View extends ViewFile implements TemplateInterface
{

  public $body = '';
  public $file;
  public $name = null;
  public $template = null;

  public function __construct( $source, $tags = [] )
  {

    parent::__construct( $source, $tags );

  }

  public function getTemplate()
  {

    if ( ! isset( $this->template ) ) {
      $file = (string) $this->source;
      if ( ! is_readable( $file ) ) {
        throw new Exception( 'Unreadable file ' . $file . ' ' . __FILE__ . ' ' . __LINE__ );
      }

      $this->template = file_get_contents( $file );
    }

    return $this->template;

  }

  public function __toString()
  {

    if ( ! isset( $this->body ) ) {
      return '';

    };

    return (string) $this->body;

  }

}
