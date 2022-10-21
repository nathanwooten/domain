<?php

if ( ! function_exists( 'upFind' ) ) {
function upFind( $directory, array $directoryContains, $root = null )
{

  $is = [];

  while( $directory ) {

    if ( is_file( $directory ) ) {
      $directory = dirname( $directory ) . DIRECTORY_SEPARATOR;
    } else {
      $directory = rtrim( $directory, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
    }

    foreach ( $directoryContains as $contains ) {
      $item = $directory . $contains;

      if ( is_readable( $item ) ) {
        $is[] = $item;
      }
    }

    if ( count( $is ) === count( $directoryContains ) ) {
      return $directory;
    }

    $parent = dirname( $directory );

    if ( $directory === $root || $directory === $parent  ) {
      $directory = false;

    } else {
      $directory = $parent;
    }
  }

  return $directory;

}
}

$paths = [
  'PROJECT_PATH' => [ 'public_html' ],
];

foreach ( $paths as $define => $contents ) {
  if ( ! defined( $define ) ) {

    if ( empty( $contents ) ) {
      continue;
    }

    $contents = array_values( $contents );

    $has = upFind( __FILE__, $contents );
    if ( ! $has ) {
      throw new Exception;
    }

    $item = rtrim( $has, DIRECTORY_SEPARATOR );

    define( $define, $item );
  }
}

$top = PROJECT_PATH . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'top.php';
return $top = require $top;
