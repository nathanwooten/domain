<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). DIRECTORY_SEPARATOR );

$lib = PROJECT_PATH . 'lib' . DIRECTORY_SEPARATOR;

require_once $lib . 'chrislocalmoving' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'http.lib.php';
require_once $lib . 'chrislocalmoving' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'view.lib.php';

if ( ! isset( $target ) ) {
  $target = getTarget();
}

$template = getTemplate( $target );
return $template;
