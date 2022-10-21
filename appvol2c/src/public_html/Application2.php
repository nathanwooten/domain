<?php

namespace nathanwooten\Application;

use nathanwooten\{

  Http\Request,
  Http\Uri

};

class Application
{

  public function __construct()
  {

    $this->application = dirname( __FILE__ );
    $this->request = new Request( new Uri );

  }

  public function getPath( $basename )
  {

    if ( is_readable( $basename ) ) {
      return $basename;

    }

    if ( is_readable( $path ) ) {
      $path = $this->application . DIRECTORY_SEPARATOR . $basename;
      return $path;

    }

  }

}

class Templater
{

  function __construct( Template $baseTemplate )
  {

    $this->template = $baseTemplate;

  }

  public function respond( TemplateInterface $template = null )
  {

    $template = is_null( $template ) ? $this->template : $template;
    $match = $template->match();

    $template_list = $this->template->getTemplates();

    $intersect = array_intersect_key( array_flip( $match ), $template_list );
    $intersect = array_combine( $match, array_slice( $templates_list, 0, count( $match ) ) );

    foreach ( $intersect as $name => $template ) {
      $intersect[ $name ] = $this->respond( $template );
    }

    $response = $template->compile( $template, $intersect );
    return $response;

  }

}

class HtmlElement
{

  public function __construct( Application $application )
  {

    $this->application = $application;

  }

  public function prepare()
  {

    $path = $this->application->getPath( $this->path );
    foreach ( scandir( $path ) as $item ) {
      $file = $path . DIRECTORY_SEPARATOR . $item;
      if ( is_file( $file ) && is_readable( $file ) ) {
        $this->templates[ $item ] = 
      }
    }
  }

}

class HtmlElementLink
{

  protected $element = 'link';
  protected $path = '/style';

  public function __construct( Application $application )
  {

    parent::__construct( $application );

  }

}

class Document
{

  public $templates = [];
  public $response = null;

  public function __construct( Application $application )
  {

    $this->application = $application;

    $this->prepare();

  }

  public function prepare()
  {

    foreach ( $this->paths as $tag_name => $path ) {
      $path = $this->application->getPath( $path );

      if ( $path ) {
        $this->paths[ $tag_name ] = $path;

        foreach ( scandir( $this->paths[ $tag_name ] ) as $item ) {
          $file = $this->paths[ $tag_name ] . $item;
          $file = $this->application->getPath( $file );
          if ( $file ) {
            $this->paths[ $tag_name ]

          }
        }
      }
    }

    foreach( (array) $this as $public_property_name => $tag_name ) {
      $this->response[ $public_property_name ] = PHP_EOL . implode( PHP_EOL, $this->$tag_name );

    }

  }

  public function htmltag( $tag_name, $input )
  {

    if ( ! is_null( $input ) && ! is_array( $input ) ) {
      $input = [ $input ];

    }

    $template = $this->getTemplater()->create( $this->map[ $tag_name ], $input );

    $this->$tag_name[ implode( '', $input ) ] = $template;

  }

  public function getTemplater()
  {

    return $this->application->get( Templater::class );

  }

  public function getPath( $basename )
  {

    return $this->application->getPath( $basename );

  }

}
