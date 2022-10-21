<?php

class ApplicationService
{

  public function getServicePath()
  {

    public function dirname( __FILE__ );

  }

  public function getPath( $append = '' )
  {

    $path = '';
    $path .= $this->getRoot() . DIRECTORY_SEPARATOR;
    $path .= $this->getApplication()->getRequest()->getUrl()->getComponenet( PHP_URL_PATH );
    $path .= $append;

    if ( is_readable( $path ) ) {
      return $path;
    }

  }

  public function getApplication()
  {

    return $this->application = $this->getLoader()->get( Application::class, [ 'path' => $this->getRoot() ] );

  }

  protected array $response = [];
  protected array $delimiters = [ '{{', '}}' ];

}

class Fs
{

  public function __construct( $root = PROJECT_PATH )
  {

    $this->root = $root;

  }

}

class FsDirectory
{

  public $directory;
  public $from = null;
  public $tagged = [];

  public function __construct( $directory, array $tags = [] )
  {

    if ( is_object( $directory ) ) {
      $this->from = $directory;
      $this->directory = (string) $directory;

    }

    if ( is_string( $directory ) ) {
      $this->directory = Fs::normalizeDirectory( $directory, '', '' );

    }

    if ( is_array( $directory ) ) {
      $this->directory = implode( DIRECTORY_SEPARATOR, array_map( fn( $item ) return trim( $item, DIRECTORY_SEPARATOR ), $directory );

    }

    if ( ! empty( $tags ) ) {
      $this->tagDirectory( $tags );

    }

  }

  public function tagDirectory( array $tags = [] )
  {

    $this->tagged = [];

    $directory = explode( DIRECTORY_SEPARATOR, $this->directory );

    $tagged = [];
    foreach ( $tags as $name => $counts ) {

      $tagged[ $name ] = [];
      $count = 0;

      foreach ( $counts as $count ) {
        if ( ! $count ) {
          $this->tagged = $directory;
        }

        $counted = '';
        while( $count ) {
          $substr = substr( $directory, $count, strpos( $directory, DIRECTORY_SEPARATOR ) );
          $directory = str_replace( $substr, '', $directory );
          $counted .= $substr;

          count--;

        }        

        $tagged[ $name ] = new FsDirectory( $counted );

      }
    }

    $this->tagged = $tagged;

  }

}

class FsFile
{

  public function __construct( $fsDirectory, $basename )
  {

    $this->directory = $fsDirectory;
    $this->basename = $basename;

  }

}

class FsTemplateFile extends FsFile
{

  public function __construct( $basename )
  {

    $this->directory = new FsTemplateDirectory;

  }

}

class FsTemplateDirectory extends FsDirectory
{

  public function __construct( $directory = '', array $tags = [] )
  {

    parent::__construct( [ PUBLIC_HTML, 'Templater', $directory ], $tags ] );

  }

}


class Templater extends Arrayy
{

  public $actions = [
    [
      'addTemplate',
      [ 'template.php', 'document', [ 'base' ] ]
    ],
  ];

  public TemplateInterface $template;

  public array $container = [];

  public function addTemplate( $source, $name, $properties = [] )
  {

    $template = new Template( $source, $name, $properties );
    return $template;

  }

  public function setTemplate( TemplateInterface $template )
  {

    $this->container[] = $template;

  }

  public function getTemplate()
  {

    return $this->template;

  }

  public function hasTemplate( array $properties = [] )
  {

    $highestPropertyIntersectCountTemplate = false;
    $has = 0;

    $templates = $this->getTemplates();

    foreach ( $this->getTemplates() as $key => $templateInstance ) {
      $intersect = array_intersect( $properties, $templateInstance->getProperties() );
      if ( $intersect && count( $intersect ) > $has ) {
        $has = count( $intersect );
        $highestPropertyIntersectCountTemplate = $templateInstance;

      }
    }

    return $highestPropertyIntersectCountTemplate;

  }

  public function getBaseTemplate()
  {

    return $this->has( [ 'base' ] );

  }



  public function prepare()
  {

    $template = $this->getTemplate();

    return $this->template = $this->compiler()->compile( $template, $template->getTemplates() );

  }

}
