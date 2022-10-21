<?php

class TemplaterAbstract
{

  protected array $response = [];
  protected array $delimiters = [ '{{', '}}' ];

  public array $container = [];
  public TemplateInterface $template;

  public function prepare( TemplateInterface $template = null )
  {

    $template = is_null( $template ) ? $this->template : $template;
    $match = $template->match();

    $template_list = $template->getTemplates();

    $intersect = array_intersect_key( array_flip( $match ), $template_list );
    $intersect = array_combine( $match, array_slice( $templates_list, 0, count( $match ) ) );

    foreach ( $intersect as $name => $template ) {
      $intersect[ $name ] = $this->prepare( $template );
    }

    $template = $template->compile( $template, $intersect );
    return $template;

  }

  public function match( $template, $specific = null )
  {

    $expression = is_null( $specific ) ? '(.*?)' : $specific;

    $regex = '/' . $this->delimit( $expression ) . '/';
    preg_match_all( $regex, $template, $match );

    $match = $match[0];
    return $match;

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

}
