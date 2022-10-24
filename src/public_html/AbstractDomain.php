<?php

namespace nathanwooten;

use Exception;

class AbstractDomain
{

  protected $path;
  protected $basespace = [ 'nathanwooten' ];
  protected $map = [];

  protected $domains = [];
  protected $items = [];

  public function __construct( $domain, $basename = null )
  {

    $this->path = $domain . ( isset( $basename ) ? DIRECTORY_SEPARATOR . $basename : '' );

    $this->manual( 'Item' );

    $this->scan();

  }

  public function scan()
  {

    $scan = scandir( $this->path );
    foreach ( $scan as $item ) {
      if ( '.' === $item || '..' === $item ) {
        continue;
      }
      $this->add( $item );
    }

  }

  public function add( $item )
  {

    $itemPath = $this->path . DIRECTORY_SEPARATOR . $item;

    switch( is_file( $itemPath ) ) {
      case true:
        if ( is_subclass_of( $item, AbstractDomain::class ) ) {
          $property = 'domains';
          $class = get_class( $item );
        } else {
          $property = 'items';
          $class = Item::class;
        }
        break;
      case false:
        $property = 'domains';
        $class = AbstractDomain::class;
        break;
    }

    $this->$property[ $item ] = new $class( $this, $item );

  }

  public function __toString()
  {

    return $this->getPath();

  }

  public function getBase()
  {

    return $this->base;

  }

  public function getDomain()
  {

    return $this;

  }

  public function getPath( $basename = null )
  {

    $path = $this->path;

    if ( $basename ) {
      $with = $path . DIRECTORY_SEPARATOR . $basename;
      if ( is_dir( $with ) && $this->isReadable( $with ) ) {
        return $with;
      } else {
        throw new Exception( 'Path does not exist ' . $with );
      }
    }

    return $path;

  }

  public function getBasespace()
  {

    return $this->basespace;

  }

  public function has( $basename )
  {

    return array_key_exists( $basename, $this->container );

  }

  public function isReadable( $check = '' )
  {

    $check = $check ?? $this->getPath();

    return is_readable( $check );

  }

  public function load( $basename, $args = [] )
  {

    if ( ! $this->has( $basename ) ) {
      $this->autoload( $basename );

      $item = $this->container[ $basename ] = new Item( $this, $basename );
    } else {
      $item = $this->container[ $basename ];
    }

    $item->getArgs();

    return $item;

  }

  public function autoload( $interface )
  {

    if ( array_key_exists( $interface, $this->map ) ) {
      $interface = $this->map[ $interface ];
    }

    foreach ( $this->getBasespace() as $basespace ) {
      $file = str_replace( $basespace, $this->getPath(), $interface ) . '.php';
      if ( $this->isReadable( $file ) ) {
        return require $file;
      }
    }

  }

  public function manual( $name )
  {

    foreach ( $this->getBasespace() as $basespace ) {
      $interface = $basespace . '\\' . $name;
      $result = $this->autoload( $interface );
      if ( $result ) {
        return $result;
      }
    }

  }

  public function map( array $map )
  {

    foreach ( $map as $alias => $real ) {
      class_alias( $real, $alias, true );
    }

  }

  public function normalize( $directory )
  {

    return trim( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $directory ), DIRECTORY_SEPARATOR );

  }

}
