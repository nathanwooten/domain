<?php

namespace nathanwooten\Application;

class TemplaterAbstract extends ServiceProvider
{

  protected ApplicationInterface $application;

  protected $services = [
    StandardFiles::class
  ];

  public $config = [
    'template.ini',
    'variable.ini'
  ];

  public function __construct( ApplicationInterface $application )
  {

    $this->setApplication( $application );

  }

  public function configure()
  {

    $config = $this->config;
    foreach ( $config as $key => $configuration ) {
      if ( is_integer( $key ) ) {
        unset( $config[ $key ] );

        $this->getConfig( $configuration );
      }
    }

  }

  public function getConfig( $basename )
  {

    if ( ! isset( $this->config[ $basename ] ) ) {
      $this->config[ $basename ] = new ApplicationItem( $this->getApplication()->getPath(), $basename );
    }

    return $this->config[ $basename ]->getFileContents();

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

    if ( ! is_a( $basename, TemplateInterface::class ) ) {
      $template = new Template( $this->getApplication(), $basename );
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
