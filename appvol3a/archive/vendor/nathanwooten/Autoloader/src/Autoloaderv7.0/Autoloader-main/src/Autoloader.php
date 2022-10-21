<?php

// MIT License, Copyright 2022 Nathan Wooten, https://github.com/nathanwooten/Autoloader/blob/main/LICENSE.md

namespace nathanwooten;

use Exception;

if ( ! class_exists( 'nathanwooten\Autoloader' ) ) {
class Autoloader
{

  protected static $container = [];
  protected static $keys = [ 'namespace' => 0, 'directory' => 1, 'vendor_path' => 2 ];

  protected static bool $normalize = true;
  protected static bool $registered = false;

  public static function configure( $namespace, $directory, $vendor_path = null )
  {

    if ( ! static::$registered ) {
      spl_autoload_register( [ 'nathanwooten\Autoloader', 'load' ], true, true );
      static::$registered = true;

    }

    if ( ! static::has( $namespace ) ) {
      $package = [];
      $package[ static::getKeyNamespace() ] = $namespace;
      $package[ static::getKeyDirectory() ] = $directory;
      if ( ! is_null( $vendor_path ) ) {
        $package[ static::getKeyVendorPath() ] = $vendor_path;

      }

      static::$container[ $namespace ] = $package;

    }

    return $namespace;

  }

  public static function load( $interface )
  {

    $result = 1;

    foreach ( static::$container as $namespace => $package ) {
      $namespace = static::getPackageNamespace( $package );
      $directory = static::getPackageDirectory( $package );

      if ( false === strpos( $interface, $namespace ) ) {
        continue;

      }

      if ( static::normalize() ) {
        $namespace = static::doNormalize( $namespace, 'r' );
        $directory = static::doNormalize( $directory, 'r' );

      }

      $interface = static::doNormalize( $interface );

      $file = str_replace( $namespace, $directory, $interface ) . '.php';

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
      $directory = $package[ $key ];

      $vendor_path = static::getPackageVendorPath( $package );
      if ( is_null( $vendor_path ) ) {
        $vendor_path = '';

      }

      $directory = static::doNormalize( $vendor_path ) . DIRECTORY_SEPARATOR . $directory;

      return $directory;

    }

  }

  public static function getPackageVendorPath( $package )
  {

    $key = static::getKeyVendorPath();
    if ( isset( $package[ $key ] ) ) {
      return $package[ $key ];

    }

  }

  public static function getKeyNamespace()
  {

    return static::$keys[ 'namespace' ];

  }

  public static function getKeyDirectory()
  {

    return static::$keys[ 'directory' ];

  }

  public static function getKeyVendorPath()
  {

    return static::$keys[ 'vendor_path' ];

  }

}
}
