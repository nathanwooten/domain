<?php

// MIT License, Copyright 2022 Nathan Wooten, https://github.com/nathanwooten/Autoloader/blob/main/LICENSE.md

namespace nathanwooten;

use Exception;

if ( ! class_exists( 'nathanwooten\Autoloader' ) ) {
class Autoloader {

  // Package properties, constants for consistency
  const NAMESPACE_ = 'namespace';
  const DIRECTORY_ = 'directory';

  const PROPERTIES_ = 'properties';
    const EXTENSION_ = 'extension';
    const NAME_ = 'name';
    const NORMALIZE_ = 'normalize';
    const RESULT_ = 'result';

  protected static $property_list = [
    self::NAME_,
    self::RESULT_,
    self::NORMALIZE_,
    self::EXTENSION_
  ];

  protected static bool $normalize = true;

  // Is/is-not registered, automatic on set

  protected static bool $registered = false;

  // Packages can be set freely, as processing is lazy, please include at least a namespace and directory

  public static array $packages = [];

  public static function autoload( $config = null )
  {

    $config = static::config( $config );
    $autoloads = [];

    $label_namespace = static::NAMESPACE_;
    $label_directory = static::DIRECTORY_;
    $label_properties = static::PROPERTIES_;

    foreach( $config as $vendor_path => $packages ) {

      $vendor_path = static::doNormalize( $vendor_path );

      foreach ( $packages as $params ) {
        if ( ! is_array( $params ) ) {
          throw new Exception( 'A package must be an array: ' . gettype( $params ) . ' given' );
        }

        // Package params
        $packaged = static::package( $params, static::getParamList() );

        // Package properties
        $packaged = static::with( $packaged, static::getParamList(), $label_properties,

          ( isset( $packaged[ $label_properties ] ) ? static::package( $packaged[ $label_properties ], static::getPropertyList() ) : [] )
        );

        // With base directory
        $packaged = static::withBaseDirectory( $packaged, $vendor_path );

        // Reset with result
		$index = static::set( $packaged );

        $got = static::get( $index );

        $autoloads[ $index ] = $got;
      }
    }

    return $autoloads;

  }

  public static function config( $config )
  {

    if ( ! is_array( $config ) ) {
      if ( ! is_string( $config ) ) {
        throw new Exception( 'If config is not an array it must be a readable string, ' . gettype( $config ) . ' provided' );
      }
      $file = $config;
      if ( ! file_exists( $file ) ) {
        throw new Exception( 'Config file does not exist: ' . $file );
      }
      $config = include $file;
    }

    if ( ! is_array( $config ) ) {
      throw new Exception( 'Config must evaluate to an array' );
    }

    foreach ( $config as $vendor_path => $packages ) {
		
      if ( ! is_readable( $vendor_path ) ) {
        throw new Exception( 'All vendor paths (config keys), must be readable, given: ' . $vendor_path );
      }

      if ( ! is_array( $packages ) ) {
        throw new Exception( 'Packages array must be and array, given: ' . gettype( $packages ) );
      }

	  foreach ( $packages as $index => $package ) {
        if ( ! is_array( $package ) || empty( $package ) ) {
          throw new Exception( 'A package must be an array and must not be empty, given: ' . gettype( $package ) );
        }
      }
    }

    return $config;

  }

  // Add a package by parameter, including $namespace, $directory and $properties;

  public static function add( $namespace, $directory, $properties = [] )
  {

    return static::set( static::package( func_get_args(), static::getParamList() ) );

  }

  // Set a package, requires a name property

  public static function set( $packaged )
  {

    // Check register always
    if ( ! static::$registered ) {
      // Register once
      spl_autoload_register( 'nathanwooten\Autoloader::load', true, true );
      static::$registered = true;
    }

    $label_namespace = static::NAMESPACE_;
    $label_directory = static::DIRECTORY_;

    $label_properties = static::PROPERTIES_;
      $label_property_name = static::NAME_;
      $label_property_normalize = static::NORMALIZE_;

    // Return and properties
    $index = null;

    if ( ! static::isPackaged( $packaged ) ) {
       throw new Exception( 'Please attempt to set packaged arrays only' );
    }

    $index = static::has( $packaged[ $label_namespace ] );

    if ( $index || 0 === $index ) {
      //do nothing
    } else {
      $packaged = static::setDefaults( $packaged );

      $index = count( static::$packages );

      // Set it
      static::$packages[ $index ] = $packaged;
    }

    // Return index
    return $index;

  }

  // Get package, by value, from the packages array

  public static function get( $id, $index = false )
  {

    $package = false;
    $key = false;

    if ( ! is_string( $id ) && ! is_integer( $id ) ) {
      throw new Exception( 'Please provide the id as a string or integer, provided: ' . gettype( $id ) );
    }

    $packages = static::$packages;
    $label_properties = static::PROPERTIES_;

    if ( array_key_exists( $id, $packages ) ) {
      $package = $packages[ $id ];

      $got = $index ? $id : $package;

    } else {

      if ( false === $package ) {
	    foreach ( $packages as $key => $pkg ) {
          if ( in_array( $id, $pkg ) ) {
            $package = $pkg;
          }
        }
      }

      if ( false === $package && isset( $package[ $label_properties ] ) && is_array( $package[ $label_properties ] ) ) {
        foreach ( $packages as $key => $pkg ) {
          if ( in_array( $id, $package[ $label_properties ] ) ) {
            $package = $pkg;
          }
        }
      }

      $got = $index ? $key : $package;

    }

    return $got;

  }

  // Has property in the packages array

  public static function has( $namespace )
  {

    $label_namespace = static::NAMESPACE_;

    foreach ( static::$packages as $index => $package ) {
      if ( $namespace === $package[ $label_namespace ] ) {
        return $index;
      }
    }

    return false;

  }

  public static function with( array $array, array $keys, $key, $value = null )
  {

    if ( ! in_array( $key, $keys ) ) {
      throw new Exception( 'Key must exist in the keys array' );
    } 

    $array[ $key ] = $value;

    return $array;

  }

  public static function withBaseDirectory( array $packaged, $vendor_path )
  {

    if ( ! static::isPackaged( $packaged ) ) {
      throw new Exception( 'Must be packaged, ' . __FUNCTION__ );
    }

    $label_directory = static::DIRECTORY_;

    $directory = $vendor_path . DIRECTORY_SEPARATOR . static::doNormalize( $packaged[ $label_directory ], 'l' );
    $packaged[ $label_directory ] = $directory;

    return $packaged;

  }

  // Package a set of properties

  public static function package( $array, $keys )
  {

    if ( ! is_array( $array ) || ! is_array( $keys ) ) {
      throw new Exception( 'Package array and keys must both be of the type "array"' );
    }

    if ( count( $array ) < count( $keys ) ) {
      $slice = 'keys';
      $count = count( $array );

    } elseif ( count( $key ) < count( $array ) ) {
      $slice = 'array';
      $count = count( $keys );

    }

    if ( isset( $slice ) ) {
      // Var vars come in handy
      $$slice = array_slice( $$slice, 0, $count );
    }

    $packaged = array_combine( $keys, $array );

    // Return packaged
    return $packaged;

  }

  // Unpackage and array

  public static function unpackage( array $associative )
  {

    // Since we can't return multiple values we simple un-assoc the array
    $unpackaged = array_values( $assoc );
    if ( isset( $unpackaged[ 2 ] ) && is_array( $unpackaged[ 2 ] ) ) {
      $unpackaged[ 2 ] = static::unpackage( $unpackaged[ 2 ] );
    }

    return $unpackaged;

  }

  public static function isPackaged( $potentially_packaged )
  {

     $isPackaged = true;

     $paramList = static::getParamList();
     $values = array_values( $potentially_packaged );

     foreach ( $paramList as $index => $key ) {
       if ( ! isset( $potentially_packaged[ $key ] ) || $potentially_packaged[ $key ] !== $values[ $index ] ) {

         return false;
       }
     }

     return $isPackaged;

  }

  // Get normalize option value

  public static function normalize( bool $normalize = null)
  {

    // If provided set it
    if ( isset( $normalize ) ) {
      static::$normalize = $normalize;
    }

    // Return property
    return static::$normalize;

  }

  protected static function getParamList()
  {

	return [ static::NAMESPACE_, static::DIRECTORY_, static::PROPERTIES_ ];

  }

  // Set your own properties list

  protected static function setPropertyList( array $property_list )
  {

    $property_list = array_values( $property_list );

    static::$property_list = $property_list;

  }

  public static function getPropertyList()
  {

    return static::$property_list;

  }

  // Is property or is param, returns int 0/1

  public static function is( $var_name )
  {

    return in_array( $var_name, static::getParamList() ) ? 0 : 1;

  }

  // Loads an interface, class or trait, or file
  // Called automatically or manually in the case of loading files

  public static function load( $interface )
  {

    $result = null;

    $label_namespace = static::NAMESPACE_;
    $label_directory = static::DIRECTORY_;

	$label_properties = static::PROPERTIES_;
      $label_property_extension = static::EXTENSION_;
      $label_property_normalize = static::NORMALIZE_;
      $label_property_result = static::RESULT_;

    // Loop through available packages in the singleton
    foreach ( static::$packages as $index => $package ) {
      if ( ! isset( $package[ $label_namespace ] ) || ! isset( $package[ $label_directory ] ) ) {
        continue;
      }

      // Required by the autoloader to load a package
      $namespace = $package[ $label_namespace ];
      $directory = $package[ $label_directory ];

      // If needs normalizing...
      if ( static::normalize(
        isset( $package[ $label_properties ][ $label_property_normalize ] ) ? $package[ $label_properties ][ $label_property_normalize ] : null
      ) ) {
		$namespace = static::doNormalize( $namespace );
        $directory = static::doNormalize( $directory );
      }

      $interface = static::doNormalize( $interface );
      $extension = isset( $package[ $label_properties ][ $label_property_extension ] ) ? '.' . $package[ $label_properties ][ $label_property_extension ] : '.' . 'php';

      $file = str_replace( $namespace, $directory, $interface ) . $extension;

      if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
        $file = $directory . DIRECTORY_SEPARATOR . $interface . $extension;

        if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
          continue;
        }
      }

      // We only include once, because we are loading interfaces, and therefore check included files
      if ( ! in_array( $file, get_included_files() ) ) {
        $result = require $file;

        $package = static::get( static::has( $namespace ) );

         // Set the package result
        $package = static::with( $package, static::getParamList(), $label_properties, static::with( $package[ $label_properties ], static::getPropertyList(), $label_property_result, $result ) );
        static::set( $package );

        break;
      }
    }

    // Null or require result
    return $result;

  }

  protected static function setDefaults( $packaged )
  {

    $label_namespace = static::NAMESPACE_;
    $label_properties = static::PROPERTIES_;
    $label_property_name = static::NAME_;
    $label_property_normalize = static::NORMALIZE_;

    if ( ! isset( $package[ $label_properties ][ $label_property_name ] ) ) {
      $packaged = static::with( $packaged, static::getParamList(), $label_properties, static::with( $packaged[ $label_properties ], static::getPropertyList(), $label_property_name, $packaged[ $label_namespace ] ) );
    }

    if ( ! isset( $package[ $label_properties ][ $label_property_normalize ] ) ) {
      $packaged = static::with( $packaged, static::getParamList(), $label_properties, static::with( $packaged[ $label_properties ], static::getPropertyList(), $label_property_normalize, static::normalize() ) );
    }

    return $packaged;

  }

  protected static function doNormalize( $item, $side = 'r' )
  {

    $fn = $side . 'trim';

    // True trim
    $normalized = $fn( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $item ), DIRECTORY_SEPARATOR );
  
    return $normalized;

  }

}
}