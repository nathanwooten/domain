<?php

namespace nathanwooten\Website\Templater;

class TemplateEngine extends Template
{

  protected $template = null;
  protected array $container = [];

  protected ?string $body = null;
  protected ?ResponseInterface $response = null;

  public function __construct( ApplicationInterface $application, $source = null )
  {

    $this->setApplication( $application );

    if ( ! is_null( $source ) ) {
      parent::__construct( $source, $methods );
    }

  }

  public function createTemplate( $methods = [] )
  {

    $template = new Template;
    $template = $this->methods( $template, $methods );

    return $template;

  }

  public function contains( $template, $var = '.*?' )
  {

    preg_match_all( $this->delimit( $var ), $template, $matches );

    if ( isset( $matches[0] ) ) {
      return $matches[0];
    }

  }

  public function capture( $readable, array $vars = [] )
  {

    extract( $vars );

    ob_start();
    include $readable;
    $output = ob_get_clean();

    return $output;

  }

  public function delimit( $name, $escape = 0 )
  {

    $delimiters = [];

    $delimiters[] = $this->delimiters( $escape );
    $delimiters[] = $this->delimiters( ! $escape );

    foreach ( $delimiters as $delimiter ) {
      $name = trim( $name, $delimiter[0] );
      $name = trim( $name, $delimiter[1] );

    }

    $delimiters = array_value( $delimiters );

    $tag = $delimiters[0][0] . $name . $delimiters[0][1];

    return $tag;

  }

  public function delimiters( $escape = 0 )
  {

    $delimiters = $this->delimiters;

    if ( $escape ) {
      foreach ( $delimiters as $k => $delimiter ) {
        $delimiters[ $k ] = str_split( $delimiter );
        $delimiters[ $k ] = implode( '\\', $delimiters[ $k ] );
      }
    }

    return $delimiters;

  }

  public function setDelimiters( array $delimiters )
  {

    $this->delimiters = array_values( array_slice( $delimiters, 0, 2 ) );

  }

  public function __toString()
  {

    if ( ! isset( $this->body ) ) {
      return '';
    };

    return (string) $this->body;

  }

}
