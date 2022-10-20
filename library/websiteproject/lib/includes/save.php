<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR . 'chrislocalmoving' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'http.lib.php';

//
$html_file = PROJECT_PATH . 'public_html' . $target . ( 1 < strlen( $target ) ? DIRECTORY_SEPARATOR : '' ) . 'index.html';

if ( ! isset( $template ) ) {
  if ( ! isset( $target ) ) {
    $target = getTarget();
  }
  $template = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'generate.php';
}

$put = file_put_contents( $html_file, $template );

if ( ! $put ) { throw new Exception( __FILE__ ); }

print $html_file . ' save was a success.';
