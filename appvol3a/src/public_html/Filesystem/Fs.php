<?php

namespace nathanwooten\Filesystem;

class Fs
{

  public static $fs = [];

  public function __construct( $root = PUBLIC_HTML )
  {

    $this->root = $root;

    static::$fs[ $root ] = $this;

  }

  public static function fsFind( $append = '' )
  {

    // find fs
    $find = PUBLIC_HTML . $append;
    if ( array_key_exists( $find, static::$fs ) ) {
      return static::$fs[ $find ];
    }

    $append = explode( DIRECTORY_SEPARATOR, trim( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $append ), DIRECTORY_SEPARATOR ) );
    while( $append ) {
      array_pop( $append );

      $find = PUBLIC_HTML . implode( DIRECTORY_SEPARATOR, $append );
      if ( array_key_exists( $find, static::$fs ) ) {
        $fs = static::$fs[ $find ];

        if ( count( $append ) ) {
          $item = static::find( $append );
        }
      }

    }

    return isset( $item ) ? $item : ( isset( $fs ) ? $fs : null );

  }

}
