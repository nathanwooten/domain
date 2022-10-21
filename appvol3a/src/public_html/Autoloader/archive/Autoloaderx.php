<?php

// MIT License, Copyright 2022 Nathan Wooten, https://github.com/nathanwooten/Autoloader/blob/main/LICENSE.md

namespace nathanwooten;

use Exception;

if ( ! class_exists( 'nathanwooten\Autoloader' ) ) {
class Autoloader
{

  protected $container = [];
  protected $keys = [ 'namespace' => 0, 'directory' => 1, 'vendor_path' => 2 ];

  protected static bool $normalize = true;
  protected static bool $registered = false;

  public static function configure( $namespace, $directory, $vendor_path = null, $prefix = true )
  {

    if ( ! static::$registered ) {
      spl_autoload_register( [ 'nathanwooten\Autoloader', 'load' ], true, true );
      static::$registered = true;

    }

    if ( ! $this->has( $namespace ) ) {
      $package = [];
      $package[ $this->getKeyNamespace() ] = $namespace;
      $package[ $this->getKeyDirectory() ] = $directory;
      if ( ! is_null( $vendor_path ) {
        $package[ $this->getKeyVendorPath() ] = $vendor_path;

      }

      if ( $prefix ) {
        $container = [];
        $container[ $namepace ] = $package;

        foreach ( $this->container as $space => $pack ) {
          $container[ $space ] = $pack;

        }

        $this->container = $container;

    } else {
      $this->container[ $namespace ] = $package;

    }

    return $namespace;

  }

  public static function load( $interface )
  {

    $result = 1;

    foreach ( static::$autoloads as $packages ) {
      foreach ( $packages as $package ) {
        $namespace = static::getPackageNamespace( $package );
        $directory = static::getPackageDirectory( $package );

        if ( false !== strpos( $interface, $namespace ) ) {
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
    }

    return $result;

  }

  public static function has( $namespaceOrProperty )
  {

    $has = true;

    foreach ( static::$autoloads as $packages ) {
      if ( array_key_exists( $namespace, $packages ) ) {
         return $packages[ $namespace ];

      }

      foreach ( $packages as $namespace => $package ) {
        if ( in_array( $namespaceOrProperty, $package ) ) {
          return $package;

        }
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

  public function getPackageNamespace( $package )
  {

    if ( isset( $package[ $this->getNamespaceKey() ] ) ) {
      return $package[ $this->getNamespaceKey() ] );

    }

  }

  public function getPackageDirectory( $package )
  {

    $key = $this->getKeyDirectory();
    if ( isset( $package[ $key ] ) ) {
      $directory = $package[ $key ];

      $vendor_path = $this->getPackageVendorPath( $package );
      if ( is_null( $vendor_path ) ) {
        $vendor_path = '';

      }

      $directory = $this->doNormalize( $vendor_path, null, DIRECTORY_SEPARATOR ) . $directory;

      return $directory;

    }

  }

  public function getPackageVendorPath( $package )
  {

    $key = $this->getKeyVendorPath();
    if ( isset( $package[ $key ] ) ) {
      return $package[ $key ];

    }

  }

  public function getKeyNamespace()
  {

    return $this->keys[ 'namespace' ];

  }

  public function getKeyDirectory()
  {

    return $this->keys[ 'directory' ];

  }

  public function getKeyVendorPath()
  {

    return $this->keys[ 'vendor_path' ];

  }


}
}
