<?php

namespace nathanwooten\Templater;

use nathanwooten\{

  Templater\TemplateInterface,

};

class Template implements TemplateInterface
{

  public $body = '';
  public $name = null;
  public $source;
  public $template = null;

  public function __construct( $source, $name = null, array $properties = null )
  {

    $this->source = $source;
    $this->name = is_null( $name ) ?? $name;
    $this->properties = is_null( $properties ) ?? $properties;

  }

  public function get( $name )
  {

    return $this->has( $name ) ? static::$container[ $name ] : null;

  }

  public function has( $name )
  {

    return array_key_exists( $name, static::$container );

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

  public function getName()
  {

    if ( ! isset( $this->name ) ) {
      return static::class;
    }

    return $this->name;

  }

  public function getProperties()
  {

    return $this->properties;

  }

  public function __toString()
  {

    if ( ! isset( $this->body ) ) {
      return '';

    };

    return (string) $this->body;

  }

}
