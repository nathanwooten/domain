<?php

namespace nathanwooten\Website\Templater;

use nathanwooten\{

  Website\Service,
  Website\Templater\TemplateInterface,
  Website\ApplicationInterface

};

class Template extends Service implements TemplateInterface
{

  protected ?ApplicationInterface $application;
  protected ?FilesystemFile $file;

  protected static array $container = [];

  public $template = null;
  public $name = null;

  public function __construct( ApplicationInterface $application, $pathToTemplate )
  {

    parent::__construct( $application );

    $this->file = new FilesystemFile( $application, $pathToTemplate );

  }

  public function add( ApplicationInterface $application, $pathToTemplate )
  {

    return new static( $application, $pathToTemplate );

  }

  public function getTemplate()
  {

    if ( ! isset( $this->template ) ) {
      $file = (string) $this->file;
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

  public function getPath()
  {

    return $this->path;

  }

  public function getReadable( $basename )
  {

    $readable = $this->getApplication()->getPath( $this->getPath() ) . $basename;
    return $readable;

  }

  public function read( $basename )
  {

    $path = $this->getReadable( $basename );

    $fw = fopen( $path, 'rw' );

    $this->file[ $path ] = new FilesystemFile( 

  }

  public function write()
  {



  }

  public function compile( $base, array $vars = [] )
  {

    $this->getReadable( $base );


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
