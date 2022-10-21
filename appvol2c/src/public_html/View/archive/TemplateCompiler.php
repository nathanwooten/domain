<?php

namespace nathanwooten\Templater;

class TemplateCompiler
{

  public function __construct( TemplaterInterface $templater )
  {

    $this->templater = $templater;

  }

  public function compile( $template )
  {

    foreach ( $this->match( $template ) as $tag ) {
      $name = $this->untag( $tag );

      $template = $this->replace( $template, $name );
      $template = $this->template( $buffer );

    }

    return $template;

  }

  public function buffer( TemplateInterface $template )
  {

    if ( ! is_readable( $template ) ) {
      $source = sys_get_temp_dir() . 'template.temp.php';
      $put = file_put_contents( $source, $template );

    }

    $templates = $this->getTemplates();
    extract( $templates );

    ob_start();
    include $source;
    $output = ob_get_clean();

    return $output;

  }

  public function replace( $template, $name )
  {

    $template = str_replace( $this->tag( $name ), print '<?php print $' . (string) $name '; ?>';
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

    $delimiters = [ '{{', '}}' ];

    if ( $escape ) {
      foreach ( $delimiters as $k => $delimiter ) {
        $delimiters[ $k ] = str_split( $delimiter );
        $delimiters[ $k ] = implode( '\\', $delimiters[ $k ] );

      }
    }

    return $delimiters;

  }

}
