<?php

namespace nathanwooten\Templater;

use nathanwooten\{

  Templater\TemplateInterface,

};

class Template implements TemplateInterface
{

  protected static array $container = [];

  protected $source = null;

  public $template = null;
  public $name = null;

  public function __construct( $source, $name = null )
  {

    $this->source = $source;
    $this->name = is_null( $name ) ?? $name;

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

  public function compile()
  {

    $source = $this->source;
    $template = $this->getTemplate();

    if ( ! is_readable( $source ) ) {
      $source = sys_get_temp_dir() . 'template.temp.php';
      $source = file_put_contents( $source,  );

    }

    $match = match( $template );
    $names = array_map( fn ( $item ) { return $this->untag( $item ), $match };



    ob_start();
    include $source;
    $output = ob_get_clean();

    return $output;

  }

  public function __toString()
  {

    if ( ! isset( $this->body ) ) {
      return '';

    };

    return (string) $this->body;

  }

}
