<?php

use nathanwooten\{

  Http\RequestInterface,
  Http\Request

};

$container = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

if ( ! defined( 'PROJECT_PATH' ) || ! defined( 'LIB_PATH' ) ) die( __FILE__ . '::' . __LINE__ );

abstract class ModelAbstract
{

  const PATH = PROJECT_PATH . 'public_html';

  protected RequestInterface $request;

  public function __construct( RequestInterface $request )
  {

    $this->location( $request );

  }

  public function location( RequestInterface $request )
  {

    $contents = $this->contents();
    foreach ( $contents as $item ) {
      $params[ $item ] = $this->getFile( $item );
    }

    $this->setRequest( $request );

  }

  protected function setRequest( RequestInterface $request )
  {

    $uri = $request->getUri();

    $target = $uri->getTarget();
    $dir = static::PATH;

    if ( is_file( $dir . $target ) ) {
      $target = str_replace( basename( $target ), '', $target );
    }

    $path = $dir . $target;
    if ( is_readable( $path ) ) {

      $this->request = $request;
    }

  }

  public function getUri()
  {

    $request = $this->getRequest();
    if ( $request ) {
      return $request->getUri();
    }

  }

  public function getPath()
  {

    $uri = $this->getUri();
    if ( $uri ) {
      return 
    }

    return $this->path;

  }

  public function getContent()
  {

    if ( empty( $this->location ) ) {
      $this->location = array_map( function ( $dirItem ) { return $this->getPath() . $dirItem; }, scandir( $this->getPath() ) );
    }

    return $this->location;

  }

  public function getFile( $basename )
  {

    $extension = substr( $basename, strpos( $basename, '.' ) );
    $methodName = 'getFile' . ucfirst( $extension );

    if ( ! is_callable( $this, $methodName ) ) {
      $methodName = 'getFileNone';
    }

    return $this->files[ $basename ] = $this->$methodName( $this->path() . $basename );

  }

  public function getFileNone( $file )
  {

    return $file;

  }

  public function getFilePhp( $file )
  {

    return include $file;    

  }

  public function getFileHtml( $file )
  {

    return file_get_contents( $file );

  }

  public function getFileIni( $file )
  {

    return parse_ini_file( $file );

  }

}

class Model extends ModelAbstract {}

$request = $container->get( Request::class );
var_dump( $request );

$model = new Model( $request );
$vars = $model->getFile( 'variables.ini' );
var_dump( $vars );





