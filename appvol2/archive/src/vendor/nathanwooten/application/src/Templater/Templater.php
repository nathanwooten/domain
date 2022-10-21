<?php

namespace nathanwooten\Application;

class TemplaterAbstract extends ServiceProvider implements TemplaterPackage
{

  protected ApplicationInterface $application;

  public function __construct( ApplicationInterface $application )
  {

    $this->setApplication( $application );

  }

  public function addTemplate( $path )
  {

    if ( ! $path instanceof Template::class ) {
      $template = $this->create( $path );
    } else {
      $template = $path;
      $path = $template->getPath();
    }

    $this->templates[ $path ] = $template;

  }

  public function getTemplate( $location )
  {

    return $this->templates[ $location ];

  }

  public function compile( $template, $vars = [] )
  {

    $file = sys_get_temp_dir() . 'tempTemplateFile.php';

    $put = file_put_contents( $file );
    if ( ! $put ) {
      throw new Exception( __FILE__ . ' ' . __LINE__ );
    }

    extract( $vars );

    ob_start();

    include $file;
    $contents = ob_get_clean();

    return $contents;

  }

  function create( $basename )
  {

    if ( ! is_a( $basename, TemplateAbstract::class ) ) {
      $template = new Template( $this, $basename );
    } else {
      $template = $basename;
    }

  }

  public function prepare( $array )
  {

    foreach ( $array as $name => $source ) {
      $array[ $name ] = prepareTemplate( $nmae, $source );
    }

    return $array;

  }

  function match( $template )
  {

    preg_match_all( '/\{\{.*?\}\}/', $template, $matches );

    if ( isset( $matches[0] ) ) {
      return $matches[0];
    }

  }

}
