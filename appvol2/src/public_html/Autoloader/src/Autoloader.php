<?php

// MIT License, Copyright 2022 Nathan Wooten, https://github.com/nathanwooten/Autoloader/blob/main/LICENSE.md

namespace nathanwooten;

use Exception;

if ( ! class_exists( 'nathanwooten\Autoloader' ) ) {
class Autoloader
{

  const SUPPORTS = [ 'PSR-4', 'PSR-0' ];

  const NAMESPACE_ = 'namespace';
  const DIRECTORY_ = 'directory';
  const SUPPORT_ = 'support';

  protected static $keys = [
    Autoloader::NAMESPACE_ => 0,
    Autoloader::DIRECTORY_ => 1,
    Autoloader::SUPPORT_ => 2
  ];

  protected static $container = [];

  protected static $map = [];

  protected static bool $normalize = true;
  protected static bool $registered = false;

  public static function configure( $namespace, $directory, array $map = null, string $support = self::SUPPORTS[0] )
  {

    if ( ! static::$registered ) {
      spl_autoload_register( [ 'nathanwooten\Autoloader', 'load' ], true, true );
      static::$registered = true;

    }

    if ( ! static::has( $namespace ) ) {
      $package = [];
      $package[ static::getKeyNamespace() ] = $namespace;
      $package[ static::getKeyDirectory() ] = $directory;
      $package[ static::getKeySupport() ] = is_null( $support ) || ! in_array( $support, Autoloader::SUPPORTS ) ? 'PSR-4' : $support;

      static::map( $map );

      static::$container[ $namespace ] = $package;

    }

    return $namespace;

  }

  public static function load( $interface )
  {

    $result = 1;

    if ( array_key_exists( $interface, static::$map ) ) {
      $interface = static::map[ $interface ];
    }

    $interface = static::doNormalize( $interface );

    foreach ( static::$container as $namespace => $package ) {
      $namespace = static::getPackageNamespace( $package );
      if ( static::normalize() ) {
        $namespace = static::doNormalize( $namespace, 'r' );

      }
      if ( 0 !== strpos( $interface, $namespace ) ) {
        continue;

      }

      $class = str_replace( $namespace, '', $interface );

      if ( 'PSR-0' === static::getPackageSupport( $package ) ) {
        $class = str_replace( '_', DIRECTORY_SEPARATOR, $class );

      }

      $directory = static::getPackageDirectory( $package );
      $directory = static::parseDirectory( $directory );
      $directory = static::doNormalize( $directory, 'r' );

      $file = $directory . $class . '.php';

      if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
        continue;

      }

      $result = require_once $file;

    }

    return $result;

  }

  public static function has( $namespaceOrProperty )
  {

    $has = true;

    $mapped = static::map( $namespaceOrProperty );
    if ( array_key_exists( $mapped, static::$container ) ) {
      return static::$container[ $mapped ];

    }

    if ( array_key_exists( $namespaceOrProperty, static::$container ) ) {
      return static::$container[ $namespaceOrProperty ];

    }

    foreach ( static::$container as $namespace => $package ) {
      if ( in_array( $namespaceOrProperty, $package ) ) {
        return $package;

      }
    }

    return ! $has;

  }

  public static function map( $alias, $id = null )
  {

    if ( is_array( $alias ) ) {
      foreach ( $alias as $a => $i ) {
        static::$map[ $a ] = $i;
        return;

      }
    }

    $map = static::$map;

    if ( ! is_null( $id ) ) {
      return $map[ $alias ] = $id;
    }

    if ( array_key_exists( $alias, $map ) ) {
      return $map[ $alias ];
    }

  }

  public static function normalize( bool $normalize = null )
  {

    // If provided set it
    if ( isset( $normalize ) ) {
      static::$normalize = $normalize;

    }

    // Return property
    return static::$normalize;

  }

  public static function doNormalize( $item, $side = '' )
  {

    $item = str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $item );

    if ( ! is_null( $side ) ) {
      $fn = $side . 'trim';
      $item = $fn( $item, DIRECTORY_SEPARATOR );

    }
  
    return $item;

  }

  public static function getPackageNamespace( $package )
  {

    $key = static::getKeyNamespace();

    if ( isset( $package[ $key ] ) ) {
      return $package[ $key ];

    }

  }

  public static function getPackageDirectory( $package )
  {

    $key = static::getKeyDirectory();
    if ( isset( $package[ $key ] ) ) {
      return $package[ $key ];

    }

  }

  public static function getPackageSupport( $package )
  {

    $key = static::getKeySupport();
    if ( isset( $package[ $key ] ) ) {
      return $package[ $key ];

    }

  }

  public static function getKeyNamespace()
  {

    return static::$keys[ Autoloader::NAMESPACE_ ];

  }

  public static function getKeyDirectory()
  {

    return static::$keys[ Autoloader::DIRECTORY_ ];

  }

  public static function getKeySupport()
  {

    return static::$keys[ Autoloader::SUPPORT_ ];

  }

  public static function parseDirectory( $directory )
  {

    if ( is_string( $directory ) ) {
      return $directory;

    }

    if ( is_array( $directory ) ) {
      $directory = array_map( fn ( $item ) => trim( (string) $item, DIRECTORY_SEPARATOR ), $directory );
      $directory = implode( DIRECTORY_SEPARATOR, $directory );

    }

    if ( ! is_string( $directory ) ) {
      $directory = (string) $directory;

    }

    return $directory;

  }

}
}
