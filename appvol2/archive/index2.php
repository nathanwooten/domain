<?php

use nathanwooten\{

  Http\RequestInterface,
  Http\Request,

  Standard\StandardFiles

};

$container = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

if ( ! defined( 'PROJECT_PATH' ) || ! defined( 'LIB_PATH' ) ) die( __FILE__ . '::' . __LINE__ );

interface ApplicationPackage {}

interface ApplicationInterface extends ApplicationPackage {

  public function getPath();
  public function getRoot();

}

interface ApplicationFilesInterface {

  public function getFiles();

}

interface DirectoryInterface
{

  public function __toString();

}
interface ApplicationDirectoryInterface extends DirectoryInterface, ApplicationPackage {}

interface ApplicationItemInterface extends ApplicationPackage {}

interface ServicesInterface {}

trait ServicesProviderTrait
{

  public function getContainer()
  {

    if ( ! is_a( $this->container, ContainerInterface::class ) ) {
      if ( ! class_exists( $this->container ) ) {
        if ( ! class_exists( Container::class ) ) {
          $class = false;
        }

        $class = Container::class;

      } else {

        $class = $this->container;

      }

      if ( $class ) {
        $this->container = new $class;
      } else {
        throw new Exception( __FILE__ . ' ' . __LINE__ );
      }
    }

    return $this->container;

  }

}

interface ServiceProviderInterface
{

  public function setApplication( ApplicationInterface $application );
  public function getApplication() : ApplicationInterface;

}

class ServiceProvider implements ServiceProviderInterface
{

  public function __construct( ApplicationInterface $application )
  {

    $this->setApplication( $application );

    $this->configureService();

  }

  public function setApplication( ApplicationInterface $application )
  {

    $this->application = $application;

  }

  public function getApplication() : ApplicationInterface
  {

    return $this->application;

  }

  public function getPath()
  {

    return $this->getApplication()->getPath();

  }

  public function get( $id )
  {

    return $this->getApplication()->get( $id );

  }

  // Services get their services from the Application
  public function configureService()
  {

    $services = $this->getServices();
    while ( $services ) {
      $service = array_shift( $services );

      // Finds or creates/sets
      $this->getApplication()->get( $service );
    }

  }

}

class Application implements ApplicationInterface
{

  use ServicesProviderTrait;

  protected RequestInterface $request;
  protected $root;
  protected $path;

  public function __construct( $root, RequestInterface $request, $paths = [] )
  {

    $this->root = $root;
    $this->request = $request;

    $this->path = new ApplicationDirectory( $this, $paths );

  }

  public function getRoot()
  {

    return $this->root;

  }

  public function getRequest()
  {

    return $this->request;

  }

  public function getPath()
  {

    return $this->path;

  }

}

class ApplicationDirectory implements ApplicationDirectoryInterface
{

  protected ApplicationInterface $application;

  public function __construct( ApplicationInterface $application, $dir = null )
  {

    $this->application = $application;

    $requestPath = $application->getPath();

    if ( is_null( $dir ) ) {
      $this->dir = '';
      return;
    }

    if ( ! is_array( $dir ) ) {
      $this->dir = $requestPath . trim( str_replace( '\\', '/', DS, $dir ), DS );
      return;
    }

    foreach ( $dir as $i => $d ) {
      if ( ! $d instanceof ApplicationDirectoryInterface ) {
        $directory = new ApplicationDirectory( $d );
        $dir[ $i ] = $directory;
      }
    }

    $this->dir = implode( DS, $dir );

  }

  public function __toString()
  {

    return $this->dir;

  }

}

class ApplicationItem implements ApplicationItemInterface {

  public function __construct( ApplicationDirectoryInterface $directory, $basename )
  {

    $this->directory = $directory;
    $this->basename = $basename;

  }

  public function getDirectory()
  {

    return $this->directory;

  }

  public function getBasename()
  {

    return $this->basename;

  }

}

interface TemplateInterface {

  public function set( $id = null, $template = null, array $chilren = [], $parent = null );
  public function get();

  public function setTemplate( TemplateInterface $template );
  public function getTemplate( $id );

}

class TemplateAbstract
{

  protected array $children = [];
  protected ?TemplateInterface $parent = null;
  protected array $templates = [];

  public array $template = [];

  public function __construct( AppInterface $app, $id, $template, array $children = [], TemplateInterface $parent = null )
  {

    $this->set( $id, $template, $children, $parent );

  }

  public function set( $id = null, $template = null, array $children = [], $parent = null )
  {

    if ( ! is_null( $id ) ) {
      $this->id = $id;
    }

    if ( ! is_null( $template ) ) {
      $this->template[] = $template;
    }

    if ( ! empty( $children ) ) {
      foreach ( $children as $template ) {
        $this->setTemplate( $template );
      }
    }

    if ( ! is_null( $parent ) ) {
      $this->parent = $parent;
    }

  }

  public function setTemplate( TemplateInterface $template )
  {

    $this->templates[ $template->getId() ] = $template;

  }

  public function getTemplate( $id )
  {

    return array_key_exists( $id, $this->templates ) ? $this->templates[ $id ] : null;

  }

}

class Templater extends ServiceProvider
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

}

function prepareVars( $vars )
{

  foreach ( $vars as $var_name => $var ) {
    $vars[ $var_name ] = prepareTemplate( $var, $vars );
  }

  return $vars;

}

function match( $template )
{

  preg_match_all( '/\{\{.*?\}\}/', $template, $matches );

  if ( isset( $matches[0] ) ) {
    return $matches[0];
  }

}

function getVars()
{

  $vars = fetchVars();
  $vars = fetchVariables();
  $vars = createVariables( $vars );
  $vars = prepareVars( $vars );

  return $vars;

}



$application = new Application( PROJECT_PATH . 'public_html', $container->get( Request::class ) );
var_dump( $application );

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