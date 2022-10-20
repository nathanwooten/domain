<?php

// MIT License, Copyright 2022 Nathan Wooten, https://github.com/nathanwooten/Autoloader/blob/main/LICENSE.md

namespace nathanwooten;

use Exception;

if ( ! class_exists( 'nathanwooten\Autoloader' ) ) {
class Autoloader
{

  const PROPERTIES = [ 'namespace', 'directory' ];

  // Configured
  private static array $autoloads = [];

  // Configure this
  protected static $config;

  protected static array $properties = [
    self::PROPERTIES[0] => 'namespace',
    self::PROPERTIES[1] => 'directory'
  ];

  protected static bool $normalize = true;
  protected static bool $registered = false;

  public static function configure( $config = null ) {

    if ( ! static::$registered ) {
      spl_autoload_register( [ 'nathanwooten\Autoloader', 'load' ], true, true );
      static::$registered = true;
    }

    $config = static::getConfig( $config );

    foreach ( $config as $vendor_path => $vendors ) {
      foreach ( $vendors as $package ) {

        $namespace = static::getPackageNamespace( $package );
        if ( ! static::has( $namespace ) ) {
          if ( ! isset( static::$autoloads[ $vendor_path ] ) ) {
            static::$autoloads[ $vendor_path ] = [];
          }

          static::$autoloads[ $vendor_path ][] = $package;
        }
      }
    }

    return $config;

  }

  public static function setConfig( array $config, $validate = 1 )
  {

    if ( $validate ) {
	  $config = static::config( $config );
    }

    return static::$config = $config;

  }

  public static function getConfig( array $config = null, $validate = 1 )
  {

    if ( is_null( $config ) ) {
      $config = static::$config;
    }

    if ( $validate ) {
      $config = static::config( $config );
    }

    return static::setConfig( $config, ! (bool) $validate );

  }

  public static function config( $config )
  {

    if ( ! is_array( $config ) ) {
      if ( ! is_string( $config ) ) {
        throw new Exception( __FILE__ . ' ' . __LINE__ );
      }
      $file = $config;
      if ( ! file_exists( $file ) ) {
        throw new Exception( __FILE__ . ' ' . __LINE__ );
      }
      $config = include $file;
    }

    if ( ! is_array( $config ) ) {
      throw new Exception( __FILE__ . ' ' . __LINE__ );
    }

    foreach ( $config as $vendor_path => $vendors ) {

      if ( ! is_readable( $vendor_path ) ) {
        throw new Exception( __FILE__ . ' ' . __LINE__ );
      }
      if ( ! is_array( $vendors ) ) {
        throw new Exception( __FILE__ . ' ' . __LINE__ );
      }

      foreach ( $vendors as $index => $vendor ) {

        if ( ! is_array( $vendor ) ) {
          throw new Exception( __FILE__ . ' ' . __LINE__ );
        }

        $package = static::package( $vendor );

        $namespace = static::getPackageNamespace( $package );
        $directory = static::getPackageDirectory( $package );

        if ( static::has( $namespace ) || static::has( $directory ) ) {
          continue;
        }

        if ( ! is_readable( static::doNormalize( $vendor_path, 'r' ) . DIRECTORY_SEPARATOR . $directory ) ) {
          throw new Exception( __FILE__ . ' ' . __LINE__ );
        }

        $config[ $vendor_path ][ $index ] = $package;
      }

    }

    return $config;

  }

  // The trick here in the case of loading files directly,
  // is to use an extension prefix, as in My\notInterface.tpl
  // which is turned into My\notInterface.tpl.php

  public static function load( $interface )
  {

    $result = null;

    foreach ( static::$autoloads as $vendor_path => $packages ) {

      $vendor_path = static::doNormalize( $vendor_path, 'r' );

      foreach ( $packages as $package ) {

        // Required by the autoloader to load a package
        $namespace = static::getPackageNamespace( $package );
        $directory = static::getPackageDirectory( $package );

        if ( static::normalize() ) {
		  $namespace = static::doNormalize( $namespace, 'r' );
          $directory = static::doNormalize( $directory, 'r' );
        }

        $interface = static::doNormalize( $interface );

        if ( false !== strpos( $interface, $namespace ) ) {
		  $file = str_replace( $namespace, $vendor_path . DIRECTORY_SEPARATOR . $directory, $interface ) . '.php';

          if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
            continue;
          }

          $result = require_once $file;

          return $result;
        }
      }
    }

    return 1;

  }

  public static function get( $id )
  {

    return $this->has( $id );

  }

  public static function has( $namespace )
  {

    foreach ( static::$autoloads as $vendor_path => $packages ) {
      foreach ( $packages as $package ) {

        if ( $namespace === static::getNamespace( $package ) ) {
          return $package;

        } else {
          $id = $namespace;

          if ( in_array( $id, $package ) ) {
            return $package;
          }
        }
      }
    }

    return false;

  }

  public static function package( array $details, array $sort = null )
  {

    if ( ! is_array( $details ) ) {
      throw new Exception( __FILE__ . ' ' . __LINE__ );
    }

    $package = [];

    $sort = is_null( $sort ) ?  static::getProperties() : $sort;

    $keys = array_values( $sort );
    $values = array_values( $details );

    foreach ( $sort as $index => $property ) {
      if ( array_key_exists( $property, $details ) ) {
        $package[ $property ] = $details[ $property ];

      } else {
        $i = array_search( $index, $keys );

        if ( array_key_exists( $i, $values ) ) {
          $package[ $property ] = $values[ $i ];
        } else {
          throw new Exception( __FILE__ . ' ' . __LINE__ );
        }
      }
    }

    // Return packaged
    return $package;

  }

  public static function setProperties( array $properties )
  {

    foreach ( $properties as $constant => $property ) {
      static::setProperty( $constant, $property );
    }

  }

  public static function getProperties()
  {

    return static::$properties;

  }

  public static function getProperty( $constant )
  {

    return static::$properties[ $constant ];

  }

  public static function removeProperty( $constant )
  {

    unset( static::$properties[ $constant ] );

  }

  public static function getPackageNamespace( $package, $constant = 'namespace' )
  {

    return static::getPackageProperty( $constant, $package );

  }

  public static function getPackageDirectory( $package, $constant = 'directory' )
  {

    return static::getPackageProperty( $constant, $package );

  }

  public static function getPackageProperty( $constant, $package )
  {

    if ( ! is_array( $package ) || empty( $package ) ) {
      throw new Exception( __FILE__ . ' ' . __LINE__ );
    }

    $property = static::$properties[ $constant ];

    if ( ! array_key_exists( $property, $package ) ) {
      throw new Exception( __FILE__ . ' ' . __LINE__ );
    }

    $property = $package[ $property ];
    return $property;

  }

  public static function normalize( bool $normalize = null)
  {

    // If provided set it
    if ( isset( $normalize ) ) {
      static::$normalize = $normalize;
    }

    // Return property
    return static::$normalize;

  }

  protected static function setProperty( $constant, $property )
  {

    static::$properties[ $constant ] = $property;

  }

  protected static function doNormalize( $item, $side = 'r' )
  {

    $fn = $side . 'trim';

    // True trim
    $normalized = $fn( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $item ), DIRECTORY_SEPARATOR );
  
    return $normalized;

  }

  protected static function valueOr( $default, $value = null )
  {

    return ! is_null( $value ) ? $value : $default;

  }

}
}
