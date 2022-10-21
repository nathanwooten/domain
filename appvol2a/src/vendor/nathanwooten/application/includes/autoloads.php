<?php

// LIB_PATH should be defined in the /public_html/*/require.php file

if ( ! defined( 'LIB_PATH' ) ) die( __FILE__ );

// Standard is the class that autoloads all the standard dependencies
// especially container, which is the dependency manager

if ( ! defined( 'STANDARD_DEPENDENCIES' ) ) define( 'STANDARD_DEPENDENCIES', [
  LIB_PATH . 'nathanwooten' => [
    [
      'nathanwooten\Application',
      'application' . DS . 'src'
    ]
  ]
] );

if ( ! isset( $autoloads ) ) {
  $autoloads = STANDARD_DEPENDENCIES;
}

return $autoloads;
