<?php

namespace nathanwooten\Container;

class Container
{

  protected $services = [];
  protected $directory;

  public function __construct( $directory = null )
  {

    if ( ! is_null( $directory ) ) {
      $this->config( $directory );
    }

  }

  public function set( $id, $service )
  {

    if ( $this->isA( $service, $id ) ) {
      $this->services[ $id ] = $service;
    }

  }

  public function get( $id, $args = null )
  {

    if ( array_key_exists( $id, $this->services ) ) {
      return $this->services[ $id ];
    }

    $service = $this->create( $id, $args );
    $this->set( $id, $service );

    return $service;

  }

  protected function create( $id, $args = null )
  {

    $service = false;

    $args = func_get_args();
    if ( ! isset( $args[2] ) ) {
        $directory = $this->config();
	} else {
        $directory = $args[2];
    }
    $directory = rtrim( $directory, DS ) . DS;
    $name = static::name( $id );

	$readable = $directory . $name . DS . $name . 'service.php';
    while ( ! is_readable( $readable ) ) {

      $scan = scandir( $directory );
      while ( ! in_array( $name, $scan ) ) {

        foreach ( $scan as $item ) {
          $file = $directory . DS . $item . DS . $item . '.php';

          if ( file_exists( $file ) ) {
          } elseif ( is_dir( $directory . DS . $item ) ) {
            $dir = $directory . DS . $item;

            $service = $this->create( $id, $args, $dir );
          }
        }
      }
    }

    if ( ! $service ) {
      $container = new $id( $this );
      $service = $container->service();
    }

    return $service;

  }

  public function config( $directory = null )
  {

    if ( isset( $directory ) ) {
      $this->directory = $directory;
    }

    return $this->directory;

  }

  protected function isA( $is, $a )
  {

    if ( is_object( $a ) ) {
      if ( is_a( $a, $is ) ) {
        return true;
      }

      return false;
    }

    return true;

  }

  public function __invoke()
  {

    $readable = $this->config();
    $scan = scandir( $readable );


  }

  public static function name( $id )
  {

    $name = str_replace( '\\', '', strtolower( $id ) );
    return $name;

  }

}
