<?php

namespace Domain;

class DomainHelper
{

  public static function requireFile( $file )
  {

    if ( static::isReadable( $file ) ) {
      return require $file;
    }

    throw new Exception;

  }

  public static function isReadable( $check )
  {

    return is_readable( $check );

  }

  public static function scan( $path, callable $filter = null )
  {

    $scan = scandir( $path );
    if ( $filter ) {
      $scan = array_filter( $scan, $filter );
    }

    return $scan;

  }

  public static function normalize( $directory )
  {

    return trim( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $directory ), DIRECTORY_SEPARATOR );

  }

}
