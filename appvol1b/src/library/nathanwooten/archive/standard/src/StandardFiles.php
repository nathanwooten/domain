<?php

namespace nathanwooten\Standard;

use nathanwooten\{

  Standard\StandardFilesInterface

};

use Exception;

if ( ! defined( 'LIB_PATH' ) ) die( __FILE__ );

class StandardFiles implements StandardFilesInterface
{

  public $add = [
    [
      [
        LIB_PATH,
        'nathanwooten' . DIRECTORY_SEPARATOR . 'standard' . DIRECTORY_SEPARATOR . 'lib'
      ],
      [
        'defines.php',
        'functions.php'
      ]
    ]
  ];

  public function __construct()
  {

    foreach ( $this->add as $params ) {
      $this->add( ...$params );
    }

  }

  public function add( $dir, array $files = [], $add = 1 )
  {

    $directory = '';

    if ( ! is_array( $dir ) ) $dir = [ $dir ];
    foreach ( $dir as $item ) {
      if ( ! $item ) {
        continue;
      }
      $directory .= DIRECTORY_SEPARATOR . $item;
    }
    $directory .= DIRECTORY_SEPARATOR;    

    foreach ( $files as $basename ) {

      $file = $directory . $basename;
      if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
        throw new Exception( 'Unknown required file: ' . $file );
      }

      $this->files[ $file ] = 0;

      if ( $add ) {
        require_once $file;
        $this->files[ $file ] = 1;
      }

    }

  }

  public function get( $dir, $basename ) {

    $dir = fsNorm( $dir, null );
    foreach ( $files as $file => $isIncluded ) {
      $basename = basename( $file );
      $path = str_replace( $basename, '', $file );

      if ( $dir === $path ) ) {
        if ( $isIncluded ) ) {
          return 1;
        }
        
      }

    }

  }

}
