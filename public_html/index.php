<?php

use nathanwooten\{

  Application\Container\ContainerInterface,

  Application\Http\RequestInterface,
  Application\Http\Request,

};

use websiteproject\{

  Application\Application,
  Container\Container

};

$application = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

if ( ! defined( 'PROJECT_PATH' ) || ! defined( 'LIB_PATH' ) ) die( __FILE__ . '::' . __LINE__ );




/*
global $template;
if ( ! isset( $template ) ) {
  $template = new Template( $app->getPath(), $app->getFile( 'template.tpl' ) );
}
*/
/*
function prepareTemplate( $template, $vars = [] )
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
*/
/*
class Item
{

  // This is the project_path plus "public_html" plus the request/uri target
  public $id;
  public $item;

  public string $file;
  public $data = [];

  public function __construct( AppInterface $app, $item )
  {

    $this->app = $app;
    $this->item = $item;

  }

  public function get()
  {

    $file = $this->getId() . $this->item;

    if ( file_exists( $file ) ) {

      $this->file = $file;
      $this->data[] = $this->getApp()->getFile( $file );

    } else {
      $this->file = $this->data = false;
    }

  }

  public function has()
  {

    return file_exists( $this->getDirectory() . $this->getItem() );

  }

  public function getId()
  {

    $directory = $this->getApp()->getPath() . $this->getRequest()->getUri()->getTarget();
    return $directory;

  }

  public function getRequest()
  {

    return $this->request;

  }

  public function getApp()
  {

    return $this->app;

  }

}

class Template extends TemplateAbstract {}

$request = $container->get( Request::class );
var_dump( $request );

$app = new Application( $request );
$vars = $app->getFile( 'variables.ini' );
*/

/*
class ApplicationFiles
{

  public array $files = [
    'template' => 'template.tpl',
    'vars' => 'variables.ini'
  ];

  public function getContents( ?ApplicationItem $item )
  {






    $basename = basename( $file );
    $extension = substr( $basename, 1 + strpos( $basename, '.' ) );

    $methodName = 'getFile' . ucfirst( $extension );

    if ( ! is_callable( [ $this, $methodName ] ) ) {
      $methodName = 'getFileNone';
    }

    $file = $this->getPath() . implode( DS, array_filter( $dir ) ) . $basename;
    $contents = $this->$methodName( $file );

    return $this->files[ $basename ] = $contents;

  }

  public function toFileNone()
  {

    return $file;

  }

  public function getFileTpl( $file )
  {

    return file_get_contents( $file );

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

  public function getFiles( $name = null )
  {

    $files = $this->files;
    if ( ! is_null( $name ) ) {
      return array_key_exists( $name, $files ) ? $files[ $name ] : null;
    }

    return $files;

  }

}
*/